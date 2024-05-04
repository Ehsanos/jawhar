<?php

namespace App\Http\Controllers\API;

use App\Models\Admin;
use App\Models\Slider;
use App\Models\News;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\RequestRenewCard;
use App\Models\ImportFile;
use App\Models\Language;
use App\Notifications\ResetPassword;
use App\Models\Wellet_profit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Token;
use App\Models\Notify;
use App\Models\Store;
use App\Models\Wifi;
use App\Models\Networks;
use App\Models\NetworksCards;
use App\Models\Notifiy;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\ProductColor;
use App\Models\UserWallet;
use App\Models\Setting;
use App\Models\RequestMobileBalance;
use App\Models\Order;
use App\Models\Payment;
use App\Models\BalanceCard;
use App\Models\NetworksCardsRequest;
use App\Models\EnableNotificationNetwork;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CardImport;


use Image;
use DB;

class StoreController extends Controller
{
    public function broker()
    {
        return Password::broker('users');
    }

    public function image_extensions()
    {
        return array('jpg', 'png', 'jpeg', 'gif', 'bmp');
    }

    public function register(Request $request)
    {
        $name = $request->get('name');
        $store_name = $request->get('store_name');
        $email = $request->get('email');
        $mobile = convertAr2En($request->get('mobile'));
        $store_mobile = convertAr2En($request->get('store_mobile'));
        $password = bcrypt($request->get('password'));

        $validator = Validator::make($request->all(),
            ['name' => 'required',
                'email' => 'required|email|unique:users',
                //'mobile' => 'required|digits_between:8,12|unique:users',
                'password' => 'required|min:6',
                'store_name' => 'required',
                'store_mobile' => 'required|digits_between:8,12',
                'logo' => 'required',
                'address' => 'required',
                'store_category_id' => 'required',
                'city_id' => 'required',
                'details' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'is_cash' => 'required',
                'is_wallet' => 'required',
                'is_online' => 'required',
                'is_delivery' => 'required',
                'is_pickup' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->messages()->all())]);
        }
        $newUser = new User();
        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->mobile = $mobile;
        $newUser->status = 'active';
        $newUser->type = '1';
