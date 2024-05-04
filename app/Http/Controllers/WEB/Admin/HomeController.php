<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\Admin;
use App\Models\AshabGame;
use App\Models\GameServies;
use App\Models\NagmaAshab;
use App\Models\NetworkSections;
use App\Models\ProductServiceRequest;
use App\Models\Recharge_info;
use App\Models\wallet_profits_dollar;
use App\Models\Wifi;
use App\Models\User;
use App\Models\Permission;
use App\Models\Setting;

use App\Models\PromotionCode;
use App\Models\Order;
use App\Models\Product;
use App\Models\Chat;
use App\Models\RequestMobileBalance;
use App\Models\RequestRenewCard;
use App\Models\NetworksCards;
use App\Models\Networks;
use App\Models\Service;
use App\Models\Store;
use App\Models\ServiceCards;
use App\Models\ProductService;
use App\Models\GameRequest;
use App\Models\PublicServiceRequest;
use App\Models\CourseRequest;
use DB;
use App\Models\Contact;
use App\Models\Booking;
use App\Models\UserWallet;
use App\Models\MobileNetworkPackages;
use App\Models\MobileCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

class HomeController extends Controller
{




    public function index()
    {
        $admin=Admin::findOrFail(auth()->guard('admin')->user()->id);

        $count_product = Product::count();
        $count_orders = Order::where('status',-1)->count();
        $count_users = User::where('type',1)->count();
        $count_owners=User::where('type',2)->count();
        $newbalanceCards = UserWallet::where('order_id',0)->where('type',0)->orderBy('id', 'DESC')->take(50)->get();
        $count_chat = Chat::where('read',0)->where('sender','0')->count();
        $request_mobil_count = RequestMobileBalance::where('action',0)->count();
        $renew_card_count = RequestRenewCard::where('action',0)->count();
        $gameRequest = GameRequest::where('status',-1)->count();
        $publicServicesRequest = PublicServiceRequest::where('status',-1)->count();
        $courseRequest = CourseRequest::where('status',-1)->count();

        $networks_count = Networks::query()->withCount('networksCards')->get();
        $service_count = ProductService::query()->where('status','active')->withCount('serviceCards')->get();

        return view('admin.home.dashboard',[
            'count_product'=>$count_product,
            'count_orders'=>$count_orders,
            'count_users'=>$count_users,
            'count_owners'=>$count_owners,
            'admin'=>$admin,
            'newbalanceCards'=>$newbalanceCards,
            'count_chat'=>$count_chat,
            'request_mobil_count'=>$request_mobil_count,
            'renew_card_count'=>$renew_card_count,
            'networks_count'=>$networks_count,
            'service_count'=>$service_count,
            'gameRequest'=>$gameRequest,
            'courseRequest'=>$courseRequest,
            'publicServicesRequest'=>$publicServicesRequest,

        ]);
    }


