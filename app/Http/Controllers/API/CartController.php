<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\WEB\Admin\ProductsController;
use App\Models\CartAddition;
use App\Models\City;
use App\Models\Setting;
use App\Models\Notify;

use App\Models\Order;
use App\Models\OrderProduct;

use App\Models\Cart;
use App\Models\Store;
use App\Models\Product;
use App\Models\UserWallet;

use App\Models\Rate;
use App\Models\OrderProductAddition;
use App\Models\PromotionCode;
use App\Models\Addition;
use App\Models\Wellet_profit;
use App\Notifications\ResetPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Token;
use App\Models\NotificationMessage;

use Illuminate\Support\Facades\Validator;
use Image;
use DB;


class CartController extends Controller
{
    public function image_extensions()
    {
        return array('jpg', 'png', 'jpeg', 'gif', 'bmp');
    }
    public function addProductToCart(Request $request, $id)
    {
        $user_id = auth('api')->id();

        if ($request->empty_cart_before == 1) {
            Cart::where('user_id', $user_id)->delete();
        }
        $product = Product::where('is_deleted', 0)->findOrFail($id);
        if ($product->status == 'not_active') {
            return response()->json(['status' => false, 'code' => 200, 'message' => __('api.product_not_available')]);
        }

        try{

        $my_store = Store::where("id",$product->store_id)->first();

        $all_my_carts = Cart::where("user_id",$user_id)->get();

        foreach ($all_my_carts as $one_cart)
        {
            $one_pro_now = Product::where("id",$one_cart->product_id)->where('is_deleted', 0)->first();

            $store_now = Store::where("id",$one_pro_now->store_id)->first();

            if($store_now->all_cities =="0")
            {
                if($my_store->city->id != $store_now->city->id)
                {
                    if( $my_store->all_cities =="0") {
                        return response()->json(['status' => false, 'code' => 200, 'message' => __('api.sameCity')]);
                    }
                }

            }

        }
        }catch (\Exception $e)
        {
            return response()->json(['status' => false, 'code' => 200, 'message' => "Error"]);
        }
        /*
       $check_cart =  Cart::where('user_id',$user_id)->first();
        if($check_cart) {
          if ($product->store_id != @$check_cart->product->store_id) {
               $message = __('api.sameStore');
               return response()->json(['status' => false, 'code' => 202 , 'another_store' => 'true', 'message' => $message]);
          }

        }
        */
        $chick = Cart::where('user_id', $user_id)->where('product_id', $id)->where('color_id', $request->color_id)->where('size_id', $request->size_id)->first();
        if ($chick) {
            {

                $chick->quantity = $chick->quantity + 1;
                $chick->save();
            }
        } else {
            $myCart = new Cart();
            $myCart->user_id = auth('api')->id();
            $myCart->fcm_token = $request->header('fcmToken');
            $myCart->product_id = $id;
            $myCart->quantity = 1;
            $myCart->color_id = $request->color_id;
            $myCart->size_id = $request->size_id;

            $myCart->save();
        }


        if ($request->addition_id != null) {
            foreach ($request->addition_id as $id => $addition_id) {
                $data[] = [
                    'addition_id' => $addition_id,
                    'cart_id' => $myCart->id,
                ];
            }
            CartAddition::insert($data);
        }

        $myNewCart = Cart::where('user_id', $user_id)->with('product')->get();
        $count_products = count($myNewCart);

        $total_cart = 0;
        $total_addition = 0;
        foreach ($myNewCart as $one) {
            $price_val = ($one->product->price_offer) ? $one->product->price_offer : $one->product->price;
            $total_cart += $price_val * $one->quantity;

        }



        $message = __('api.add_cart');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'count_products' => $count_products, 'total_cart' => $total_cart + $total_addition]);
    }

    public function getMyCart(Request $request)
    {
        $user_id = auth('api')->id();
        $exchange_rate = Setting::orderByDesc('id')->first()->exchange_rate;
        $myCart = Cart::where('user_id', $user_id)->with('product')->paginate(20);
        if ($myCart) {
            $myNewCart = Cart::where('user_id', '=', $user_id)->with('product')->get();
            $count_products = count($myNewCart);
            $total_cart = 0;
            foreach ($myNewCart as $one) {
                if ($one->product->is_dollar) {


                    $price = $exchange_rate * $one->product->price;
                } else {
                    $price = $one->product->price;
                }
                $total_cart += $price * $one->quantity;

            }

            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'total' => $total_cart, 'MyCart' => $myCart]);
        }
        $message = __('api.cartEmpty');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'MyCart' => $myCart]);

    }

    public function changeQuantity(Request $request, $id)
    {
        $user_id = auth('api')->id();

        $myCart = Cart::where('user_id', $user_id)->where('id', $id)->first();

        if ($myCart) {
            if ($request->type == 1) {
                $newValue = $myCart->quantity + 1;
            } else {
                $newValue = $myCart->quantity - 1;
            }
            $myCart->update(['quantity' => $newValue]);
            $myNewCart = Cart::where('user_id', $user_id)->with('product')->get();
            $total_cart = 0;
            $total_addition = 0;
            foreach ($myNewCart as $one) {
                $price_val = ($one->product->price_offer) ? $one->product->price_offer : $one->product->price;
                $total_cart += $price_val * $one->quantity;


            }
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'Quantity' => $newValue, 'total_cart' => $total_addition + $total_cart]);

        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        }
    }
    public function deleteProductCart(Request $request, $id)
    {
        $user_id = auth('api')->id();

        $myCart = Cart::where('user_id', $user_id)->where('id', $id)->delete();

        if ($myCart) {

            $myNewCart = Cart::where('user_id', $user_id)->with('product')->get();
            $total_cart = 0;
            $total_addition = 0;
            foreach ($myNewCart as $one) {
                $price_val = ($one->product->price_offer) ? $one->product->price_offer : $one->product->price;
                $total_cart += $price_val * $one->quantity;
            }

            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'total_cart' => $total_cart + $total_addition]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        }

    }
    public function old_checkOut(Request $request)
    {
        $user_id = auth('api')->id();
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required',
            'delivery_city_id' => 'required',
            //   'mobile' => 'required',
            'address_name' => 'required',
            'full_address' => 'required',
            'payment' => 'required',
            'payment' => 'required',
        ]);
        $stat = false;
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $myCart = Cart::where('user_id', $user_id)->with('product')->get();
        $firstProduct = Cart::where('user_id', $user_id)->with('product')->first();
        if ($myCart) {
            if ($myCart->isEmpty()) {
                $message = __('api.cartEmpty');
                return response()->json(['status' => false, 'code' => 202, 'message' => $message]);
            }
            $count_products = count($myCart);
            $totalproduct = 0;
            foreach ($myCart as $one) {
                $price_val = ($one->product->discount != 0) ? $one->product->discount : $one->product->price;
                $totalproduct += $price_val * $one->quantity;
            }
            $store = Store::where('id', $firstProduct->product->store_id)->first();
            $myCity = City::where('id', $request->delivery_city_id)->first();
            $total = $totalproduct;
            $promoCode = PromotionCode:: where('name', $request->get('promoCode_name'))->first();
            if ($promoCode) {
                $discount = ($total * $promoCode->discount) / 100;
            } else {
                $discount = 0;
            }
            $balanceIn = UserWallet::where('user_id', $user_id)->where('type', 0)->sum('total_price');
            $balanceOut = UserWallet::where('user_id', $user_id)->where('type', 1)->sum('total_price');
            $balance = $balanceIn - $balanceOut;
            if ($total > 50) {
                if ($total > $balance) {
                    $message = __('api.noBalance');
                    return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                }
                $stat = true;
            } else {
                $setting = Setting::first();
                if ($total + $setting->min_order > $balance) {
                    $message = __('api.noBalance');
                    return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                }
                $stat = true;
            }
            if ($stat) {
                $order = new Order();
                $order->user_id = $user_id;
                if ($store) {
                    $order->store_id = $store->id;
                } else {
                    $order->store_id = 0;
                }
                $order->delivery_city_id = $request->delivery_city_id;
                $order->customer_name = $request->customer_name;
                $order->mobile = $request->mobile;
                $order->latitude = $request->latitude;
                $order->longitude = $request->longitude;
                $order->full_address = $request->full_address;
                $order->address_name = $request->address_name;
                $order->note = $request->note;
                $order->payment = $request->payment;
                $order->code_name = ($promoCode) ? $promoCode->name : 0;
                if ($total > 50) {
                    $order->total_price = $total;
                } else {
                    $order->total_price = $total + $setting->min_order;
                }
                $order->discount_code = ($promoCode) ? $promoCode->discount : 0;
                $order->invoice_discount = $discount;
                $order->payment = $request->payment;
                $order->save();
                if ($order) {
                    foreach ($myCart as $one) {
                        if ($one->product->price_offer != 0) {
                            $price = ($one->product->price) - ($one->product->price * $one->product->discount);
                        } else {
                            $price = 0;
                        }
                        $ProductOrder = new OrderProduct();
                        $ProductOrder->order_id = $order->id;
                        $ProductOrder->product_id = $one->product_id;
                        $ProductOrder->quantity = $one->quantity;
                        $ProductOrder->price = $one->product->price;
                        $ProductOrder->color_id = $one->color_id;
                        $ProductOrder->size_id = $one->size_id;
                        $ProductOrder->discount = $price;
                        $ProductOrder->currency = get_user_carrency_from_api();
                        $ProductOrder->save();
                    }
                    Cart::where('user_id', $user_id)->delete();
                    $wallet = new UserWallet();
                    $wallet->user_id = $user_id;
                    $wallet->order_id = $order->id;
                    $wallet->title = 'طلب منتج';
                    $wallet->details = 'رقم الطلب#: ' . $order->id;
                    if ($total > 50) {
                        $wallet->total_price = $total;
                    } else {
                        $wallet->total_price = $total + $setting->min_order;
                    }
                    $wallet->type = 1;
                    $wallet->save();
                    if ($store) {
                        $message = 'لديك طلب جديد';
                        $notification = new Notify();
                        $notification->user_id = $store->user_id;
                        $notification->messag_type = 1;
                        $notification->message = $message;
                        $notification->save();
                        $tokens = Token::where('user_id', $store->user_id)->pluck('fcm_token')->toArray();
                        // return $tokens_ios;
                        sendNotificationToUsers($tokens, $message, 1, 0);
                        $wallet2 = new UserWallet();
                        $wallet2->user_id = $store->user_id;
                        $wallet2->order_id = $order->id;
                        $wallet2->title = 'طلب جديد';
                        $wallet2->details = 'رقم الطلب#: ' . $order->id;
                        $wallet2->total_price = $total;
                        $wallet2->type = 0;  //0=in 1=out
                        $wallet2->save();
                        if ($total < 50) {
                            $wallet_j = new UserWallet();
                            $wallet_j->user_id = 1;
                            $wallet_j->order_id = $order->id;
                            $wallet_j->title = 'طلب جديد(رسوم توصيل)';
                            $wallet_j->details = 'رقم الطلب#: ' . $order->id;
                            $wallet_j->total_price = $setting->min_order;
                            $wallet_j->type = 0;  //0=in 1=out
                            $wallet_j->save();
                        }
                    }
                }
                $message = __('api.status1');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message,
                    'checkOut' => ['phoneNumber' => $request->mobile, 'totalProduct' => $total, 'price_discount_code' => $discount, 'order' => $order]]);
            }
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function checkOut(Request $request)
    {
        $user_id = auth('api')->id();

        $msg = check_version_in_post($request);
        if($msg != "")
        {
            return response()->json(['status' => false, 'code' => 200, 'message' => $msg]);
        }


        $validator = Validator::make($request->all(), [
            'customer_name' => 'required',
            'delivery_city_id' => 'required',
            //   'mobile' => 'required',
            'address_name' => 'required',
            'full_address' => 'required',
            'payment' => 'required',

        ]);
        $stat = false;
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $lolo = [];
        $myCarts = Cart::where('user_id', $user_id)->with('product')->get();
        foreach ($myCarts as $one)
        {
            $lolo [] =  $one->product->store_id;
        }
        $all_stores_is = (array_unique($lolo));
        $all_carts = [];
        foreach ($all_stores_is as $onn)
        {
            $all_pro = Product::where('is_deleted', 0)->where("store_id",$onn)->get();
            $all_pro_ids = [];
            foreach ($all_pro as $pro)
            {
                $all_pro_ids [] = $pro->id;
            }
            $all_carts[]= Cart::where('user_id', $user_id)->whereIn("product_id",$all_pro_ids)->with('product')->get();
        }
        //111111111
        foreach($all_carts as $one_cart)
        {
            $myCart = $one_cart;
            if ($myCart) {
                if ($myCart->isEmpty()) {
                    $message = __('api.cartEmpty');
                    return response()->json(['status' => false, 'code' => 202, 'message' => $message]);
                }
                $totalproduct = 0;
                foreach ($myCart as $one) {
                    $price_val = ($one->product->discount != 0) ? $one->product->discount : $one->product->price;
                    $totalproduct += $price_val * $one->quantity;
                }
                $total = $totalproduct;
                
                if($setting->min_order > $total )
                {
                    return response()->json(['status' => false, 'code' => 200, 'message' => $setting->min_order."الحد الأدنى للطب "]);
                }
                
                $promoCode = PromotionCode:: where('name', $request->get('promoCode_name'))->first();
                if ($promoCode) {
                    $discount = ($total * $promoCode->discount) / 100;
                } else {
                    $discount = 0;
                }
                $balanceIn = UserWallet::where('user_id', $user_id)->where('type', 0)->sum('total_price');
                $balanceOut = UserWallet::where('user_id', $user_id)->where('type', 1)->sum('total_price');
                $balance = $balanceIn - $balanceOut;
                if ($total > $setting->order_without_discount) {
                    if ($total > $balance) {
                        $message = __('api.noBalance');
                        return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                    }
                    $stat = true;
                } else {
                    $setting = Setting::first();
                    if ($total + $setting->min_order > $balance) {
                        $message = __('api.noBalance');
                        return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                    }
                    $stat = true;
                }
            }
            else
            {
                $message = __('api.not_found');
                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
            }
        }
        $boo = true;
        //2222222
        foreach ( $all_carts as $one_cart)
        {
            $myCart =$one_cart;
            $firstProduct = $one_cart[0];
            $totalproduct = 0;
            foreach ($myCart as $one) {
                $price_val = ($one->product->discount != 0) ? $one->product->discount : $one->product->price;
                $totalproduct += $price_val * $one->quantity;
            }
            $store = Store::where('id', $firstProduct->product->store_id)->first();
            $total = $totalproduct;
            $promoCode = PromotionCode:: where('name', $request->get('promoCode_name'))->first();
            if ($promoCode) {
                $discount = ($total * $promoCode->discount) / 100;
            } else {
                $discount = 0;
            }
            $balanceIn = UserWallet::where('user_id', $user_id)->where('type', 0)->sum('total_price');
            $balanceOut = UserWallet::where('user_id', $user_id)->where('type', 1)->sum('total_price');
            $balance = $balanceIn - $balanceOut;
            if ($total > $setting->order_without_discount)
            {
                $stat = true;
            }
            else
            {
                $setting = Setting::first();
                $stat = true;
            }
            if ($stat) {
                $order = new Order();
                $order->user_id = $user_id;
                if ($store) {
                    $order->store_id = $store->id;
                } else {
                    $order->store_id = 0;
                }
                $order->delivery_city_id = $request->delivery_city_id;
                $order->customer_name = $request->customer_name;
                $order->mobile = $request->mobile;
                $order->latitude = $request->latitude;
                $order->longitude = $request->longitude;
                $order->full_address = $request->full_address;
                $order->address_name = $request->address_name;
                $order->note = $request->note;
                $order->payment = $request->payment;
                $order->currency = get_user_carrency_from_api();
                $order->code_name = ($promoCode) ? $promoCode->name : 0;
                if ($total > 50 || $request->delivery_cost == 2 ) {
                    $order->total_price = $total;
                } else {
                    if($boo)
                    {
                        $order->total_price = $total + $setting->min_order;
                    }
                    else
                    {
                        $order->total_price = $total;
                    }
                }
                $order->discount_code = ($promoCode) ? $promoCode->discount : 0;
                $order->invoice_discount = $discount;
                $order->payment = $request->payment;
                $order->save();
                if ($order)
                {
                    foreach ($myCart as $one) {
                        if ($one->product->price_offer != 0) {
                            $price = ($one->product->price) - ($one->product->price * $one->product->discount);
                        } else {
                            $price = 0;
                        }
                        $ProductOrder = new OrderProduct();
                        $ProductOrder->order_id = $order->id;
                        $ProductOrder->product_id = $one->product_id;
                        $ProductOrder->quantity = $one->quantity;
                        $ProductOrder->price = $one->product->price;
                        $ProductOrder->color_id = $one->color_id;
                        $ProductOrder->size_id = $one->size_id;
                        $ProductOrder->discount = $price;
                        $ProductOrder->currency = get_user_carrency_from_api();
                        $ProductOrder->save();
                    }
                    Cart::where('user_id', $user_id)->delete();
                    $wallet = new UserWallet();
                    $wallet->user_id = $user_id;
                    $wallet->order_id = $order->id;
                    $wallet->title = 'طلب منتج';
                    $wallet->details = 'رقم الطلب#: ' . $order->id;
                    if ($total > 50 || $request->delivery_cost == 2) {
                        $wallet->total_price = $total;
                    }
                    else
                    {
                        $wallet->total_price = $total + $setting->min_order;
                    }
                    $wallet->type = 1;
                    $wallet->save();
                    if ($store)
                    {
                        $store = Store::where('id',$firstProduct->product->store_id)->first();

                        if ($total > 50) {
                            $app_percent = $total * $store->app_percent / 100;
                        }
                        else
                        {
                            $only_profit  = $total - $setting->min_order;
                            $app_percent = $only_profit * $store->app_percent / 100;
                        }

                        $message = 'لديك طلب جديد';
                        $notification = new Notify();
                        $notification->user_id = $store->user_id;
                        $notification->messag_type = 1;
                        $notification->message = $message;
                        $notification->save();
                        $tokens = Token::where('user_id', $store->user_id)->pluck('fcm_token')->toArray();
                        // return $tokens_ios;
                        sendNotificationToUsers($tokens, $message, 1, 0);
                        $wallet2 = new UserWallet();
                        $wallet2->user_id = $store->user_id;
                        $wallet2->order_id = $order->id;
                        $wallet2->title = 'طلب جديد';
                        $wallet2->details = 'رقم الطلب#: ' . $order->id;
                        $wallet2->total_price = $total - $app_percent;
                        $wallet2->type = 0;  //0=in 1=out
                        $wallet2->save();

                        $Wellet_profit=new Wellet_profit();
                        $Wellet_profit->user_id=$user_id;
                        $Wellet_profit->profit = $app_percent;
                        $Wellet_profit->details= 'رقم الطلب#: ' . $order->id;
                        $Wellet_profit->type =0;
                        $Wellet_profit->city_id =$request->delivery_city_id;
                        $Wellet_profit->service_name =5;
                        $Wellet_profit->status_wellet =1;
                        $Wellet_profit->save();
                        $Wellet_profit->id;
                        $order->wellet_profit_id =$Wellet_profit->id;
                        $order->save();

                        if ($total < 50) {
                            $soso = $setting->min_order + $app_percent;
                            $wallet_j = new UserWallet();
                            $wallet_j->user_id = 1;
                            $wallet_j->order_id = $order->id;
                            $wallet_j->title = ' طلب جديد(رسوم توصيل) مع نسبة الربح ';
                            $wallet_j->details = 'رقم الطلب#: ' . $order->id;
                            $wallet_j->total_price =$soso;
                            $wallet_j->type = 0;  //0=in 1=out
                            $wallet_j->save();
                        }
                        else
                        {
                            $wallet_j = new UserWallet();
                            $wallet_j->user_id = 1;
                            $wallet_j->order_id = $order->id;
                            $wallet_j->title = 'نسبة الربح من منتجات احد المتاجر ';
                            $wallet_j->details = 'رقم الطلب#: ' . $order->id;
                            $wallet_j->total_price =$app_percent;
                            $wallet_j->type = 0;  //0=in 1=out
                            $wallet_j->save();
                        }
                    }
                }
            }
            $boo = false;
        }
        $message = __('api.status1');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message,
            'checkOut' => ['phoneNumber' => $request->mobile, 'totalProduct' => $total, 'price_discount_code' => $discount, 'order' => $order]]);
    }

    public function checkPromo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $promo = PromotionCode::where('name', $request->get('name'))->whereDate('end', '>=', date('Y-m-d'))->whereDate('start', '<=', date('Y-m-d'))->where('status', 'active')->first();
        if ($promo) {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'PromotionCode' => $promo]);
        } else {
            $message = __('api.wrongPromo');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);

        }

    }
    public function deleteCartItems(Request $request)
    {
        $user_id = auth('api')->id();

        $myCart = Cart::where('user_id', $user_id)->delete();

        if ($myCart) {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        }

    }

}