//store_owner
        $newUser->password = $password;
        if ($request->hasFile('image_profile')) {
            $image = $request->file('image_profile');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/users/$file_name");
            $newUser->image_profile = $file_name;
        }
        $newUser->save();
        $newUser->user_code = "10$newUser->id";
        $newUser->save();
        if ($newUser) {
            $newStore = new Store();
            $newStore->user_id = $newUser->id;
            $newStore->store_name = $store_name;
            $newStore->mobile = $store_mobile;
            $newStore->address = $request->address;
            $newStore->city_id = $request->city_id;
            $newStore->store_category_id = $request->store_category_id;
            $newStore->details = $request->details;
            $newStore->latitude = $request->latitude;
            $newStore->longitude = $request->longitude;
            $newStore->is_cash = $request->is_cash;
            $newStore->is_wallet = $request->is_wallet;
            $newStore->is_online = $request->is_online;
            $newStore->is_delivery = $request->is_delivery;
            $newStore->is_pickup = $request->is_pickup;
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $extention = $logo->getClientOriginalExtension();
                $file_name = str_random(15) .
                    "" . rand(1000000, 9999999) .
                    "" . time() .
                    "_" . rand(1000000, 9999999)
                    .
                    "." . $extention;
                Image::make($logo)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save("uploads/images/stores/$file_name");
                $newStore->logo = $file_name;
            }
            $newStore->save();
            $massege = __('api.storeAdded');
            return response()->json(['status' =>
                true, 'code' => 200, 'message' => $massege
            ]);
        }

        $massege = __('api.whoops');
        return response()->json(['status' => false, 'code' => 400, 'message' => $massege]);
    }

    public function editStoreProfile(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        $store = Store::where('user_id', $user_id)->first();
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' => false, 'code' =>
                200, 'message' => $message
            ]);
        }
        $validator = Validator::make($request->all(), ['name' => 'required', 'mobile' => 'required',]);
        $name = ($request->has('name')) ? $request->get('name') : $user->name;
        $mobile = (convertAr2En($request->get('mobile'))) ? $request->get('mobile') : $user->mobile;
        $user->name = $name;
        $user->mobile = $mobile;
        if ($request->hasFile('image_profile')) {
            $imageProfile = $request->file('image_profile');
            $extention = $imageProfile->getClientOriginalExtension();
            $file_name = str_random(15) .
                "" . rand(1000000, 9999999)
                .
                "" . time() .
                "_" . rand(1000000, 9999999) .
                "." . $extention;
            Image::make($imageProfile)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/users/$file_name");
            $user->image_profile = $file_name;
        }
        $user->save();
        if ($user) {
            if ($store) {
                $store->store_name = $request->store_name ?? $store->store_name;
                $store->mobile = $request->store_mobile ?? $store->mobile;
                $store->address = $request->address ?? $store->address;
                $store->city_id = $request->city_id ?? $store->city_id;
                $store->store_category_id = $request->store_category_id ?? $store->store_category_id;
                $store->details = $request->details ?? $store->details;
                $store->latitude = $request->latitude ?? $store->latitude;
                $store->longitude = $request->longitude ?? $store->longitude;
                $store->is_cash = $request->is_cash ?? $store->is_cas;
                $store->is_wallet = $request->is_wallet ?? $store->is_wallet;
                $store->is_online = $request->is_online ?? $store->is_online;
                $store->is_delivery = $request->is_delivery ?? $store->is_delivery;
                $store->is_pickup = $request->is_pickup ?? $store->is_pickup;
                if ($request->hasFile('logo')) {
                    $logo = $request->file('logo');
                    $extention = $logo->getClientOriginalExtension();
                    $file_name = str_random(15) .
                        "" . rand(1000000, 9999999) .
                        "" . time() .
                        "_" . rand(1000000, 9999999) .
                        "." . $extention;
                    Image::make($logo)->resize(800,
                        null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save("uploads/images/stores/$file_name");
                    $store->logo = $file_name;
                }
                $store->save();
                $message = __('api.edit');
                return response()->json(['status' => true, 'code' => 200, 'user' => $user, 'store' => $store,
                    'message' => implode("\n", $validator->messages()->all())
                ]);
            }
        } else {
            $message = __('api.not_edit');
            return response()->json(['status' => false, 'code' => 200, 'message' => $validator]);
        }
    }

    public function myProducts(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' => false, 'code' =>
                200, 'message' => $message
            ]);
        }
        if ($user) {
            $storeId = Store::where('user_id', $user_id)->first();

            $products = Product::query()->where('is_deleted', 0)->where('store_id', $storeId->id);

            if ($request->has('category_id')) {
                if ($request->get('category_id') != null)
                    $products->where('category_id', $request->category_id);
            }
            if ($request->has('status')) {
                if ($request->get('status') != null)
                    $products->where('status', $request->status);
            }

            if ($request->has('name')) {
                if ($request->get('name') != '')
                    $products->whereTranslationLike('name', '%' . $request->get('name') . '%');
                // $products->where(function($q) use($request) {$q->whereTranslationLike('name', '%' . $request->get('name') . '%', 'en')->orWhereTranslationLike('name', '%' . $request->get('name') . '%', 'ar');});
            }
            $products = $products->get();
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message,
                'products' => $products
            ]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function storeOrders(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        if ($user) {
            $storeId = Store::where('user_id', $user_id)->first();
            $orders = Order::where('store_id', $storeId->id)->orderBy('id', 'desc')->with('order_products')->get();
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message,
                'orders' => $orders
            ]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function changeOrderStatus(Request $request)
    {
        $user_id = auth('api')->id();
        $setting = Setting::first();
        $storeId = Store::where('user_id', $user_id)->first();
        $validator = Validator::make($request->all(), ['order_id' => 'required', 'status' => 'required',]);
        $order = Order::findOrFail($request->order_id);
        if ($order->store_id != $storeId->id) {
            $message = __('api.not_found');
            return response()->json(['status' =>
                false, 'code' => 200, 'message' => $message
            ]);
        }

        if ($request->status == 0) {
            $message = __('api.OrderIsPreparing');
            $order->status = $request->status;
            $order->save();
        } elseif ($request->status == 1) {
            $message = __('api.OrderIsOnDelivery');
            $order->status = $request->status;
            $order->save();
        } elseif ($request->status == 2) {
            $message = __('api.OrderIsComplete');
            $order->status = $request->status;
            $order->save();

            $Wellet_profit = Wellet_profit::where('id', $order->wellet_profit_id)->first();
            $Wellet_profit->status_wellet = 0;
            $Wellet_profit->save();
        } elseif ($request->status == 3) {

            $app_percent = $order->total_price * $order->Store->app_percent / 100;
            $soso = $setting->min_order + $app_percent;

            if (User_Wallet_Check_Balance(1,$app_percent) && User_Wallet_Check_Balance($storeId->user_id, ($order->total_price > 50 ? ($order->total_price - $app_percent) : ($order->total_price - $soso))))
            {
                $message = __('api.OrderIsCancel');
                $order->status = $request->status;
                $order->save();

                $wallet = new UserWallet();
                $wallet->user_id = $order->user_id;
                $wallet->order_id = $order->id;
                $wallet->title = 'اعادة رصيد';
                $wallet->details = 'رقم الطلب: ' . $order->id;
                $wallet->total_price = $order->total_price;
                $wallet->type = 0;
                $wallet->save();

                $wallet_s = new UserWallet();
                $wallet_s->user_id = $storeId->user_id;
                $wallet_s->order_id = $order->id;
                $wallet_s->title = 'إلغاء طلب';
                $wallet_s->details = 'رقم الطلب: ' . $order->id;

                if ($order->total_price > 50) {
                    $wallet_s->total_price = $order->total_price - $app_percent;
                    $wallet_j = new UserWallet();
                    $wallet_j->user_id = 1;
                    $wallet_j->order_id = $order->id;
                    $wallet_j->title = ' الغاء طلب نسبة الربح من متجر  ';
                    $wallet_j->details = 'رقم الطلب#: ' . $order->id;
                    $wallet_j->total_price = $app_percent;
                    $wallet_j->type = 1;  //0=in 1=out
                    $wallet_j->save();
                } else {
                    $wallet_s->total_price = $order->total_price - $soso;
                    $wallet_J = new UserWallet();
                    $wallet_J->user_id = 1;
                    $wallet_J->order_id = $order->id;
                    $wallet_J->title = 'إلغاء طلبد(رسوم توصيل) مع نسبة الربح ';
                    $wallet_J->details = 'رقم الطلب: ' . $order->id;
                    $wallet_J->total_price = $soso;
                    $wallet_J->type = 1;
                    $wallet_J->save();
                }
                $wallet_s->type = 1;
                $wallet_s->save();

                $Wellet_profit = Wellet_profit::where('id', $order->wellet_profit_id)->first();
                $Wellet_profit->delete();

            } else {
                return response()->json(['status' => false, 'code' => 200, 'message' => 'احد المستفيدين من ارباح هذه الخدمة ليس لديه رصيد كافي في محفظته ']);
            }

        }
            $order_id = $request->order_id;
            $tokens = Token::where('user_id', $order->user_id)->pluck('fcm_token')->toArray(); // return $tokens_ios; sendNotificationToUsers($tokens,$message, '2', $order_id);
            $notifiy = new Notifiy();
            $notifiy->user_id = $order->user_id;
            $notifiy->order_id = $order_id;
            $notifiy->message = $message;
            $notifiy->save();
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'order' => $order]);

    }

    public function myNews(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged
out successfully';
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        if ($user) {
            $storeId = Store::where('user_id', $user_id)->first();
            $news = News::where('store_id', $storeId->id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'news' => $news]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function mySliders(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' =>
                false, 'code' => 200, 'message' => $message
            ]);
        }
        if ($user) {
            $storeId = Store::where('user_id', $user_id)->first();
            $sliders = Slider::where('store_id', $storeId->id)->orderBy('id', 'desc')->get();
            $message = __('api.ok');
            return response()->json(['status' =>
                true, 'code' => 200, 'message' => $message, 'sliders' => $sliders
            ]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function addProduct(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' => false, 'code' =>
                200, 'message' => $message
            ]);
        }
        if ($user) {
            $validator = Validator::make($request->all(), ['name_ar' => 'required', 'name_en' => 'required', 'price' => 'required |numeric', 'sub_category_id' => 'required', 'image' => 'required|image|mimes:jpeg,jpg,png,gif',]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->messages()->all())]);
            }
            $storeId = Store::where('user_id', $user_id)->first();
            $product = new Product();
            $product->store_id = $storeId->id;
            $product->price = $request->price;
            $product->category_id = $request->category_id;
            $product->discount = $request->discount ?? 0;
            $product->offer_from = $request->offer_from;
            $product->offer_to = $request->offer_to;
            $product->subCategory_id = $request->sub_category_id;
            $product->is_dollar = $request->is_dollar;  // 0=tr  1=$
            $product->count = $request->product_count;
            $locales = Language::all()->pluck('lang');
            foreach ($locales as $locale) {
                $product->translateOrNew($locale)->name = $request->get('name_' . $locale);
                $product->translateOrNew($locale)->description = $request->get('description_'
                    . $locale);
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extention = $image->getClientOriginalExtension();
                $file_name = str_random(15) .
                    "" . rand(1000000, 9999999) .
                    "" . time() .
                    "_" . rand(1000000, 9999999) .
                    "." . $extention;
                Image::make($image)->resize(800,
                    null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save("uploads/images/products/$file_name");
                $product->image = $file_name;
            }
            $product->save();
            if ($product) {
                if ($request->hasFile('extra_images')) {
                    $files = $request->file('extra_images');
                    foreach ($files as $one) {
                        $image = str_random(15) .
                            "_" . rand(1000000, 9999999) .
                            "_" . time() .
                            "_" . rand(1000000, 9999999) .
                            ".jpg";
                        Image::make($one)->resize(800, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save("uploads/images/products/$image");
                        $newAttach = new ProductImage();
                        $newAttach->product_id = $product->id;
                        $newAttach->product_img = $image;
                        $newAttach->save();
                    }
                }
                if ($request->has('color_id')) {
                    $color = $request->color_id;
                    foreach ($color as $one) {
                        $newColor = new ProductColor();
                        $newColor->product_id = $product->id;
                        $newColor->color_id = $one;
                        $newColor->save();
                    }
                }
                if ($request->has('size_id')) {
                    $Size = $request->size_id;
                    foreach ($Size as $one) {
                        $newSize = new ProductSize();
                        $newSize->product_id = $product->id;
                        $newSize->size_id = $one;
                        $newSize->save();
                    }
                }
            }
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'product' => $product]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function editProduct(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged
out successfully';
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        if ($user) {
            $validator = Validator::make($request->all(), ['product_id' => 'required', 'name_ar' => 'required', 'name_en' => 'required', 'price' => 'required
    |numeric', 'sub_category_id' => 'required',]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->messages()->all())]);
            }
            $storeId = Store::where('user_id', $user_id)->first();
            $product = Product::where('store_id', $storeId->id)->where('is_deleted', 0)->findOrFail($request->product_id);
            $product->price = $request->price;
            $product->category_id = $request->category_id;
            $product->discount = $request->discount ?? 0;
            $product->status = $request->status;
            $product->offer_from = $request->offer_from;
            $product->offer_to = $request->offer_to;
            $product->subCategory_id = $request->sub_category_id;
            $product->is_dollar = $request->is_dollar;
            $product->count = $request->product_count;
            $locales = Language::all()->pluck('lang');
            foreach ($locales as $locale) {
                $product->translateOrNew($locale)->name = $request->get('name_'
                    . $locale);
                $product->translateOrNew($locale)->description = $request->get('description_' . $locale);
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extention = $image->getClientOriginalExtension();
                $file_name = str_random(15) .
                    "" . rand(1000000,
                        9999999) .
                    "" . time() .
                    "_" . rand(1000000, 9999999) .
                    "." . $extention;
                Image::make($image)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save("uploads/images/products/$file_name");
                $product->image = $file_name;
            }
            if ($request->hasFile('extra_images')) {
                $files = $request->file('extra_images');
                foreach ($files as $one) {
                    $image = str_random(15) .
                        "_" . rand(1000000, 9999999) .
                        "_" . time() .
                        "_" . rand(1000000, 9999999) .
                        ".jpg";
                    Image::make($one)->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save("uploads/images/products/$image");
                    $newAttach = new ProductImage();
                    $newAttach->product_id = $request->product_id;
                    $newAttach->product_img = $image;
                    $newAttach->save();
                }
            }

            if ($request->has('color_id')) {
                $color = $request->color_id;
                foreach ($color as $one) {
                    $newColor = ProductColor::updateOrCreate(
                        ['product_id' => $request->product_id, 'color_id' => $one]);
                }
            }
            if ($request->has('size_id')) {
                $size = $request->size_id;
                foreach ($size as $one1) {
                    $newSize = ProductSize::updateOrCreate(
                        ['product_id' => $request->product_id, 'size_id' => $one1]);
                }
            }

            $product->save();
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'product' => $product]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function addColorImage(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user) {
            $validator = Validator::make($request->all(), ['product_id' => 'required', 'color_id' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->messages()->all())]);
            }
            $storeId = Store::where('user_id', $user_id)->first();
            $product = Product::where('store_id', $storeId->id)->where('is_deleted', 0)->findOrFail($request->product_id);
            $ProductColor = ProductColor::where('color_id', $request->color_id)->where('product_id', $request->product_id)->first();
            if ($request->hasFile('color_image')) {
                $image = $request->file('color_image');
                $extention = $image->getClientOriginalExtension();
                $file_name = str_random(15) .
                    "" . rand(1000000,
                        9999999) .
                    "" . time() .
                    "_" . rand(1000000, 9999999) .
                    "." . $extention;
                Image::make($image)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save("uploads/images/products/$file_name");
                $ProductColor->color_image = $file_name;
            }
            $ProductColor->save();
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'product' => $product]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function addSlider(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out
successfully';
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        if ($user) {
            $validator = Validator::make($request->all(), ['type' => 'required', //1 = product, 2 = link'product_id' => 'required_if:type,1', 'link' => 'required_if:type,2',
                'image' => 'required|image|mimes:jpeg,jpg,png,gif',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->messages()->all())]);
            }
            $storeId = Store::where('user_id', $user_id)->first();
            $slider = new Slider();
            $slider->store_id = $storeId->id;
            $slider->type = $request->type;
            $slider->product_id = $request->product_id;
            $slider->link = $request->link;
            $locales = Language::all()->pluck('lang');
            foreach ($locales as $locale) {
                $slider->translateOrNew($locale)->title = $request->get('title_'
                    . $locale);
                $slider->translateOrNew($locale)->details = $request->get('details_' . $locale);
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extention = $image->getClientOriginalExtension();
                $file_name = str_random(15) .
                    "" . rand(1000000,
                        9999999) .
                    "" . time() .
                    "_" . rand(1000000, 9999999) .
                    "." . $extention;
                Image::make($image)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save("uploads/images/ads/$file_name");
                $slider->image = $file_name;
            }
            $slider->save();
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'slider' => $slider]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function editSlider(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out
successfully';
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        if ($user) {
            $validator = Validator::make($request->all(), ['slider_id' => 'required', 'status' => 'required', 'type' => 'required', //1 = product, 2 = link'product_id' => 'required_if:type,1',
                'link' => 'required_if:type,2',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->messages()->all())]);
            }
            $storeId = Store::where('user_id', $user_id)->first();
            $slider = Slider::where('store_id',
                $storeId->id)->findOrFail($request->slider_id);
            $locales = Language::all()->pluck('lang');

            foreach ($locales as $locale) {
                $slider->translateOrNew($locale)->title = $request->get('title_'
                    . $locale);
                $slider->translateOrNew($locale)->details = $request->get('details_' . $locale);
            }
            $slider->type = $request->type ?? $slider->type;
            $slider->product_id = $request->product_id ?? $slider->product_id;
            $slider->status = $request->status;
            $slider->link = $request->link ?? $slider->link;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extention = $image->getClientOriginalExtension();
                $file_name = str_random(15) .
                    "" . rand(1000000, 9999999) .
                    "" . time() .
                    "_" . rand(1000000, 9999999) .
                    "." . $extention;
                Image::make($image)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save("uploads/images/ads/$file_name");
                $slider->image = $file_name;
            }
            $slider->save();
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'slider' => $slider]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function addCategory(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        if ($user) {
            $validator = Validator::make($request->all(), ['name_ar' => 'required', 'name_en' => 'required', 'image' => 'required|image|mimes:jpeg, jpg, png, gif',]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->messages()->all())]);
            }
            $storeId = Store::where('user_id', $user_id)->first();
            $category = new Category();
            $category->store_id = $storeId->id;
            $locales = Language::all()->pluck('lang');
            foreach ($locales as $locale) {
                $category->translateOrNew($locale)->name = $request->get('name_'
                    . $locale);
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extention = $image->getClientOriginalExtension();
                $file_name = str_random(15) .
                    "" . rand(1000000, 9999999) .
                    "" . time() .
                    "_" . rand(1000000, 9999999) .
                    "." . $extention;
                Image::make($image)->resize(800,
                    null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save("uploads/images/category/$file_name");
                $category->image = $file_name;
            }
            $category->save();
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message,
                'category' => $category
            ]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function editCategory(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        if ($user) {
            $validator = Validator::make($request->all(), ['name_ar' => 'required', 'name_en' => 'required', 'category_id' => 'required', 'status' => 'required',]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->
                messages()->all())]);
            }
            $storeId = Store::where('user_id', $user_id)->first();
            $category = Category::where('store_id', $storeId->id)->where('is_deleted', 0)->findOrFail($request->category_id);
            $category->status = $request->status;
            $locales = Language::all()->pluck('lang');
            foreach ($locales as $locale) {
                $category->translateOrNew($locale)->name = $request->get('name_' . $locale);
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extention = $image->getClientOriginalExtension();
                $file_name = str_random(15) .
                    "" . rand(1000000,
                        9999999) .
                    "" . time() .
                    "_" . rand(1000000, 9999999) .
                    "." . $extention;
                Image::make($image)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save("uploads/images/category/$file_name");
                $category->image = $file_name;
            }
            $category->save();
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'category' => $category]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function addSubCategory(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged
out successfully';
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        if ($user) {
            $validator = Validator::make($request->all(), ['name_ar' => 'required', 'name_en' => 'required', 'category_id' => 'required', 'image' => 'required',]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->messages()->all())]);
            }
            $storeId = Store::where('user_id', $user_id)->first();
            $subCategory = new SubCategory();
            $subCategory->store_id = $storeId->id;
            $subCategory->category_id = $request->category_id;
            $locales = Language::all()->pluck('lang');
            foreach ($locales as $locale) {
                $subCategory->translateOrNew($locale)->name = $request->get('name_' . $locale);
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extention = $image->getClientOriginalExtension();
                $file_name = str_random(15) .
                    "" . rand(1000000, 9999999) .
                    "" . time() .
                    "_" . rand(1000000, 9999999) .
                    "." . $extention;
                Image::make($image)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save("uploads/images/category/$file_name");
                $subCategory->image = $file_name;
            }
            $subCategory->save();
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'subCategory' => $subCategory]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function editSubCategory(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id',
                $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        if ($user) {
            $validator = Validator::make($request->all(), ['name_ar' => 'required',
                'name_en' => 'required', 'sub_category_id' => 'required', 'status' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->messages()->all())]);
            }
            $storeId = Store::where('user_id', $user_id)->first();
            $subCategory = SubCategory::where('store_id', $storeId->id)->where('is_deleted', 0)->findOrFail($request->sub_category_id);
            $locales = Language::all()->pluck('lang');
            foreach ($locales as $locale) {
                $subCategory->translateOrNew($locale)->name = $request->get('name_' . $locale);
            }
            $subCategory->status = $request->status;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extention = $image->getClientOriginalExtension();
                $file_name = str_random(15) .
                    "" . rand(1000000, 9999999) .
                    "" . time() .
                    "_" . rand(1000000, 9999999)
                    .
                    "." . $extention;
                Image::make($image)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save("uploads/images/category/$file_name");
                $subCategory->image = $file_name;
            }
            $subCategory->save();
            $message = __('api.ok');
            return response()->json(['status' =>
                true, 'code' => 200, 'message' => $message, 'subCategory' => $subCategory
            ]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function addNews(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' => false, 'code' =>
                200, 'message' => $message
            ]);
        }
        if ($user) {
            $validator = Validator::make($request->all(), ['title' => 'required',]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->messages()->all())]);
            }
            $storeId = Store::where('user_id', $user_id)->first();
            $news = new News();
            $news->store_id = $storeId->id;
            $news->title = $request->title;
            $news->link = $request->link;
            $news->save();
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' =>
                200, 'message' => $message, 'news' => $news
            ]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function editNews(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        if ($user) {
            $validator = Validator::make($request->all(), ['news_id' => 'required', 'status' => 'required', 'title' => 'required',]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->
                messages()->all())]);
            }
            $storeId = Store::where('user_id', $user_id)->first();
            $news = News::where('store_id', $storeId->id)->where('is_deleted', 0)->findOrFail($request->news_id);
            $news->title = $request->title ?? $news->title;
            $news->link = $request->link ?? $news->link;
            $news->status = $request->status ?? $news->status;
            $news->save();
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'news' => $news]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' =>
                $message
            ]);
        }
    }
   
    public function deletStoreItem(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        if ($user) {
            $validator = Validator::make($request->all(), ['type' => 'required', 'id' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->
                messages()->all())]);
            }
            $storeId = Store::where('user_id', $user_id)->first();
            if($request->type == 1){ //News
            $item = News::where('store_id', $storeId->id)->where('is_deleted', 0)->findOrFail($request->id);
            }
            if($request->type == 2){ //Category
            $item = Category::where('store_id', $storeId->id)->where('is_deleted', 0)->findOrFail($request->id);
            }
            if($request->type == 3){ //SubCategory
            $item = SubCategory::where('store_id', $storeId->id)->where('is_deleted', 0)->findOrFail($request->id);
            }
            if($request->type == 4){ //Product
            $item = Product::where('store_id', $storeId->id)->where('is_deleted', 0)->findOrFail($request->id);
            }
            if($item){
            $item->is_deleted = 1;
            $item->save();  
            $message = __('api.delete');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'item' => $item]);    
            }

        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' =>
                $message
            ]);
        }
    }

