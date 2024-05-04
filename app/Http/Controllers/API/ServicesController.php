<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\sbPolygonEngine;
use App\Models\Ad;
use App\Models\Category;
use App\Models\NetworkSections;
use App\Models\ProductServiceRequest;
use App\Models\SubCategory;
use App\Models\UserWallet;
use App\Models\Notify;
use App\Models\Token;
use App\Models\Store;

use App\Models\RequestRenewCard;
use App\Models\ServiceCardsRequest;
use App\Models\NetworksCardsRequest;
use App\Models\Wellet_profit;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\City;
use App\Models\ServiceCards;
use App\Models\Service;
use App\Models\ProductService;
use App\Models\ProductReview;
use App\Models\Wifi;
use App\Models\Networks;
use App\Models\NetworksCards;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class ServicesController extends Controller
{

    public function getServices()
    {
        $service = Service::query()->where('status', 'active')->get();
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'service' => $service]);
    }

    public function getServicesProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }

         
        $service = ProductService::query();
        if ($request->city_id > 0) {
            $service = $service->where(function($query)use ($request) {
                $query
                    ->where('city_id', $request->city_id)
                    ->orWhere("city_id", "0");
            });
        }
        $service = $service->where('status', 'active')->where('service_id', $request->service_id)->get();


        if (count($service) > 0) {
            if(get_user_carrency_from_api() == "turkey")
            {
                foreach ($service as $lolo)
                {
                    if(isset($lolo->is_dollar) && $lolo->is_dollar == 1)
                    {
                        $lolo->price =  $lolo->result($lolo->price);
                        $lolo->nagma =  $lolo->result($lolo->nagma_price);
                    }else{
                          $lolo->price =  $lolo->price;
                        $lolo->nagma =  $lolo->nagma_price;
                    }
                }
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'service' => $service]);

            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
                foreach ($service as $lolo)
                {
                    
                    if(isset($lolo->is_dollar) && $lolo->is_dollar == 1)
                    {
                        $lolo->price =  $lolo->price;
                        $lolo->nagma =  $lolo->result($lolo->nagma_price);
                    }
                    else
                    {
                        $lolo->price =  $lolo->result1($lolo->price);
                        $lolo->nagma =  $lolo->result1($lolo->nagma_price);
                    }
                }
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'service' => $service]);

            }
        }
        else
        {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'service' => $service]);

        }

    }

    public function checkWhatsApp(Request $request)
    {
        $network = ProductService::query()->findOrFail($request->product_id);
        $message = "فقط للتأكد من ان الوتس اب في البردكت سيرفس";
        return response()->json(['status' => ( isset($network->wapp_status) && $network->wapp_status == 1 ? true : false ) , 'code' => 200, 'message' => $message]);

    }

    public function getServiceCards(Request $request)
    {
        $user_id = auth('api')->id();

        $msg = check_version_in_post($request);
        if($msg != "")
        {
            return response()->json(['status' => false, 'code' => 200, 'message' => $msg]);
        }

        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'تم تسجيل الخروج بنجاح';
            return response()->json(['status' => false, 'code' => 200,
                'message' => $message]);
        }
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $network = ProductService::query()->findOrFail($request->product_id);
        $balanceIn = UserWallet::where('user_id', $user_id)->where('type', 0)->sum('total_price');
        $balanceOut = UserWallet::where('user_id', $user_id)->where('type', 1)->sum('total_price');
        $balance = $balanceIn - $balanceOut;

        if(get_user_carrency_from_api() == "turkey")
        {
            if (isset($network->is_dollar) && $network->is_dollar == 1) {

                $network->price = $network->result($network->price);
                $network->purchasing_price = $network->result($network->purchasing_price);
            }
        }
        elseif(get_user_carrency_from_api() == "dollar")
        {

            if (isset($network->is_dollar) && $network->is_dollar == 1)
            {
                $network->price =  $network->price;
                $network->purchasing_price =  $network->purchasing_price;
            }
            else
            {
                $network->price = $network->result1($network->price);
                $network->purchasing_price = $network->result1($network->purchasing_price);
            }
        }

        if ($network->price > $balance) {
            $message = __('api.noBalance');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        } else {

            $diff_price = $network->price - $network->purchasing_price;

            if(isset($network->wapp_status) && $network->wapp_status == 1)
            {
                $wapp = $network->whatsapp()->first();

                if(isset($wapp->id) && $wapp->id != "")
                {
                    //customer
                    $wallet_c = new UserWallet();
                    $wallet_c->user_id = $user_id;
                    $wallet_c->total_price = $network->price;
                    $wallet_c->title = 'خدمة';
                    $wallet_c->details = ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                    $wallet_c->type = 1;
                    $wallet_c->save();

                    if($wapp->status == 1)
                    {

                        $wapp_owner_amount = ($diff_price * $wapp->percent) / 100;

                        // wapp owner
                        $wallet = new UserWallet();
                        $wallet->user_id = $wapp->user_id;
                        $wallet->total_price = $wapp_owner_amount;
                        $wallet->title = 'خدمة';
                        $wallet->details = ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                        $wallet->type = 0;
                        $wallet->save();

                        //jawhar
                        $wallet_j = new UserWallet();
                        $wallet_j->user_id = 1;
                        $wallet_j->total_price = $network->price - $wapp_owner_amount;
                        $wallet_j->title = 'خدمة';
                        $wallet_j->details = ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                        $wallet_j->type = 0;
                        $wallet_j->save();

                        $Wellet_profit=new Wellet_profit();
                        $Wellet_profit->user_id=$user_id;
                        $Wellet_profit->profit = $diff_price - $wapp_owner_amount;
                        $Wellet_profit->purchasing_price= $network->purchasing_price;
                        $Wellet_profit->details= ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                        $Wellet_profit->type =0;
                        $Wellet_profit->city_id =$request->get('city_id');
                        $Wellet_profit->service_name =6;
                        $Wellet_profit->worker_id = $wapp->user_id;
                        $Wellet_profit->worker_profit = $wapp_owner_amount;
                        $Wellet_profit->status_wellet =1;
                        $Wellet_profit->save();
                        $Wellet_profit->id;



                    }
                    else
                    {

                        //jawhar
                        $wallet_j = new UserWallet();
                        $wallet_j->user_id = 1;
                        $wallet_j->total_price = $network->price;
                        $wallet_j->title = 'خدمة';
                        $wallet_j->details = ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                        $wallet_j->type = 0;
                        $wallet_j->save();

                        $Wellet_profit=new Wellet_profit();
                        $Wellet_profit->user_id=$user_id;
                        $Wellet_profit->profit =  $diff_price;
                        $Wellet_profit->purchasing_price= $network->purchasing_price;
                        $Wellet_profit->details= ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                        $Wellet_profit->type =0;
                        $Wellet_profit->city_id =$request->get('city_id');
                        $Wellet_profit->service_name =6;
                        $Wellet_profit->status_wellet =1;
                        $Wellet_profit->save();
                        $Wellet_profit->id;

                    }

                    //request
                    $req = new ProductServiceRequest();
                    $req->city_id = $request->get('city_id') ?? '1';
                    $req->user_id = $user_id;
                    $req->whatsapp_id = $wapp->id;
                    $req->product_service_id = $network->id;
                    $req->status = 0;
                    $req->number = "";
                    $req->j_price = isset( $wallet_j->total_price) &&  $wallet_j->total_price != "" ?  $wallet_j->total_price : 0;
                    $req->user_price = isset($wallet_c->total_price) && $wallet_c->total_price != "" ? $wallet_c->total_price : 0;
                    $req->wapp_owner_price = isset($wallet->total_price) && $wallet->total_price != "" ? $wallet->total_price : 0;
                    $req->wellet_profit_id =$Wellet_profit->id;
                    $req->currency = get_user_carrency_from_api();
                    $req->save();

                    //notify
                    $notify = new Notify();
                    $notify->user_id = $user_id;
                    $notify->order_id = 0;
                    $notify->messag_type = 0;
                    $notify->message = 'رقم الطلب: '. $req->id . '<br>' .'خدمة :' . $network->name . '<br>' . 'خدمة العملاء:' . $wapp->phone;
                    $notify->save();

                    $message = ' رقم الطلب : '. $req->id . '%0a' .' الخدمة :' . $network->name.'%0a'.'الرجاء تزويدي بالخدمة المطلوبة ';
                    return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'phone' => $wapp->phone]);
                }
                else
                {
                    $message = "واتس اب غير موجود";
                    return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                }

            }
            else
                if(isset($network->service_worker_status) && $network->service_worker_status == 1)
                {
                    $serviceCard = ServiceCards::query()->where('is_used', 0)->where('product_id', $request->product_id)->first();

                    if ($serviceCard) {

                        $wapp = $network->whatsapp_service_worker()->first();

                        if(isset($wapp->id) && $wapp->id != "")
                        {
                            //customer
                            $wallet_c = new UserWallet();
                            $wallet_c->user_id = $user_id;
                            $wallet_c->total_price = $network->price;
                            $wallet_c->title = 'خدمة';
                            $wallet_c->details = ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                            $wallet_c->type = 1;
                            $wallet_c->save();

                            if($wapp->status == 1)
                            {
                                $diff_price = $network->price - $network->purchasing_price;
                                $wapp_owner_amount = ($diff_price * $wapp->percent) / 100;

                                // wapp owner
                                $wallet = new UserWallet();
                                $wallet->user_id = $wapp->user_id;
                                $wallet->total_price = $wapp_owner_amount;
                                $wallet->title = 'خدمة';
                                $wallet->details = ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                                $wallet->type = 0;
                                $wallet->save();

                                //jawhar
                                $wallet_j = new UserWallet();
                                $wallet_j->user_id = 1;
                                $wallet_j->total_price = $network->price - $wapp_owner_amount;
                                $wallet_j->title = 'خدمة';
                                $wallet_j->details = ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                                $wallet_j->type = 0;
                                $wallet_j->save();

                                $Wellet_profit=new Wellet_profit();
                                $Wellet_profit->user_id=$user_id;
                                $Wellet_profit->profit = $diff_price - $wapp_owner_amount;
                                $Wellet_profit->purchasing_price= $network->purchasing_price;
                                $Wellet_profit->details= ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                                $Wellet_profit->type =0;
                                $Wellet_profit->city_id =$request->get('city_id');
                                $Wellet_profit->service_name =6;
                                $Wellet_profit->worker_id =$wapp->user_id;
                                $Wellet_profit->worker_profit = $wapp_owner_amount;
                                $Wellet_profit->status_wellet =0;
                                $Wellet_profit->save();
                                $Wellet_profit->id;

                            }
                            else
                            {

                                //jawhar
                                $wallet_j = new UserWallet();
                                $wallet_j->user_id = 1;
                                $wallet_j->total_price = $network->price;
                                $wallet_j->title = 'خدمة';
                                $wallet_j->details = ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                                $wallet_j->type = 0;
                                $wallet_j->save();

                                $Wellet_profit=new Wellet_profit();
                                $Wellet_profit->user_id=$user_id;
                                $Wellet_profit->profit = $diff_price;
                                $Wellet_profit->purchasing_price= $network->purchasing_price;
                                $Wellet_profit->details= ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                                $Wellet_profit->type =0;
                                $Wellet_profit->city_id =$request->get('city_id');
                                $Wellet_profit->service_name =6;
                                $Wellet_profit->status_wellet =0;
                                $Wellet_profit->save();
                                $Wellet_profit->id;

                            }

                            $serviceCard->is_used = 1;
                            $serviceCard->save();

                            $notify = new Notify();
                            $notify->user_id = $user_id;
                            $notify->order_id = 0;
                            $notify->messag_type = 0;
                            $notify->message = 'خدمة :' . $network->name . '<br>' . 'الرقم:' . $serviceCard->pin . $serviceCard->card_id . '<br>' . ' الكود: ' . $serviceCard->password;
                            $notify->save();

                            $notificationMessage = 'تم تخزين بيانات البطاقة في قائمة التنبيهات';
                            $tokens = Token::where('user_id', $user_id)->pluck('fcm_token')->toArray();
                            sendNotificationToUsers($tokens, $notificationMessage, 1, 0);

                            $serviceCardsRequest = new  ServiceCardsRequest();
                            $serviceCardsRequest->service_id = $serviceCard->service_id;
                            $serviceCardsRequest->service_cards_id = $serviceCard->id;
                            $serviceCardsRequest->product_service_id = $serviceCard->product_id;
                            $serviceCardsRequest->price = $network->price;
                            $serviceCardsRequest->user_id = $user_id;
                            $serviceCardsRequest->city_id = $request->get('city_id') ?? '1';
                            $serviceCardsRequest->wellet_profit_id = $Wellet_profit->id;
                            $serviceCardsRequest->currency = get_user_carrency_from_api();
                            $serviceCardsRequest->save();

                            $message = __('api.ok');
                            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'ServiceCards' => $serviceCard]);

                        }
                        else
                        {
                            $message = "واتس اب غير موجود";
                            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                        }


                    }
                    else {
                        $message = __('api.noCardAvalabil');
                        return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
                    }

                }
                else
                {
                    $serviceCard = ServiceCards::query()->where('is_used', 0)->where('product_id', $request->product_id)->first();
                    if ($serviceCard) {
                        $network = ProductService::query()->findOrFail($request->product_id);

                        $wallet = new UserWallet();
                        $wallet->user_id = $user_id;
                        $wallet->total_price = $network->price;
                        $wallet->title = 'خدمة';
                        $wallet->details = ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                        $wallet->type = 1;
                        $wallet->save();

                        $wallet_j = new UserWallet();
                        $wallet_j->user_id = 1;
                        $wallet_j->total_price = $network->price;
                        $wallet_j->title = 'خدمة';
                        $wallet_j->details = ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                        $wallet_j->type = 0;
                        $wallet_j->save();

                        $Wellet_profit=new Wellet_profit();
                        $Wellet_profit->user_id=$user_id;
                        $Wellet_profit->profit = $diff_price;
                        $Wellet_profit->purchasing_price= $network->purchasing_price;
                        $Wellet_profit->details= ' اسم الخدمة ' . $network->name . ' السعر ' . $network->price;
                        $Wellet_profit->type =0;
                        $Wellet_profit->city_id =$request->get('city_id');
                        $Wellet_profit->service_name =6;
                        $Wellet_profit->status_wellet =0;
                        $Wellet_profit->save();
                        $Wellet_profit->id;

                        $serviceCard->is_used = 1;
                        $serviceCard->save();
                        $notify = new Notify();
                        $notify->user_id = $user_id;
                        $notify->order_id = 0;
                        $notify->messag_type = 0;
                        $notify->message = 'خدمة :' . $network->name . '<br>' . 'الرقم:' . $serviceCard->pin . $serviceCard->card_id . '<br>' . ' الكود: ' . $serviceCard->password;
                        $notify->save();
                        $notificationMessage = 'تم تخزين بيانات البطاقة في قائمة التنبيهات';
                        $tokens = Token::where('user_id', $user_id)->pluck('fcm_token')->toArray();
                        sendNotificationToUsers($tokens, $notificationMessage, 1, 0);
                        $serviceCardsRequest = new  ServiceCardsRequest();
                        $serviceCardsRequest->service_id = $serviceCard->service_id;
                        $serviceCardsRequest->service_cards_id = $serviceCard->id;
                        $serviceCardsRequest->product_service_id = $serviceCard->product_id;
                        $serviceCardsRequest->price = $network->price;
                        $serviceCardsRequest->user_id = $user_id;
                        $serviceCardsRequest->city_id = $request->get('city_id') ?? '1';
                        $serviceCardsRequest->wellet_profit_id = $Wellet_profit->id;
                        $serviceCardsRequest->currency = get_user_carrency_from_api();
                        $serviceCardsRequest->save();

                        $message = __('api.ok');
                        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'ServiceCards' => $serviceCard]);
                    }
                    else {
                        $message = __('api.noCardAvalabil');
                        return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
                    }
                }
        }
    }


    public function getCategories()
    {
        $category = Category::query()->where('status', 'active')->where('is_deleted', 0)->orderBy('ordering', 'asc')->get();
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'category' => $category]);
    }
    public function getSubCategories(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        if ($request->category_id > 0) {
            $subcategory = SubCategory::query()->where('status', 'active')->where('category_id', $request->category_id)->where('store_id', 0)->where('is_deleted', 0)->get();
        } else {
            $subcategory = SubCategory::query()->where('store_id', 0)->where('status', 'active')->where('is_deleted', 0)->get();
        }
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'subcategory' => $subcategory]);
    }

    public function getProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sub_category_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        if ($request->sub_category_id > 0)
        {
            if ($request->store_id > 0)
            {
                $products = Product::query()->where('status', 'active')->where('store_id', $request->store_id)->where('subCategory_id', $request->sub_category_id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
            }
            else
            {
                $products = Product::query()->where('status', 'active')->where('store_id', 0)->where('subCategory_id', $request->sub_category_id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
            }

        }
        else
        {
            if ($request->store_id > 0)
            {
                $products = Product::query()->where('status', 'active')->where('store_id', $request->store_id)->where('is_deleted', 0)->get();
            }
            else
            {
                $products = Product::query()->where('status', 'active')->where('store_id', 0)->where('is_deleted', 0)->get();
            }

            if (count($products) > 0)
            {
                if (get_user_carrency_from_api() == "turkey")
                {
                    foreach ($products as $lolo)
                    {
                        if (isset($lolo->is_dollar) && $lolo->is_dollar == 1)
                        {
                            $lolo->price = $lolo->result($lolo->price);
                        }
                    }
                    $message = __('api.ok');
                    return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'products' => $products]);

                }
                elseif (get_user_carrency_from_api() == "dollar")
                {
                    foreach ($products as $lolo)
                    {
                        if (isset($lolo->is_dollar) && $lolo->is_dollar == 1)
                        {
                            $lolo->price = $lolo->price;
                        }
                        else
                        {
                            $lolo->price = $lolo->result1($lolo->price);
                        }
                    }
                    $message = __('api.ok');
                    return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'products' => $products]);

                }
            }
            else
            {
                $message = __('api.not_found');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'products' => $products]);
            }
        }
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'products' => $products]);
    }
    public function getNewest()
    {
        $newest = Product::query()->where('status', 'active')->where('store_id', 0)->where('newest', 1)->where('is_deleted', 0)->orderBy('id', 'desc')->limit(10)->get();
        $mostSelling = Product::query()->where('status', 'active')->where('most_selling', 1)->orderBy('id', 'desc')->where('is_deleted', 0)->limit(10)->get();
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'newest' => $newest, 'most_selling' => $mostSelling]);
    }

    public function allNewest()
    {
        $count = Product::query()->where('status', 'active')->where('store_id', 0)->where('is_deleted', 0)->where('newest', 1)->count();
        $newest = Product::query()->where('status', 'active')->where('newest', 1)->where('is_deleted', 0)->get();
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'count' => $count, 'newest' => $newest]);
    }

    public function allMostSelling()
    {
        $count = Product::query()->where('status', 'active')->where('store_id', 0)->where('most_selling', 1)->where('is_deleted', 0)->count();
        $mostSelling = Product::query()->where('status', 'active')->where('most_selling', 1)->where('is_deleted', 0)->get();
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'count' => $count, 'most_selling' => $mostSelling]);
    }

    public function getProduct(Request $request)
    {
        $product = Product::query()->where('id', $request->id)->with(['attachments', 'category', 'subCategory', 'colors', 'sizes'])->with(['userReview' => function ($query) {
            $query->where('status', 'active')->orderBy('id', 'desc')->with('user')->take(5);
        }])->first();
        $product ['review_count'] = ProductReview::query()->where('product_id', $request->id)->count();
        $product ['reviewAvg'] = ProductReview::query()->where('product_id', $request->id)->avg('rate');
        if ($product) {
            $product->increment('views');
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'product' => $product]);
        }
        if (count($product) > 0) {
            if(get_user_carrency_from_api() == "turkey")
            {
                foreach ($product as $lolo)
                {
                    if(isset($lolo->is_dollar) && $lolo->is_dollar == 1)
                    {
                        $lolo->price =  $lolo->result($lolo->price);
                    }
                }
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'product' => $product]);
            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
                foreach ($product as $lolo)
                {
                    if(isset($lolo->is_dollar) && $lolo->is_dollar == 1)
                    {
                        $lolo->price =  $lolo->price;
                    }
                    else
                    {
                        $lolo->price =  $lolo->result1($lolo->price);
                    }
                }
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'product' => $product]);

            }
        }
        else
        {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'product' => $product]);
        }
        $message = __('api.noProduct');
        return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
    }

    public function getProductReview(Request $request)
    {
        $ProductReview = ProductReview::where('status', 'active')->where('product_id', $request->id)->orderBy('id', 'desc')->with('user')->paginate(10);
        //     $ProductReview ['review_count'] = ProductReview::query()->where('status', 'active')->where('product_id', $request->id)->count();
        //  $ProductReview ['reviewAvg'] = ProductReview::query()->where('status', 'active')->where('product_id', $request->id)->avg('rate');
        if ($ProductReview) {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'productReview' => $ProductReview]);
        }
        $message = __('api.productReview');
        return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
    }

    public function productReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //  'email' => 'required|email',
            'product_id' => 'required',
            'rate' => 'required',
            'comment' => 'required',
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }

        $productReview = new  ProductReview();
        $productReview->product_id = $request->get('product_id');
        $productReview->rate = $request->get('rate');
        $productReview->comment = $request->get('comment');
        $productReview->user_id = $request->get('user_id');
        $productReview->save();
        $message = __('api.ok');

        return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
    }

    public function getWifi(Request $request)
    {

        if ($request->city_id > 0) {
            $wifi = Wifi::query()->where('status', 'active')->where('city_id', $request->city_id)->get();

        } else {
            $wifi = Wifi::query()->where('status', 'active')->get();
        }
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'wifi' => $wifi]);
    }

    public function getNetwork(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'wifi_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $network = Networks::query()->where('status', 'active')->where('wifi_id', $request->wifi_id)->get();
        if (count($network) > 0) {

            if(get_user_carrency_from_api() == "turkey")
            {
                foreach ($network as $lolo) {
                    if (isset($lolo->is_dollar) && $lolo->is_dollar == 1) {
                        $lolo->price = $lolo->result($lolo->price);
                        $lolo->nagma = $lolo->result($lolo->nagma_price);
                    }
                }
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'network' => $network]);
            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
                foreach ($network as $lolo)
                {
                    if(isset($lolo->is_dollar) && $lolo->is_dollar == 1)
                    {
                        $lolo->price =  $lolo->price;
                        $lolo->nagma =  $lolo->nagma_price;
                    }
                    else
                    {
                        $lolo->price = $lolo->result1($lolo->price);
                        $lolo->nagma = $lolo->result1($lolo->nagma_price);
                    }
                }
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'network' => $network]);

            }

        }else {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'network' => $network]);
        }

        }

    public function getNetworksCards(Request $request)
    {

        $user_id = auth('api')->id();
        $msg = check_version_in_post($request);
        if($msg != "")
        {
            return response()->json(['status' => false, 'code' => 200, 'message' => $msg]);
        }
//        dd($user_id." - ".$request->get('latitude')." - ".$request->get('longitude'));
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' => false, 'code' => 200,
                'message' => $message]);
        }
        $validator = Validator::make($request->all(), [
            'network_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }

        $balanceIn = UserWallet::where('user_id', $user_id)->where('type', 0)->sum('total_price');
        $balanceOut = UserWallet::where('user_id', $user_id)->where('type', 1)->sum('total_price');
        $balance = $balanceIn - $balanceOut;

        $carfBalance = Networks::query()->where('status', 'active')->where('id', $request->network_id)->first();
        if($carfBalance->nagma_price > 0){
            $price = $carfBalance->nagma_price;
        }else{
            $price = $carfBalance->price; 
        }
//        if (isset($carfBalance->is_dollar) && $carfBalance->is_dollar == 1) {
//            $carfBalance->price = $carfBalance->result($carfBalance->price);
//        }

        if(get_user_carrency_from_api() == "turkey")
        {
            if (isset($carfBalance->is_dollar) && $carfBalance->is_dollar == 1) {
                $price = $carfBalance->result($price);
            }
           }
        elseif(get_user_carrency_from_api() == "dollar")
        {

            if (isset($carfBalance->is_dollar) && $carfBalance->is_dollar == 1)
                {
                    $price =  $price;
                }
                else
                {
                    $price = $carfBalance->result1($price);
                }

        }

        if ($price > $balance) {
            $message = __('api.noBalance');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        } else {
            $networkCard = NetworksCards::query()->where('is_used', 0)->where('network_id', $request->network_id)->first();
            if ($networkCard) {
                $network = Networks::query()->findOrFail($request->network_id);
                $wifi = Wifi::query()->findOrFail($network->wifi_id);

                if(get_user_carrency_from_api() == "turkey")
                {
                    if (isset($network->is_dollar) && $network->is_dollar == 1) {
                        $price = $network->result($price);
                    }
                }
                elseif(get_user_carrency_from_api() == "dollar")
                {

                    if (isset($network->is_dollar) && $network->is_dollar == 1)
                    {
                        $price =  $price;
                    }
                    else
                    {
                        $price = $network->result1($price);
                    }

                }

                $wallet = new UserWallet();
                $wallet->user_id = $user_id;
                $wallet->total_price = $price;
                $wallet->title = 'شبكة واي فاي' . $wifi->name;
                $wallet->details = ' واي فاي ' . $network->name . ' السعر: ' . $price;
                $wallet->type = 1;
                $wallet->save();
                $networkCard->is_used = 1;
                $networkCard->save();
                $notify = new Notify();
                $notify->user_id = $user_id;
                $notify->order_id = 0;
                $notify->messag_type = 0;
                $notify->message = ' نوع البطاقة ' . $network->name . '<br>' . ' اسم المستخدم: ' . $networkCard->pin . '<br>' . ' كلمة السر: ' . $networkCard->password;
                $notify->save();
                $notificationMessage = 'تم تخزين بيانات البطاقة في قائمة التنبيهات';
                $tokens = Token::where('user_id', $user_id)->pluck('fcm_token')->toArray();
                sendNotificationToUsers($tokens, $notificationMessage, 1, 0);
                $networksCardsRequest = new  NetworksCardsRequest();
                $networksCardsRequest->network_id = $request->get('network_id');
                $networksCardsRequest->price = $network->price;
                $networksCardsRequest->user_id = $user_id;
                $networksCardsRequest->city_id = $request->get('city_id') ?? '1';
                $networksCardsRequest->latitude = $request->get('latitude') ?? '';
                $networksCardsRequest->longitude = $request->get('longitude') ?? '';
                $networksCardsRequest->store_id = $wifi->store_id;
                $networksCardsRequest->currency = get_user_carrency_from_api();
                $networksCardsRequest->save();

                if ($wifi->store_id > 0) {
                    $store = Store::where('id', $wifi->store_id)->first();
                    $app_percent = $price * $store->app_percent / 100;
                    $distributor_ratio= $price - $app_percent;

                    $wallet_j = new UserWallet();
                    $wallet_j->user_id = 1;
                    $wallet_j->total_price = $app_percent;
                    $wallet_j->title = 'شبكة واي فاي' . $wifi->name;
                    $wallet_j->details = ' واي فاي ' . $network->name . ' السعر: ' . $price . ' النسبة: ' . $app_percent;
                    $wallet_j->type = 0;
                    $wallet_j->save();

                    $Wellet_profit=new Wellet_profit();
                    $Wellet_profit->user_id=$user_id;
                    $Wellet_profit->profit = $app_percent;
                    $Wellet_profit->details=  ' واي فاي ' . $network->name . ' fdgdfgdg: ' . $price . ' النسبة: ' . $app_percent;
                    $Wellet_profit->type =0;
                    $Wellet_profit->city_id =$request->get('city_id');
                    $Wellet_profit->service_name =7;
                    $Wellet_profit->status_wellet =0;
                    $Wellet_profit->save();

                    $data1 = $request->get('latitude');
                    $data2 = $request->get('longitude');

                    $selected_user_id = -1;

                    try {

                        if (isset($data1) && $data1 != "" && isset($data2) && $data2 != "") {
                            $all_network = NetworkSections::where("store_id", $wifi->store_id)->get();

                            foreach ($all_network as $one_net) {
                                $a1 = explode(",", $one_net->top_point);
                                $a2 = explode(",", $one_net->bottom_point);
                                $b1 = explode(",", $one_net->right_point);
                                $b2 = explode(",", $one_net->left_point);

                                $polygonBox = [
                                    [(double)trim($a1[0]), (double)trim($a1[1])],
                                    [(double)trim($a2[0]), (double)trim($a2[1])],
                                    [(double)trim($b1[0]), (double)trim($b1[1])],
                                    [(double)trim($b2[0]), (double)trim($b2[1])],
                                ];

                                $sbPolygonEngine = new sbPolygonEngine($polygonBox);

                                $isCrosses = $sbPolygonEngine->isCrossesWith($data1, $data2);

                                if ($isCrosses) {
                                    $selected_user_id = $one_net->user_id;
                                    break;
                                }

                            }

                            if ($selected_user_id != -1) {
                                $networksCardsRequest->selected_user_id = $selected_user_id;
                                $networksCardsRequest->save();
                                $NetworkSections = NetworkSections::where('store_id', $wifi->store_id)->first();
                                if (isset($NetworkSections->id)) {
                                    $new_app_percent = $distributor_ratio * $NetworkSections->app_percent / 100;

                                    $wallet_distributor = new UserWallet();
                                    $wallet_distributor->user_id = $selected_user_id;
                                    $wallet_distributor->total_price = $new_app_percent;
                                    $wallet_distributor->title = 'شبكة واي فاي' . $wifi->name;
                                    $wallet_distributor->details = ' واي فاي ' . $network->name . ' السعر: ' . $price;
                                    $wallet_distributor->type = 0;
                                    $wallet_distributor->save();

                                    $wallet = new UserWallet();
                                    $wallet->user_id = $store->user_id;
                                    $wallet->total_price = ($distributor_ratio - $new_app_percent);
                                    $wallet->title = 'شبكة واي فاي';
                                    $wallet->details = ' واي فاي ' . $network->name . ' السعر: ' . $price;
                                    $wallet->type = 0;
                                    $wallet->save();

                                }

                            } else {
                                $wallet = new UserWallet();
                                $wallet->user_id = $store->user_id;
                                $wallet->total_price = $distributor_ratio;
                                $wallet->title = 'شبكة واي فاي';
                                $wallet->details = ' واي فاي ' . $network->name . ' السعر: ' . $price;
                                $wallet->type = 0;
                                $wallet->save();

                            }
                        } else {
                            $wallet = new UserWallet();
                            $wallet->user_id = $store->user_id;
                            $wallet->total_price = $distributor_ratio;
                            $wallet->title = 'شبكة واي فاي';
                            $wallet->details = ' واي فاي ' . $network->name . ' السعر: ' . $price;
                            $wallet->type = 0;
                            $wallet->save();
                        }

                    } catch (\Exception $e) {

                    }

                }

                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'networkCard' => $networkCard]);
            } else {
                $message = __('api.noCardAvalabil');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
            }
        }

    }


    public function reNewNetworksCards(Request $request)
    {
        $user_id = auth('api')->id();
        $msg = check_version_in_post($request);
        if($msg != "")
        {
            return response()->json(['status' => false, 'code' => 200, 'message' => $msg]);
        }

        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'تم تسجيل الخروج بنجاح';
            return response()->json(['status' => false, 'code' => 200,
                'message' => $message]);
        }
        $validator = Validator::make($request->all(), [
            'network_id' => 'required',
            'username' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }

        $balanceIn = UserWallet::where('user_id', $user_id)->where('type', 0)->sum('total_price');
        $balanceOut = UserWallet::where('user_id', $user_id)->where('type', 1)->sum('total_price');
        $balance = $balanceIn - $balanceOut;

        $network = Networks::query()->findOrFail($request->network_id);

            if(get_user_carrency_from_api() == "turkey")
            {
                if (isset($network->is_dollar) && $network->is_dollar == 1) {

                    $network->price = $network->result($network->price);
                }
            }
            elseif(get_user_carrency_from_api() == "dollar")
            {

                    if (isset($network->is_dollar) && $network->is_dollar == 1)
                    {
                        $network->price =  $network->price;
                    }
                    else
                    {
                        $network->price = $network->result1($network->price);
                    }
            }

        if ($network->price > $balance){
            $message = __('api.noBalance');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        else
        {
            $wifi = Wifi::query()->findOrFail($network->wifi_id);
            $RequestRenewCardt = new RequestRenewCard();
            $RequestRenewCardt->user_id = $user_id;
            $RequestRenewCardt->store_id = $wifi->store_id;
            $RequestRenewCardt->wifi_id = $network->wifi_id;
            $RequestRenewCardt->network_id = $network->id;
            $RequestRenewCardt->balance = $network->price;
            $RequestRenewCardt->username = $request->username;
            $RequestRenewCardt->mobile = $request->mobile;
            $RequestRenewCardt->city_id = $request->city_id;
            $RequestRenewCardt->latitude = $request->get('latitude') ?? '';
            $RequestRenewCardt->longitude = $request->get('longitude') ?? '';
            $RequestRenewCardt->currency = get_user_carrency_from_api();

            //   $RequestRenewCardt = (convertAr2En($request->get('mobile'))) ? $request->get('mobile') : $RequestRenewCardt->mobile;
            $RequestRenewCardt->save();

            $wallet_u = new UserWallet();
            $wallet_u->user_id = $user_id;
            $wallet_u->total_price =$network->price;
            $wallet_u->title = 'طلب تجديد شبكة';
            $wallet_u->details = ' واي فاي ' . $network->name . ' السعر: ' . $network->price;
            $wallet_u->type = 1;
            $wallet_u->save();

            if ($wifi->store_id > 0) {
                $store = Store::where('id', $wifi->store_id)->first();
                
                if($network->nagma_price > 0){
                    $reNewNetwork_percent =  $network->price - $network->nagma_price ; //* $store->reNewNetwork_percent / 100;
                    $distributor_ratio= $network->price - $reNewNetwork_percent;
                    
                }else{
                    $reNewNetwork_percent = $network->price * $store->reNewNetwork_percent / 100;
                    $distributor_ratio= $network->price - $reNewNetwork_percent;

                }
                
                $wallet_j = new UserWallet();
                $wallet_j->user_id = 1;
                $wallet_j->total_price = $reNewNetwork_percent;
                $wallet_j->title = 'طلب تجديد شبكة';
                $wallet_j->details = ' واي فاي ' . $network->name . ' السعر: ' . $network->price . ' النسبة: ' . $reNewNetwork_percent;
                $wallet_j->type = 0;
                $wallet_j->save();

                $Wellet_profit=new Wellet_profit();
                $Wellet_profit->user_id=$user_id;
                $Wellet_profit->profit = $reNewNetwork_percent;
                $Wellet_profit->details=  ' واي فاي ' . $network->name . ' السعر: ' . $network->price . ' النسبة: ' . $reNewNetwork_percent;
                $Wellet_profit->type =0;
                $Wellet_profit->city_id =$request->get('city_id');
                $Wellet_profit->service_name =8;
                $Wellet_profit->status_wellet =1;
                $Wellet_profit->save();
                $RequestRenewCardt->wellet_profit_id =$Wellet_profit->id;
                $RequestRenewCardt->save();

                $data1 = $request->get('latitude');
                $data2 = $request->get('longitude');

                $selected_user = null;

                try {

                    if(isset($data1) && $data1 != "" && isset($data2) && $data2 != "")
                    {
                        $all_network = NetworkSections::where("store_id",$wifi->store_id)->get();

                        foreach($all_network as $one_net)
                        {
                            $a1 = explode (",", $one_net->top_point);
                            $a2 = explode (",", $one_net->bottom_point);
                            $b1 = explode (",", $one_net->right_point);
                            $b2 = explode (",", $one_net->left_point);

                            $polygonBox = [
                                [(double)trim($a1[0]),(double)trim($a1[1])],
                                [(double)trim($a2[0]),(double)trim($a2[1])],
                                [(double)trim($b1[0]),(double)trim($b1[1])],
                                [(double)trim($b2[0]),(double)trim($b2[1])],
                            ];

                            $sbPolygonEngine = new sbPolygonEngine($polygonBox);

                            $isCrosses = $sbPolygonEngine->isCrossesWith($data1, $data2);

                            if($isCrosses)
                            {
                                $selected_user = $one_net;
                                break;
                            }

                        }

                        if($selected_user != null){
                            $NetworkSections = NetworkSections::where('store_id', $wifi->store_id)->first();
                            $new_reNewNetwork_percent = $distributor_ratio * $NetworkSections->reNewNetwork_percent / 100;

                            $RequestRenewCardt->selected_user_id = $selected_user->user_id;
                            $RequestRenewCardt->selected_user_reNewNetwork_percent = $new_reNewNetwork_percent;
                            $RequestRenewCardt->network_user_reNewNetwork_percent = $distributor_ratio - $new_reNewNetwork_percent;
                            $RequestRenewCardt->save();

                            if(isset($NetworkSections->id))
                            {
                                $wallet_distributor = new UserWallet();
                                $wallet_distributor->user_id = $selected_user->user_id;
                                $wallet_distributor->total_price = $new_reNewNetwork_percent;
                                $wallet_distributor->title = 'طلب تجديد شبكة' . $wifi->name;
                                $wallet_distributor->details = ' واي فاي ' . $network->name . ' السعر: ' . $network->price;
                                $wallet_distributor->type = 0;
                                $wallet_distributor->save();

                                $wallet = new UserWallet();
                                $wallet->user_id = $store->user_id;
                                $wallet->total_price = ($distributor_ratio - $new_reNewNetwork_percent);
                                $wallet->title = 'طلب تجديد شبكة';
                                $wallet->details = ' واي فاي ' . $network->name . ' السعر: ' . $network->price;
                                $wallet->type = 0;
                                $wallet->save();

                            }

                        }
                        else
                        {
                            $wallet = new UserWallet();
                            $wallet->user_id = $store->user_id;
                            $wallet->total_price = $distributor_ratio;
                            $wallet->title = 'طلب تجديد شبكة';
                            $wallet->details = ' واي فاي ' . $network->name . ' السعر: ' . $network->price;
                            $wallet->type = 0;
                            $wallet->save();

                        }
                    }
                    else
                    {
                        $wallet = new UserWallet();
                        $wallet->user_id = $store->user_id;
                        $wallet->total_price = $distributor_ratio;
                        $wallet->title = 'طلب تجديد شبكة';
                        $wallet->details = ' واي فاي ' . $network->name . ' السعر: ' . $network->price;
                        $wallet->type = 0;
                        $wallet->save();
                    }

                } catch (\Exception $e) {

                }

                $RequestRenewCardt->app_percent = $reNewNetwork_percent;
                $RequestRenewCardt->save();

                $notify = new Notify();
                $notify->user_id = $store->user_id;
                $notify->order_id = 0;
                $notify->messag_type = 0;
                $notify->message = ' طلب تجديد شبكة  ' . $network->name . ' السعر: ' . $network->price;
                $notify->save();

                $notificationMessage = ' طلب تجديد شبكة  ' . $network->name . ' السعر: ' . $network->price;
                $tokens = Token::where('user_id', $store->user_id)->pluck('fcm_token')->toArray();
                sendNotificationToUsers($tokens, $notificationMessage, 1, 0);
            }

            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);


        }


    }

    public function search(Request $request)
    {
        $items = Product::where('is_deleted', 0)->query();

        if ($request->has('text') && $request->text != '') {
            $search = $request->get('text');
            $products = Product::where('status', 'active')->whereTranslationLike('name', '%' . $search . '%')->paginate(12);
            if ($products) {
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'products' => $products]);
            } else {
                $message = __('api.noResult');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
            }

        }


    }

    public function filter(Request $request)
    {
        $products = Product::where('is_deleted', 0)->where('status', 'active');

        //   if ($request->has('offers') ) {
        //     if ($request->get('offers') ==1)
        //     {   
        //      $productoffer=Productoffer::where('offer_from','<=',now()->toDateString())->where('offer_to' ,'>=', now()->toDateString())->pluck('product_id')->toArray();
        //       $products =  $products->whereIn('id',$productoffer);      
        //     }
        // }

        if ($request->has('text') && $request->text != '') {
            $search = $request->get('text');
            $products = $products->whereTranslationLike('name', '%' . $search . '%');
        }

        if ($request->has('min_price') and $request->has('max_price')) {
            if ($request->get('min_price') != null and $request->has('max_price') != null) {

                $products = $products->whereBetween('price', [$request->get('min_price'), $request->get('max_price')]);
            }
        }

        if ($request->has('category_id')) {
            if ($request->get('category_id') != null) {
                $products = $products->where('category_id', $request->get('category_id'));
            }
        }
        if ($request->has('subCategory_id')) {
            if ($request->get('subCategory_id') != null) {
                $products = $products->where('subCategory_id', $request->get('subCategory_id'));
            }
        }


        if ($request->has('price_ordering')) {
            if ($request->get('price_ordering') == 1) {
                $products = $products->orderBy('price', 'asc')->paginate(20);
            } else {
                $products = $products->orderBy('price', 'desc')->paginate(20);
            }
        } else {
            $products = $products->orderBy('id', 'desc')->paginate(20);
        }


        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'products' => $products]);


    }

}
?>