    public function changeStatus($model,Request $request)
    {
        $role = "";
        if($model == "admins") $role = 'App\Models\Admin';
        if($model == "users") $role = 'App\Models\User';
        if($model == "promotions") $role = 'App\Models\PromotionCode';
        if($model == "owners") $role = 'App\Models\User';
        if($model == "categories") $role = 'App\Models\Category';
        if($model == "products") $role = 'App\Models\Product';
        if($model == "storeProducts") $role = 'App\Models\Product';
        if($model == "productServices") $role = 'App\Models\ProductService';
        if($model == "ads") $role = 'App\Models\Ad';
        if($model == "cities") $role = 'App\Models\City';
        if($model == "wifi") $role = 'App\Models\Wifi';
        if($model == "networks") $role = 'App\Models\Networks';
        if($model == "pages") $role = 'App\Models\Page';
        if($model == "questions") $role = 'App\Models\Question';
        if($model == "notifications") $role = 'App\Models\Notification';
        if($model == "subcategories") $role = 'App\Models\SubCategory';
        if($model == "azkar") $role = 'App\Models\Azkar';
        if($model == "azkarDetails") $role = 'App\Models\AzkarDetails';
        if($model == "news") $role = 'App\Models\News';
        if($model == "storeNews") $role = 'App\Models\News';
        if($model == "storeSliders") $role = 'App\Models\Slider';
        if($model == "sliders") $role = 'App\Models\Slider';
        if($model == "cards") $role = 'App\Models\NetworksCards';
        if($model == "productServicesCards") $role = 'App\Models\ServiceCards';
        if($model == "stores") $role = 'App\Models\Store';
        if($model == "games") $role = 'App\Models\Game';
        if($model == "gameServies") $role = 'App\Models\GameServies';
        if($model == "institutes") $role = 'App\Models\Institute';
        if($model == "instituteCourses") $role = 'App\Models\InstituteCourses';
        if($model == "waterTanks") $role = 'App\Models\WaterTank';
        if($model == "balanceCards") $role = 'App\Models\BalanceCard';
        if($model == "publicServices") $role = 'App\Models\PublicService';
        if($model == "distribution_points") $role = 'App\Models\DistributionPoint';
        if($model == "allStoreCategories") $role = 'App\Models\StoreCategory';
        if($model == "networks") $role = 'App\Models\Networks';
        if($model == "colors") $role = 'App\Models\Color';
        if($model == "sizes") $role = 'App\Models\Size';
        if($model == "person_mac") $role = 'App\Models\Person_mac';
        if($model == "mac") $role = 'App\Models\Mac';
        if($model == "NetworkSections") $role = 'App\Models\NetworkSections';
        if($model == "requestRenewCard") $role = 'App\Models\RequestRenewCard';
        if($model == "Whatsapp") $role = 'App\Models\WhatsappUsers';
        if($model == "ashab") $role = 'App\Models\AshabGames';
        if($model == "MobileCompany") $role = 'App\Models\MobileCompany';
        if($model == "MobileNetworkPackages") $role = 'App\Models\MobileNetworkPackages';
        if($model == "Expenses") $role = 'App\Models\Wellet_profit';
        if($model == "profit_tr") $role = 'App\Models\Wellet_profit';
        if($model == "Recharge") $role = 'App\Models\Recharge_info';
        if($model == "countries") $role = 'App\Models\Country';
        if($model == "nagma_ashab") $role = 'App\Models\NagmaAshab';
        if($model == "nagma_game") $role = 'App\Models\NagmaGame';
        if($model == "promo_codes") $role = 'App\Models\PromoCode';
        if($role !=""){
            if ($request->action == 'delete') {
                if($model == "games55")
                {
                    foreach ($request->IDsArray as $one_game_id)
                    {
                        $gs = GameServies::where("game_id",$one_game_id)->first();

                        if(isset($gs->id))
                        {

                        }
                        else
                        {
                            $role::query()->where('id',$one_game_id)->delete();
                        }

                    }
                }
                else
                    if($model == "gameServiesdd")
                    {

                    }
                    else
                        if($model == "stores")
                        {
                            foreach ($request->IDsArray as $one_card_id)
                            {
                                $gc = Wifi::where("store_id",$one_card_id)->first();

                                if(isset($gc->id))
                                {

                                }
                                else
                                {
                                    $role::query()->where('id',$one_card_id)->delete();
                                }

                            }
                        }
                        else
                            if($model == "networks")
                            {
                                foreach ($request->IDsArray as $one_renew_cart_id)
                                {
                                    $gr = RequestRenewCard::where("network_id",$one_renew_cart_id)->first();

                                    if(isset($gr->id))
                                    {

                                    }
                                    else
                                    {
                                        $role::query()->where('id', $one_renew_cart_id)->delete();
                                    }

                                }
                            }
                            else
                                if($model == "requestRenewCard")
                                {
                                    foreach ($request->IDsArray as $one_renew_cart_id)
                                    {
                                        $gr = RequestRenewCard::where("id",$one_renew_cart_id)->where('action',0)->first();

                                        if(isset($gr->id))
                                        {

                                        }
                                        else
                                        {
                                            $role::query()->where('id', $one_renew_cart_id)->delete();
                                        }

                                    }
                                }
                                else
                                    if($model == "productServices"){

                                        foreach ($request->IDsArray as $one_productServices_id)
                                        {
                                            $gr = ProductServiceRequest::where("product_service_id",$one_productServices_id)->where("status","0")->get();

                                            if(count($gr) > 0)
                                            {

                                            }
                                            else
                                            {
                                                $role::query()->where('id', $one_productServices_id)->delete();
                                            }

                                        }
                                    } else
                                        if($model == "ashab"){

                                            foreach ($request->IDsArray as $one_AshabGame_id)
                                            {
                                                    $role::query()->where('id', $one_AshabGame_id)->delete();

                                            }
                                        }
                                    else
                                    {
                                        $role::query()->whereIn('id', $request->IDsArray)->delete();
                                    }

            }
            else {
                if($request->action) {
                    $role::query()->whereIn('id', $request->IDsArray)->update(['status' => $request->action]);
                }
            }
            return $request->action;
        }
        return false;


    }


}