//UserAPP
    public function getStores(Request $request)
    {
        $stores = Store::query();
        if ($request->city_id > 0) {
            $stores = $stores->where('status', 'active')
                ->where(function ($query) use ($request) {
                    $query->where("city_id", $request->city_id)
                        ->orWhere('all_cities', '1');
                });
        }
        if ($request->category_id > 0) {
            $stores = $stores->where('status', 'active')->where('store_category_id', $request->category_id);
        }
        $stores = $stores->where('status', 'active')->where('type', '1')->orderBy('likes_count', 'desc')->get();
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'stores' => $stores]);
    }

    public function getStoreByWifiId(Request $request)
    {
        $validator = Validator::make($request->all(), ['wifi_id' => 'required',]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $wifi = Wifi::where("id", $request->wifi_id)->first();
        $store = Store::where('id', $wifi->store_id)->first();
        $store['news'] = News::where('store_id', $wifi->store_id)->where('is_deleted', 0)->where('status', 'active')->get();
        $store['sliders'] = Slider::where('store_id', $wifi->store_id)->where('status', 'active')->get();
        $store['products'] = Product::where('store_id', $wifi->store_id)->where('is_deleted', 0)->where('status', 'active')->get();
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'store' => $store]);
    }

    public function getStoreById(Request $request)
    {
        $validator = Validator::make($request->all(), ['store_id' => 'required',]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $store = Store::where('id', $request->store_id)->first();
        $store['news'] = News::where('store_id', $request->store_id)->where('is_deleted', 0)->where('status', 'active')->get();
        $store['sliders'] = Slider::where('store_id', $request->store_id)->where('status', 'active')->get();
        $store['products'] = Product::where('store_id', $request->store_id)->where('is_deleted', 0)->where('status', 'active')->get();
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'store' => $store]);
    }

    public function getStoreByProductId(Request $request)
    {
        $validator = Validator::make($request->all(), ['product_id' => 'required',]);
        if ($validator->fails()) {
            $message = "الرجاء التاكد من ان المنتج اي دي";
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }

        $product = Product::where("id", $request->product_id)->where('is_deleted', 0)->first();

        if(isset($product->id))
        {
            $store = Store::where("id",$product->store_id)->first();

            if( isset($store->id) )
            {
                if ($store->status_cart == 1)
                {
                    $message = "لا يوجد خدمة توصيل لهذا المتجر";
                    return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                }
                else
                {
                    $message = "تم";
                    return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
                }
            }
            else
            {
                $message = "هذا المتجر غير موجود";
                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
            }
        }
        else
        {
            $message = "هذا المنتج غير موجود";
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function getCategoriesByStoreId(Request $request)
    {
        $validator = Validator::make($request->all(), ['store_id' => 'required',]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $categories = Category::where('store_id', $request->store_id)->where('is_deleted', 0)->where('status', 'active')->orderBy('id', 'desc')->get();
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'categories' => $categories]);
    }

    public function myCategory(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        if ($user) {
            $storeId = Store::where('user_id', $user_id)->first();
            $categories = Category::where('store_id', $storeId->id)->where('is_deleted', 0)->orderBy('id', 'desc')->get();
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'categories' =>
                $categories
            ]);
        }
    }

    public function getStoreSubCategories(Request $request)
    {
        $validator = Validator::make($request->all(), ['category_id' => 'required', 'store_id' => 'required',]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' =>
                200, 'validator' => implode("\n", $validator->messages()->all())
            ]);
        }
        if ($request->category_id > 0) {
            $subcategory = SubCategory::query()->where('category_id', $request->category_id)->where('status', 'active')->where('is_deleted', 0)->orderBy('id', 'desc')->get();
        } else {
            $subcategory = SubCategory::query()->where('store_id',
                $request->store_id)->where('status', 'active')->where('is_deleted', 0)->get();
        }
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'subcategory' => $subcategory]);
    }

    public function likeStore(Request $request)
    {
        $validator = Validator::make($request->all(), ['store_id' => 'required',]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        $store = Store::where('id', $request->store_id)->first();
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' => false,
                'code' => 200, 'message' => $message
            ]);
        }
        if ($store) {
            $cheke = Favorite::where('user_id', $user_id)->where('store_id', $store->id)->first();
            if ($cheke) {
                $cheke->delete();
                $store->likes_count = $store->likes_count - 1;
                $store->save();
                $message = __('api.unLike');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
            }
            $storeLikes = new Favorite();
            $storeLikes->user_id = $user_id;
            $storeLikes->store_id = $store->id;
            $store->likes_count = $store->likes_count + 1;
            $store->save();
            $storeLikes->save();
            $message = __('api.likeSucsses');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function likeStoreByWifiId(Request $request)
    {
        $validator = Validator::make($request->all(), ['wifi_id' => 'required',]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        $wifi = Wifi::where("id", $request->wifi_id)->first();
        $store = Store::where('id', $wifi->store_id)->first();
        if ($user->status == 'not_active') {
            Token::where('user_id', $user_id)->delete();
            auth('api')->user()->token()->revoke();
            $message = 'logged out successfully';
            return response()->json(['status' => false,
                'code' => 200, 'message' => $message
            ]);
        }
        if ($store) {
            $cheke = Favorite::where('user_id', $user_id)->where('store_id', $store->id)->first();
            if ($cheke) {
                $cheke->delete();
                $store->likes_count = $store->likes_count - 1;
                $store->save();
                $message = __('api.unLike');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
            }
            $storeLikes = new Favorite();
            $storeLikes->user_id = $user_id;
            $storeLikes->store_id = $store->id;
            $store->likes_count = $store->likes_count + 1;
            $store->save();
            $storeLikes->save();
            $message = __('api.likeSucsses');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
    }

    public function searchUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required',
            'type' => 'required', //0=user_code , 1=name
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        if ($request->type == 0) {
            $users = User::orderBy('id', 'desc')->where('status', 'active')->where('user_code', $request->text)->paginate(10);

        } else {
            $users = User::orderBy('id', 'desc')->where('status', 'active')->where('name', 'like', '%' . $request->text . '%')->paginate(10);
        }
        $message = __('api.ok');
        $newData = ['status' => true, 'code' => 200, 'message' => $message, 'users' => $users];
        return response()->json($newData);
    }

    public function sendBalance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'balance' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $user_id = auth('api')->id();
        $user = User::query()->where('status', 'active')->findOrFail($user_id);
        $store = Store::where('id', $user_id)->first();

        $user2 = User::query()->where('status', 'active')->findOrFail($request->user_id);
        $balanceIn = UserWallet::where('user_id', $user_id)->where('type', 0)->sum('total_price');
        $balanceOut = UserWallet::where('user_id', $user_id)->where('type', 1)->sum('total_price');
        $balance = $balanceIn - $balanceOut;


        if ($balance < $request->balance) {
            $message = __('api.noBalance');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }

        $wallet = new UserWallet();
        $wallet->user_id = $user_id;
        $wallet->order_id = 0;
        $wallet->title = 'ارسال رصيد';
        $wallet->details = 'اسم المستخدم#: ' . $user2->name;
        $wallet->total_price = $request->balance;
        $wallet->type = 1;  //0=in 1=out
        $wallet->save();
        //  return 1;
        $wallet2 = new UserWallet();
        $wallet2->user_id = $user2->id;
        $wallet2->order_id = 0;
        $wallet2->title = 'استلام رصيد';
        $wallet2->details = 'اسم المستخدم#: ' . $user->name;
        $wallet2->total_price = $request->balance;
        $wallet2->type = 0;  //0=in 1=out
        $wallet2->save();
        $message = "تم ايداع رصيد في محفظتك";
        $notification = new Notify();
        $notification->user_id = $request->user_id;
        $notification->messag_type = 0;//0=in 1=out
        $notification->message = $message;
        $notification->save();

        $tokens = Token::where('user_id', $request->user_id)->pluck('fcm_token')->toArray();
        // return $tokens_ios;
        sendNotificationToUsers($tokens, $message, 1, 0);
        $message = __('api.ok');
        $newData = ['status' => true, 'code' => 200, 'message' => $message];
        return response()->json($newData);

    }

    public function openStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $user_id = auth('api')->id();
        $user = User::query()->where('status', 'active')->findOrFail($user_id);
        $store = Store::where('user_id', $user_id)->first();
        $store->is_open = $request->status;
        $store->save();

        $message = __('api.ok');
        $newData = ['status' => true, 'code' => 200, 'message' => $message];
        return response()->json($newData);

    }

    public function getMyNetworks(Request $request, $wifi_id)
    {
        $user_id = auth('api')->id();
        $user = User::query()->where('status', 'active')->findOrFail($user_id);
        $store = Store::where('user_id', $user->id)->first();
        if ($store) {
            $wifi = Wifi::findOrFail($wifi_id);
            if ($wifi->store_id == $store->id) {
                $network = Networks::query()->where('status', 'active')->where('wifi_id', $wifi_id)->get();
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'network' => $network]);
            }
        }
        $message = __('api.not_found');
        return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
    }

    public function addNewNetwork(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'price' => 'required_if:type,1',
            'image' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $user_id = auth('api')->id();
        $user = User::query()->where('status', 'active')->findOrFail($user_id);
        $store = Store::where('user_id', $user->id)->first();
        if ($store) {
            $wifi = Wifi::where(['status' => 'active', 'store_id' => $store->id])->first();
            if ($wifi) {
                $network = new Networks();
                $network->name = $request->name;
                $network->wifi_id = $wifi->id;
                $network->price = $request->price ?? 0;
                $network->type = $request->type;
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $extention = $image->getClientOriginalExtension();
                    $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
                    Image::make($image)->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save("uploads/images/networks/$file_name");
                    $network->image = $file_name;
                }
                $network->save();
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'network' => $network]);
            }
        }
        $message = __('api.not_found');
        return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
    }

    public function editNetwork(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'network_id' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $user_id = auth('api')->id();
        $store = Store::where('user_id', $user_id)->first();
        if ($store) {
            $network = Networks::findOrFail($request->network_id);
            $wifi = Wifi::where(['status' => 'active', 'store_id' => $store->id])->first();
            if ($wifi && $network->wifi_id == $wifi->id) {
                $network->name = $request->name;
                $network->price = $request->price ?? 0;
                $network->type = $request->type;
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $extention = $image->getClientOriginalExtension();
                    $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
                    Image::make($image)->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save("uploads/images/networks/$file_name");
                    $network->image = $file_name;
                }
                $network->save();
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'network' => $network]);
            }
        }
        $message = __('api.not_found');
        return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
    }

    public function removeNetwork($network_id)
    {
        $network = Networks::findOrFail($network_id);
        $user_id = auth('api')->id();
        $store = Store::where('user_id', $user_id)->first();
        if ($store) {
            $wifi = Wifi::where(['status' => 'active', 'store_id' => $store->id])->first();
            if ($wifi && $network->wifi_id == $wifi->id) {
                $network->delete();
            }
        }
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
    }

    public function getMyNetworkCards(Request $request, $network_id)
    {
        $network = Networks::findOrFail($network_id);
        $user_id = auth('api')->id();
        $store = Store::where('user_id', $user_id)->first();
        if ($store) {
            $wifi = Wifi::where(['status' => 'active', 'store_id' => $store->id])->first();
            if ($wifi && $network->wifi_id == $wifi->id) {
                $networkCards = NetworksCards::query()->where('status', 'active')->where('network_id', $network_id)->get();
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'networkCards' => $networkCards]);
            }
        }
        $message = __('api.not_found');
        return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
    }

    public function addNewNetworkCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'card_id' => 'required',
            'pin' => 'required',
            'password' => 'required',
            'network_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $network = Networks::findOrFail($request->network_id);
        $user_id = auth('api')->id();
        $store = Store::where('user_id', $user_id)->first();
        if ($store) {
            $wifi = Wifi::where(['status' => 'active', 'store_id' => $store->id])->first();
            if ($wifi && $network->wifi_id == $wifi->id) {
                $newNetworkCard = new NetworksCards();
                $newNetworkCard->card_id = 0;
                $newNetworkCard->pin = $request->pin;
                $newNetworkCard->password = $request->password;
                $newNetworkCard->wifi_id = $network->wifi_id;
                $newNetworkCard->network_id = $network->id;
                $newNetworkCard->save();

                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'network' => $newNetworkCard]);
            }
        }
        $message = __('api.not_found');
        return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
    }
    public function deleteNetworkCard($id)
    {



        $card = NetworksCards::findOrFail($id);
        if ($card) {
           $card->delete();
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'network' => $newNetworkCard]);
            
        }
        $message = __('api.not_found');
        return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
    }

    public function getNetworksRequest(Request $request)
    {

        $user_id = auth('api')->id();
        $store = Store::where('user_id', $user_id)->first();

        if ($store) {
            $items = NetworksCardsRequest::where(['store_id' => $store->id])->paginate(10);
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'items' => $items]);
        }
        $message = __('api.not_found');
        return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
    }

    public function getRenewCardRequest(Request $request)
    {

        $user_id = auth('api')->id();
        $store = Store::where('user_id', $user_id)->first();
        if ($store) {
            $items = RequestRenewCard::where(['store_id' => $store->id])->with('network:id,name')->paginate(15);
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'items' => $items]);
        }
        $message = __('api.not_found');
        return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
    }

    public function updateCardRequest(Request $request, $id)
    {
        $order = RequestRenewCard::findOrFail($id);
        set_currency($order);
        $soso = $order->balance - $order->app_percent;
        if ($request->status == 0)
        {
            $message = __('api.OrderIsPreparing');
            $order->action = $request->status;
            $order->save();
        }
        elseif ($request->status == 1)
        {
            $message = __('api.OrderIsOnDelivery');
            $order->action = $request->status;
            $order->save();
            $Wellet_profit = Wellet_profit::where('id', $order->wellet_profit_id)->first();
            $Wellet_profit->status_wellet = 0;
            $Wellet_profit->created_at = Carbon::now()->format('YmdHis');
            $Wellet_profit->save();
        }
        elseif ($request->status == 2)
        {

            if(User_Wallet_Check_Balance(1,$order->app_percent) && User_Wallet_Check_Balance($order->Store->user_id,$soso))
            {
                $message = __('api.OrderIsCancel');
                $order->action = $request->status;
                $order->save();
                $wallet = new UserWallet();
                $wallet->user_id = $order->user_id;
                $wallet->order_id = $order->id;
                $wallet->title = 'اعادة رصيد';
                $wallet->details = ' رقم الطلب (wifi): ' . $order->id;
                $wallet->total_price = $order->balance;
                $wallet->type = 0;
                $wallet->save();

                if (isset($order->selected_user_id) && is_numeric($order->selected_user_id))
                {
                    if(User_Wallet_Check_Balance($order->selected_user_id,$order->selected_user_reNewNetwork_percent) && User_Wallet_Check_Balance($order->Store->user_id,$order->network_user_reNewNetwork_percent))
                    {
                        $wallet_n = new UserWallet();
                        $wallet_n->user_id = $order->selected_user_id;
                        $wallet_n->total_price = $order->selected_user_reNewNetwork_percent;
                        $wallet_n->title = 'الغاء طلب تجديد شبكة';
                        $wallet_n->details = ' واي فاي ' . $order->name . ' السعر: ' . $order->selected_user_reNewNetwork_percent;
                        $wallet_n->type = 1;
                        $wallet_n->save();

                        $wallet_n = new UserWallet();
                        $wallet_n->user_id = $order->Store->user_id;
                        $wallet_n->total_price = $order->network_user_reNewNetwork_percent;
                        $wallet_n->title = 'الغاء طلب تجديد شبكة';
                        $wallet_n->details = ' واي فاي ' . $order->name . ' السعر: ' . $order->network_user_reNewNetwork_percent;
                        $wallet_n->type = 1;
                        $wallet_n->save();
                    }
                    else
                    {
                        return response()->json(['status' => false, 'code' => 200, 'message' => 'احد المستفيدين من ارباح هذه الخدمة ليس لديه رصيد كافي في محفظته ']);
                    }
                }
                else
                {
                    $wallet_store = new UserWallet();
                    $wallet_store->user_id = $order->Store->user_id;
                    $wallet_store->order_id = $order->id;
                    $wallet_store->title = 'اعادة رصيد';
                    $wallet_store->details = ' رقم الطلب (wifi): ' . $order->id;
                    $wallet_store->total_price = $order->balance - $order->app_percent;
                    $wallet_store->type = 1;
                    $wallet_store->save();
                }
                $wallet_j = new UserWallet();
                $wallet_j->user_id = 1;
                $wallet_j->order_id = $order->id;
                $wallet_j->title = 'اعادة رصيد';
                $wallet_j->details = ' رقم الطلب (wifi): ' . $order->id;
                $wallet_j->total_price = $order->app_percent;
                $wallet_j->type = 1;
                $wallet_j->save();

                $Wellet_profit = Wellet_profit::where('id', $order->wellet_profit_id)->first();
                $Wellet_profit->delete();
            }
            else
            {
                return response()->json(['status' => false, 'code' => 200, 'message' => 'احد المستفيدين من ارباح هذه الخدمة ليس لديه رصيد كافي في محفظته ']);
            }
        }


        $tokens = Token::where('user_id', $order->user_id)->pluck('fcm_token')->toArray();
        sendNotificationToUsers($tokens, $message, "2", $id);
        $notifiy = new Notifiy();
        $notifiy->user_id = $order->user_id;
        $notifiy->order_id = $id;
        $notifiy->message = $message;
        $notifiy->save();
        set_currency("");
        return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
    }

    public function addNewNetworkFile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required|max:50000|mimes:xlsx,xls',
            'network_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }

        $user_id = auth('api')->id();
        $store = Store::where('user_id', $user_id)->first();
        if ($store) {

            $item = new ImportFile();
            $file = $request->file('file');
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . ".xlsx";
            // $name = time().$file->getClientOriginalName();

            $file->move(public_path() . '/uploads/', $file_name);
            $item->file_name = $file_name;
            $item->network_id = $request->network_id;
            $item->store_id = $store->id;
            $item->save();
            

                $files = ImportFile::where('id', $item->id)->update(['status'=>2]);
                $network= Networks::query()->findOrFail($item->network_id);
                // return $network;
                Excel::import(new CardImport($network), public_path().'/uploads/'.$item->file_name);
                // Excel::import(new UsersImport, public_path().'/uploads/excel/'.$file->file_name);

                $files = ImportFile::where('id', $item->id)->update(['status'=>3]);
            
            
            $message = 'تم رفع الملف و تخزين كافة البيانات';
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);


        }

    }


    public function sendNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $user_id = auth('api')->id();
        $store = Store::where('user_id', $user_id)->first();
        if ($store) {
            $users = EnableNotificationNetwork::where('store_id', $store->id)->pluck('user_id')->toArray();
            $tokens = Token::whereIn('user_id', $users)->pluck('fcm_token')->toArray();
            sendNotificationToUsers($tokens, $request->message, '0', '0');
            foreach ($users as $one) {
                $notification = new Notify();
                $notification->user_id = $one;
                $notification->messag_type = 1;
                $notification->message = 'رسالة من متجر' . $store->store_name . '  الرسالة:' . $request->message;
                $notification->save();
            }
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        }
        $message = __('api.not_found');
        return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
    }

    public function doTransfer(Request $request)
    {
        $setting = Setting::query()->findOrFail(1);

        if($setting->status_dollar_conversion == 1)
        {
            $validator = Validator::make($request->all(), [
                'amount' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
            }


            $Purchasing_price =  $setting->exchange_rate;
            $selling_price =  $setting->selling_price;

            $user_id = auth('api')->id();

            $balanceIn = UserWallet::where('user_id',$user_id)->where('type', 0)->sum('total_price');
            $balanceOut = UserWallet::where('user_id',$user_id)->where('type', 1)->sum('total_price');
            $balance = $balanceIn - $balanceOut;

            if ($balance < $request->amount)
            {
                $message = __('api.noBalance');
                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
            }
            else
            {
                if(get_user_carrency_from_api() == "turkey")
                {

                    $wallet = new UserWallet();
                    $wallet->user_id = $user_id;
                    $wallet->order_id = 0;
                    $wallet->title = 'ارسال رصيد';
                    $wallet->details = 'اسم المستخدم#: ' . $user_id;
                    $wallet->total_price =$request->amount;
                    $wallet->type = 1;  //0=in 1=out
                    $wallet->save();

                    $dollar_balance = $request->amount / $Purchasing_price ;

                    $wallet2 = new UserWallet("dollar");
                    $wallet2->user_id = $user_id;
                    $wallet2->order_id = 0;
                    $wallet2->title = 'استلام رصيد';
                    $wallet2->details = 'اسم المستخدم#: ' . $user_id;
                    $wallet2->total_price = $dollar_balance;
                    $wallet2->type = 0;  //0=in 1=out
                    $wallet2->save();

                    $message = "تم ايداع رصيد في محفظتك";
                    $notification = new Notify();
                    $notification->user_id =$user_id;
                    $notification->messag_type = 0;//0=in 1=out
                    $notification->message = $message;
                    $notification->save();

                    $tokens = Token::where('user_id', $user_id)->pluck('fcm_token')->toArray();
                    // return $tokens_ios;
                    sendNotificationToUsers($tokens, $message, 1, 0);
                    $message = __('api.ok');
                    $newData = ['status' => true, 'code' => 200, 'message' => $message];
                    return response()->json($newData);

                    $message = __('api.ok');
                    return response()->json(['status' => true, 'code' => 200, 'message' => $message, ]);
                }
                elseif(get_user_carrency_from_api() == "dollar")
                {

                    $wallet = new UserWallet();
                    $wallet->user_id = $user_id;
                    $wallet->order_id = 0;
                    $wallet->title = 'ارسال رصيد';
                    $wallet->details = 'اسم المستخدم#: ' . $user_id;
                    $wallet->total_price =$request->amount;
                    $wallet->type = 1;  //0=in 1=out
                    $wallet->save();

                    $dollar_balance = $request->amount * $selling_price ;

                    $wallet2 = new UserWallet("turkey");
                    $wallet2->user_id = $user_id;
                    $wallet2->order_id = 0;
                    $wallet2->title = 'استلام رصيد';
                    $wallet2->details = 'اسم المستخدم#: ' .$user_id;
                    $wallet2->total_price = $dollar_balance;
                    $wallet2->type = 0;  //0=in 1=out
                    $wallet2->save();

                    $message = "تم ايداع رصيد في محفظتك";
                    $notification = new Notify();
                    $notification->user_id =$user_id;
                    $notification->messag_type = 0;//0=in 1=out
                    $notification->message = $message;
                    $notification->save();

                    $tokens = Token::where('user_id',$user_id)->pluck('fcm_token')->toArray();
                    // return $tokens_ios;
                    sendNotificationToUsers($tokens, $message, 1, 0);
                    $message = __('api.ok');
                    $newData = ['status' => true, 'code' => 200, 'message' => $message];
                    return response()->json($newData);

                    $message = __('api.ok');
                    return response()->json(['status' => true, 'code' => 200, 'message' => $message,]);
                }
                else
                {
                    $message = __('api.not_found');
                    return response()->json(['status' => true, 'code' => 200, 'message' => $message,]);
                }
            }
        }
        else
        {
            return response()->json(['status' => false, 'code' => 200, 'message' => "هذه الخدمة متوقفة حاليا", ]);
        }


    }
}