<?php
namespace App\Http\Controllers\API;
use App\Models\NagmaGame;
use App\Models\AshabGames;
use App\Models\AshabGamesCards;
use App\Models\AshabLog;
use App\Models\AshabRequest;
use App\Models\Azkar;
use App\Models\AzkarDetails;
use App\Models\Ad;
use App\Models\Country;
use App\Models\MobileNetworkPackages;
use App\Models\NagmaAshab;
use App\Models\Notify;
use App\Models\Recharge_info;
use App\Models\RequestMobileBalance;
use App\Models\Token;
use App\Models\Wellet_profit;
use App\Models\Wifi;
use App\Models\Slider;
use App\Models\Category;
use App\Models\StoreCategory;
use App\Models\NetworkRequest;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Question;
use App\Models\City;
use App\Models\Size;
use App\Models\Color;
use App\Models\Cart;
use App\Models\Api;
use App\Models\Service;
use App\Models\DistributionPoint;
use App\Models\PublicService;
use App\Models\WaterTank;
use App\Models\Game;
use App\Models\GameServies;
use App\Models\PublicServiceRequest;
use App\Models\GameRequest;
use App\Models\Courses;
use App\Models\Institute;
use App\Models\InstituteCourses;
use App\Models\CourseRequest;
use App\Models\AdditionService;
use App\Models\Page;
use App\Models\Contact;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\EnableNotificationNetwork;
use App\Models\News;
use App\Models\MobileCompany;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Language;
use Illuminate\Support\Str;


class AppController extends Controller
{


    public function distributionPoints()
    {
        $data = DistributionPoint::query()->where('status', 'active')->get();
        if (count($data) > 0) {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'DistributionPoint' => $data]);

        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'DistributionPoint' => $data]);

        }
    }
    public function getWaterTanks()
    {
        $data = WaterTank::query()->where('status', 'active')->orderBy('price','asc')->get();
        if (count($data) > 0) {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'waterTanks' => $data]);

        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'waterTanks' => $data]);

        }
    }
    public function getPublicServices(Request $request)
    {
        $data = PublicService::where('city_id',$request->city_id)->where('parent_id',0)->where('status', 'active')->orderByDesc('id')->get();
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'publicServices' => $data]);

    }
    public function getSubPublicServices($id)
    {
        $data = PublicService::where('parent_id',$id)->where('status', 'active')->orderByDesc('id')->get();
        if (count($data) > 0) {
            if(get_user_carrency_from_api() == "turkey")
            {
                foreach ($data as $lolo)
                {
                    if(isset($lolo->is_dollar) && $lolo->is_dollar == 1)
                    {
                        $lolo->price =  "".$lolo->result($lolo->price);
                    }
                }
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'subPublicServices' => $data]);

            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
                foreach ($data as $lolo)
                {
                    if(isset($lolo->is_dollar) && $lolo->is_dollar == 1)
                    {
                        $lolo->price = $lolo->price;
                    }
                    else
                    {
                        $lolo->price =  "".$lolo->result1($lolo->price);
                    }
                }
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'subPublicServices' => $data]);

            }
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'subPublicServices' => $data]);
           }
    }

    public function subPublicServiceRequest(Request $request)
    {
                $user_id = auth('api')->id();
         $user = User::query()->findOrFail($user_id);
        if($user->status != 'active'){
             $message = 'عذرا .. حسابك غير فعال';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]); 
        }

        $msg = check_version_in_post($request);
        if($msg != "")
        {
            return response()->json(['status' => false, 'code' => 200, 'message' => $msg]);
        }

        $validator = Validator::make($request->all(), [
            'sub_public_service_id' => 'required',
            //    'mobile' => 'required|digits_between:8,12',
            'name' => 'required',
            'address' => 'required',
            'address' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'city_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $publicService = PublicService::findOrFail($request->sub_public_service_id);
        $balanceIn=UserWallet::where('user_id',$user_id)->where('type',0)->sum('total_price');
        $balanceOut=UserWallet::where('user_id',$user_id)->where('type',1)->sum('total_price');
        $balance= $balanceIn - $balanceOut;

        if ($publicService > 0)
        {
            if(get_user_carrency_from_api() == "turkey")
            {
                    if(isset($publicService->is_dollar) && $publicService->is_dollar == 1)
                    {
                        $publicService->price =  "".$publicService->result($publicService->price);
                        $publicService->purchasing_price =  "".$publicService->result($publicService->purchasing_price);
                    }
            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
                 if(isset($publicService->is_dollar) && $publicService->is_dollar == 1)
                    {
                        $publicService->price = $publicService->price;
                        $publicService->purchasing_price = $publicService->purchasing_price;
                    }
                    else
                    {
                        $publicService->price =  "".$publicService->result1($publicService->price);
                        $publicService->purchasing_price =  "".$publicService->result1($publicService->purchasing_price);
                    }

            }
        }

        if($publicService->price > $balance )
        {
            $message = __('api.noBalance');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        $lolo= $publicService->price;
        $koko= $publicService->purchasing_price;
        $soso = $lolo-$koko;

        $Wellet_profit=new Wellet_profit();
        $Wellet_profit->user_id=$user_id;
        $Wellet_profit->profit =$soso;
        $Wellet_profit->purchasing_price= $koko ;
        $Wellet_profit->details=' اسم الخدمة ' .$publicService->name . ' السعر ' . $publicService->price;
        $Wellet_profit->type =0;
        $Wellet_profit->city_id =$request->get('city_id');
        $Wellet_profit->service_name =0;
        $Wellet_profit->status_wellet =1;
        $Wellet_profit->save();
        $Wellet_profit->id;
        $wallet=new UserWallet();
        $wallet->user_id=$user_id;
        $wallet->total_price =$publicService->price;
        $wallet->title= $publicService->name ;
        $wallet->details=' اسم الخدمة ' .$publicService->name . ' السعر ' . $publicService->price;
        $wallet->type =1;
        $wallet->save();
        $wallet_J=new UserWallet();
        $wallet_J->user_id=1;
        $wallet_J->total_price =$publicService->price;
        $wallet_J->title= $publicService->name ;
        $wallet_J->details=' اسم الخدمة ' .$publicService->name . ' السعر ' . $publicService->price;
        $wallet_J->type =0;
        $wallet_J->save();

        $networkRequest = new  PublicServiceRequest();
        $networkRequest->sub_public_service_id = $request->get('sub_public_service_id');
        $networkRequest->price = $publicService->price;
        $networkRequest->name = $request->get('name');
        $networkRequest->mobile = $request->get('mobile');
        $networkRequest->address = $request->get('address');
        $networkRequest->req_date = $request->get('req_date');
        $networkRequest->req_time = $request->get('req_time');
        $networkRequest->latitude = $request->get('latitude');
        $networkRequest->longitude = $request->get('longitude');
        $networkRequest->user_id =$user_id;
        $networkRequest->wellet_profit_id =$Wellet_profit->id;
        $networkRequest->city_id = $request->get('city_id') ?? '1';
        $networkRequest->currency = get_user_carrency_from_api();
        $networkRequest->save();
        $message = __('api.ok');


        return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
    }

    public function getGames(Request $request)
    {
        $data = Game::query();
        //if ($request->city_id > 0) {
        $data = $data->where('city_id', $request->city_id)->orWhere("city_id","0");
        //}

        $data = $data->where('status', 'active')->get();
        if (count($data) > 0) {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'games' => $data]);

        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'games' => $data]);

        }
    }

    public function getGamesServies(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'game_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }

        $data = GameServies::query()->where(['game_id'=>$request->game_id,'status'=>'active'])->orderBy('price','asc')->get();

        if (count($data) > 0) {
            if(get_user_carrency_from_api() == "turkey")
            {
                foreach ($data as $lolo)
                {
                    if(isset($lolo->is_dollar) && $lolo->is_dollar == 1)
                    {
                        $lolo->price =  $lolo->result($lolo->new_price);
                        $lolo->nagma =  $lolo->result($lolo->nagma_price);
                    }
                }
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'gamesServies' => $data]);

            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
                foreach ($data as $lolo)
                {
                    if(isset($lolo->is_dollar) && $lolo->is_dollar == 1)
                    {
                        $lolo->price =  $lolo->new_price;
                        $lolo->nagma =  $lolo->nagma_price;
                    }
                    else
                    {
                        $lolo->price =  $lolo->result1($lolo->new_price);
                        $lolo->nagma =  $lolo->result1($lolo->nagma_price);
                    }
                }
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'gamesServies' => $data]);

            }
        }
        else
        {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'gamesServies' => $data]);

        }
    }

    public function gameRequestOld(Request $request)
    {
        
        $is_maintenance = Setting::orderByDesc('id')->first()->is_maintenance;

        if($is_maintenance == 1){
                            $message = 'عذرا .. التطبيق في وضع الصيانة';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]);
        }
        
        $discount = 0;
                $user_id = auth('api')->id();
         $user = User::query()->findOrFail($user_id);
        if($user->status != 'active'){
             $message = 'عذرا .. حسابك غير فعال';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]); 
        }
        // $msg = check_version_in_post($request);
        // if($msg != "")
        // {
        //     return response()->json(['status' => false, 'code' => 200, 'message' => $msg]);
        // }

        $validator = Validator::make($request->all(), [
            'game_id' => 'required',
            'servies_id' => 'required',
         //   'user_game_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $quentity = $request->quantity ?? 1;       /// change 1 to  quentity from request 
         $game = GameServies::where(['status'=>'active'])->findOrFail($request->servies_id);

        $checkNagmaGames = NagmaGame::where('user_id',$user_id)->where('nagma_game_ids', 'LIKE', '%,'.$request->servies_id.',%')->first()->average;

              $jawharProfit =  ($game->purchasing_price * $game->commission)/100;
       $NagmaGames =     $jawharProfit * $checkNagmaGames /100 ;

        // $main_game = Game::where('id' , $request->game_id)->first();
   if($checkNagmaGames){
       $discount = $NagmaGames;
   }
         $quantity = $request->quantity ?? 1;

        
        $exchange_rate = Setting::orderByDesc('id')->first()->exchange_rate;
        $balanceIn=UserWallet::where('user_id',$user_id)->where('type',0)->sum('total_price');
        $balanceOut=UserWallet::where('user_id',$user_id)->where('type',1)->sum('total_price');
        $balance= $balanceIn - $balanceOut;
       
        // if(($game->new_price * $quantity > $balance ){
        //     $message = __('api.noBalance');
        //     return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        // }
        
            $newPrice = $game->new_price-$discount;
            $purchasingPrice = $game->purchasing_price;

                if(isset($game->is_dollar) && $game->is_dollar == 1)
                {
                     $newPrice =  $game->result($game->new_price - $discount);
                    $purchasingPrice = $game->result($game->purchasing_price);
                }
                
                       if(get_user_carrency_from_api() == "turkey")
            {
                if(isset($game->is_dollar) && $game->is_dollar == 1)
                {
                    $showPrice =  $game->result($game->new_price - $discount);
                }
            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
                if(isset($game->is_dollar) && $game->is_dollar == 1)
                {
                    $showPrice = $game->new_price -$discount;
                }
                else
                {
                    $showPrice = $game->result1($game->new_price - $discount);
                }

            }     
                

        if($game->api_id > 0){
            if(($showPrice *$quantity ) > $balance ){

                $message = __('api.noBalance') . ' رصيد الحالي ' .$balance .' المطلوب للخدمة ' .$showPrice  *$quantity;
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]);
            }
     
            $gameRequest = new  GameRequest();
                $gameRequest->game_id = $request->game_id;
                $gameRequest->city_id = $request->get('city_id');
                $gameRequest->servies_id = $request->get('servies_id');
                $gameRequest->quantity = $quantity ;
                $gameRequest->price = $newPrice * $quantity;
                $gameRequest->mobile = $request->get('mobile')??"";
                $gameRequest->user_id =$user_id;
                $gameRequest->wellet_profit_id =  $Wellet_profit->id;
                $gameRequest->user_game_id =$request->get('user_game_id')??"";
                $gameRequest->currency = get_user_carrency_from_api();
               
            
            $api = Api::where('id' , $game->api_id)->first();
            if($api->id == 1){
                $base_url = substr($api->url, 0, strrpos( $api->url, '/'));
                $end_point = $base_url.'/newOrder'.'/'.$game->target_id.'/params?qty='.$quentity.'&playerID='.$request->user_game_id;
                $response = callAPI($end_point , $api->token,[],'GET','1');
                if($response->status == 'ERROR'){
                    $message = __('api.whoops');
                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
 
                }else{
                    $gameRequest->api_id = $api->id;
                    $gameRequest->api_order_id = json_decode(@$response->message)->data->order_id;
                    $gameRequest->api_total_price = json_decode(@$response->message)->data->price;
                    $gameRequest->api_order_status =json_decode(@$response->message)->data->status;
                    $gameRequest->save();
                    $this->store_wallet_data($request , $game , $user_id,$quentity);
                    $message = __('api.ok');
                }
               
               
            }else if($api->id == 2){
        
                $base_url = substr($api->url, 0, strrpos( $api->url, '/'));
                $end_point = $base_url.'/createOrder';
                // $post_fields = [];
                $post_fields[] = [
                        'items' => [(object) [
                            'denomination_id'=> $game->target_id,    
                            'qty'=> 1,
                        ]
                    ]    
                        ,'args' => (object) [
                        'playerid'=> $request->user_game_id,    
                    ],
                    'orderToken'=>$this->gen_uuid(),
                    // 'orderToken'=> '2f93df31-e5d2-449e-ac84-a24f94e08778',    
                ];
                $response = callAPI($end_point , $api->token,[],'POST','2');
                if($response->status == 'ERROR'){
                    $message = __('api.whoops');
                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                }else{
                    $gameRequest->api_id = $api->id;
                    $gameRequest->api_order_id = json_decode(@$response->message)->data->order_id;
                    $gameRequest->api_total_price = json_decode(@$response->message)->data->price;
                    $gameRequest->api_order_status =json_decode(@$response->message)->data->status;
                    $gameRequest->save();
                    $this->store_wallet_data($request , $game , $user_id,$quentity);
                    $message = __('api.ok');
                }
               
            }else if($api->id == 3){
                $base_url = substr($api->url, 0, strrpos( $api->url, '/'));
                $end_point = $base_url.'/newOrder'.'/'.$game->target_id.'/params?qty='.$quentity.'&playerID='.$request->user_game_id;
                $response = callAPI($end_point , $api->token,[],'GET','3');
                if($response->status == 'ERROR'){
                    $message = __('api.whoops');
                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                }else{
                    $gameRequest->api_id = $api->id;
                    $gameRequest->api_order_id = json_decode(@$response->message)->data->order_id;
                    $gameRequest->api_total_price = json_decode(@$response->message)->data->price;
                    $gameRequest->api_order_status =json_decode(@$response->message)->data->status;
                    $gameRequest->save();
                    $this->store_wallet_data($request , $game , $user_id,$quentity);
                    $message = __('api.ok');
                }
            }else if($api->id == 4){
                
                ///// complete numbersapp;
            }else if($api->id == 5){
            // return '123';
                $base_url = $api->url;
                $end_point = $base_url.'/RequestOrder?API='.$api->token.'&productId='.$game->target_id.'&amount='.$quentity.'&playernumber='.$request->get('user_game_id').'&playername=jawharstores';
                $response = callAPI($end_point , $api->token,[],'GET','5');
                
                if($response->status == 'ERROR'){
                    $message = __('api.whoops');
                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                }else{
                    $gameRequest->api_id = $api->id;
                    $gameRequest->api_order_id = json_decode(@$response->message)->orderId;
                    $gameRequest->api_total_price = json_decode(@$response->message)->value1;
                    $gameRequest->api_order_status =json_decode(@$response->message)->status;
                    $gameRequest->save();
                    $this->store_wallet_data($request , $game , $user_id,$quentity);
                    $message = __('api.ok');
                }
               
            }
            else if($api->id == 8){
                $base_url = substr($api->url, 0, strrpos( $api->url, '/'));
                $end_point = $base_url.'/newOrder'.'/'.$game->target_id.'/params?qty='.$quantity.'&playerID='.$request->user_game_id.'&order_uuid='.Str::uuid();
                $response = callAPI($end_point , $api->token,[],'GET','3');
                if($response->status == 'ERROR'){
                    $message = __('api.whoops');
                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                }else{
                    $gameRequest->api_id = $api->id;
                    $gameRequest->api_order_id = json_decode(@$response->message)->data->order_id;
                    $gameRequest->api_total_price = json_decode(@$response->message)->data->price;
                    $gameRequest->api_order_status =json_decode(@$response->message)->data->status;
                    $gameRequest->save();
                    $this->store_wallet_data($request , $game , $user_id,$quentity);
                    $message = __('api.ok');
                }
            }
            
        }else{
            if(get_user_carrency_from_api() == "turkey")
            {
                if(isset($game->is_dollar) && $game->is_dollar == 1)
                {
                    $game->price =  $game->result($game->price);
                    $game->purchasing_price = $game->result($game->purchasing_price);
                }
            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
                if(isset($game->is_dollar) && $game->is_dollar == 1)
                {
                    $game->price = $game->price;
                    $game->purchasing_price = $game->purchasing_price;
                }
                else
                {
                    $game->price =  "".$game->result1($game->price);
                    $game->purchasing_price =  "".$game->result1($game->purchasing_price);
                }

            }


        if($game->price > $balance ){
            $message = __('api.noBalance');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }


        $lolo= $game->price * $quantity;
        $PriceDiscount = $lolo * $discount/100;
        $koko= $game->purchasing_price *$quantity;
        $soso = $lolo-$koko;
        $priceAfterDiscount = $lolo - $PriceDiscount;

        $Wellet_profit=new Wellet_profit();
        $Wellet_profit->user_id=$user_id;
        $Wellet_profit->profit =$soso;
        $Wellet_profit->purchasing_price= $koko ;
        $Wellet_profit->details=' اسم الخدمة ' .$game->size . ' السعر ' . $priceAfterDiscount;
        $Wellet_profit->type =0;
        $Wellet_profit->city_id =$request->get('city_id');
        $Wellet_profit->service_name =1;
        $Wellet_profit->status_wellet =1;
        $Wellet_profit->save();
        $Wellet_profit->id;

        $wallet=new UserWallet();
        $wallet->user_id=$user_id;
        $wallet->total_price =$priceAfterDiscount;
        $wallet->title= 'الألعاب Games' ;
        $wallet->details=' اسم الخدمة ' .$game->size . ' السعر ' . $priceAfterDiscount;
        $wallet->type =1;
        $wallet->save();
        $wallet_J=new UserWallet();
        $wallet_J->user_id=1;
        $wallet_J->total_price =$priceAfterDiscount;
        $wallet_J->title= 'الألعاب Games' ;
        $wallet_J->details=' كود المستخدم ' .$user_id .' اسم الخدمة ' .$game->size . ' السعر ' . $priceAfterDiscount;
        $wallet_J->type =0;
        $wallet_J->save();

        $gameRequest = new  GameRequest();
        $gameRequest->game_id = $request->get('game_id');
        $gameRequest->city_id = $request->get('city_id');
        $gameRequest->servies_id = $request->get('servies_id');
        $gameRequest->price = $game->price;
        $gameRequest->mobile = $request->get('mobile')??"";
        $gameRequest->user_id =$user_id;
        $gameRequest->wellet_profit_id =  $Wellet_profit->id;
        $gameRequest->user_game_id =$request->get('user_game_id')??"";
        $gameRequest->currency = get_user_carrency_from_api();
        $gameRequest->save();
        $message = __('api.ok');
        }
        
            $notify = new Notify();
            $notify->user_id = $user_id;
            $notify->order_id = 0;
            $notify->messag_type = 0;
            $notify->message = 'تم طلب الخدمة  للمتابعة رقم الطلب ' . $gameRequest->id ;
            $notify->save();
        
        
        return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
    }
    

    public function gameRequest(Request $request)
    {    
        $is_maintenance = Setting::orderByDesc('id')->first()->is_maintenance;


        
        $discount = 0;
        $user_id = auth('api')->id();
         $user = User::query()->findOrFail($user_id);
        if($user->status != 'active'){
             $message = 'عذرا .. حسابك غير فعال';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]); 
        }
        // $msg = check_version_in_post($request);
        // if($msg != "")
        // {
        //     return response()->json(['status' => false, 'code' => 200, 'message' => $msg]);
        // }
        
                if($is_maintenance == 1){
                            $message = 'عذرا .. التطبيق في وضع الصيانة';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]);
        }

        $validator = Validator::make($request->all(), [
            'game_id' => 'required',
            'servies_id' => 'required',
            'city_id' => 'required',
         //   'user_game_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        
        $quentity = $request->quantity ?? 1;       /// change 1 to  quentity from request 
        $game = GameServies::where(['status'=>'active'])->findOrFail($request->servies_id);
        
        if(@$game->game->is_quantity == 1 && @$game->game->min_quantity > $quentity){
            $message = @$game->game->min_quantity." أقل كمية مسموحة ";
            return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]);
        }
        
        $checkNagmaGames = NagmaGame::where('user_id',$user_id)->where('nagma_game_ids', 'LIKE', '%,'.$request->servies_id.',%')->first()->average;

        $jawharProfit =  ($game->purchasing_price * $game->commission)/100;
        $NagmaGames =     $jawharProfit * $checkNagmaGames /100 ;

        // $main_game = Game::where('id' , $request->game_id)->first();
   if($checkNagmaGames){
       $discount = $NagmaGames;
   }
         $quantity = $request->quantity ?? 1;

        
        $exchange_rate = Setting::orderByDesc('id')->first()->exchange_rate;
        $balanceIn=UserWallet::where('user_id',$user_id)->where('type',0)->sum('total_price');
        $balanceOut=UserWallet::where('user_id',$user_id)->where('type',1)->sum('total_price');
        $balance= $balanceIn - $balanceOut;
     
        
            $newPrice = $game->new_price-$discount;
            $purchasingPrice = $game->purchasing_price;

                if(isset($game->is_dollar) && $game->is_dollar == 1)
                {
                     $newPrice =  $game->result($game->new_price - $discount);
                    $purchasingPrice = $game->result($game->purchasing_price);
                }
                
                       if(get_user_carrency_from_api() == "turkey")
            {
                if(isset($game->is_dollar) && $game->is_dollar == 1)
                {
                    $showPrice =  $game->result($game->new_price - $discount);
                }
            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
                if(isset($game->is_dollar) && $game->is_dollar == 1)
                {
                    $showPrice = $game->new_price -$discount;
                }
                else
                {
                    $showPrice = $game->result1($game->new_price - $discount);
                }

            }     
                

        if($game->api_id > 0){
            if(($showPrice *$quantity ) > $balance ){

                $message = __('api.noBalance') . ' رصيد الحالي ' .$balance .' المطلوب للخدمة ' .$showPrice  *$quantity;
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]);
            }
     
    
            $gameRequest = new  GameRequest();
                $gameRequest->game_id = $request->game_id;
                $gameRequest->city_id = $request->get('city_id');
                $gameRequest->servies_id = $request->get('servies_id');
                $gameRequest->quantity = $quantity ;
                $gameRequest->price = $newPrice * $quantity;
                $gameRequest->mobile = $request->get('mobile')??"";
                $gameRequest->user_id =$user_id;
                $gameRequest->wellet_profit_id =  $Wellet_profit->id;
                $gameRequest->user_game_id =$request->get('user_game_id')??"";
                $gameRequest->currency = get_user_carrency_from_api();
               
            
            $api = Api::where('id' , $game->api_id)->first();
            if($api->id == 1){
                $base_url = substr($api->url, 0, strrpos( $api->url, '/'));
                $end_point = $base_url.'/newOrder'.'/'.$game->target_id.'/params?qty='.$quentity.'&playerID='.$request->user_game_id;
                $response = callAPI($end_point , $api->token,[],'GET','1');
                if($response->status == 'ERROR'){
                    $message = __('api.whoops');
                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
 
                }else{
                    $gameRequest->api_id = $api->id;
                    $gameRequest->api_order_id = json_decode(@$response->message)->data->order_id;
                    $gameRequest->api_total_price = json_decode(@$response->message)->data->price;
                    $gameRequest->api_order_status =json_decode(@$response->message)->data->status;
                    $gameRequest->save();
                    $this->store_wallet_data($request , $game , $user_id,$quentity);
                    $message = __('api.ok');
                }
               
               
            }else if($api->id == 2){
        
                $base_url = substr($api->url, 0, strrpos( $api->url, '/'));
                $end_point = $base_url.'/createOrder';
                // $post_fields = [];
                $post_fields[] = [
                        'items' => [(object) [
                            'denomination_id'=> $game->target_id,    
                            'qty'=> 1,
                        ]
                    ]    
                        ,'args' => (object) [
                        'playerid'=> $request->user_game_id,    
                    ],
                    'orderToken'=>$this->gen_uuid(),
                    // 'orderToken'=> '2f93df31-e5d2-449e-ac84-a24f94e08778',    
                ];
                $response = callAPI($end_point , $api->token,[],'POST','2');
                
                if($response->status == 'ERROR'){
                    $message = __('api.whoops');
                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                }else{
                    $gameRequest->api_id = $api->id;
                    $gameRequest->api_order_id = json_decode(@$response->message)->data->order_id;
                    $gameRequest->api_total_price = json_decode(@$response->message)->data->price;
                    $gameRequest->api_order_status =json_decode(@$response->message)->data->status;
                    $gameRequest->save();
                    $this->store_wallet_data($request , $game , $user_id,$quentity);
                    $message = __('api.ok');
                }
               
            }else if($api->id == 3){
                $base_url = substr($api->url, 0, strrpos( $api->url, '/'));
                $end_point = $base_url.'/newOrder'.'/'.$game->target_id.'/params?qty='.$quentity.'&playerID='.$request->user_game_id;
                $response = callAPI($end_point , $api->token,[],'GET','3');
                if($response->status == 'ERROR'){
                    $message = __('api.whoops');
                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                }else{
                    $gameRequest->api_id = $api->id;
                    $gameRequest->api_order_id = json_decode(@$response->message)->data->order_id;
                    $gameRequest->api_total_price = json_decode(@$response->message)->data->price;
                    $gameRequest->api_order_status =json_decode(@$response->message)->data->status;
                    $gameRequest->save();
                    $this->store_wallet_data($request , $game , $user_id,$quentity);
                    $message = __('api.ok');
                }
            }else if($api->id == 4){
                
                ///// complete numbersapp;
            }else if($api->id == 5){
            // return '123';
                $base_url = $api->url;
                $end_point = $base_url.'/RequestOrder?API='.$api->token.'&productId='.$game->target_id.'&amount='.$quentity.'&playernumber='.$request->get('user_game_id').'&playername=jawharstores';
                $response = callAPI($end_point , $api->token,[],'GET','5');
                
                if($response->status == 'ERROR'){
                    $message = __('api.whoops');
                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                }else{
                    $gameRequest->api_id = $api->id;
                    $gameRequest->api_order_id = json_decode(@$response->message)->orderId;
                    $gameRequest->api_total_price = json_decode(@$response->message)->value1;
                    $gameRequest->api_order_status =json_decode(@$response->message)->status;
                    $gameRequest->save();
                    $this->store_wallet_data($request , $game , $user_id,$quentity);
                    $message = __('api.ok');
                }
               
            }
            else if($api->id == 8){
                $base_url = substr($api->url, 0, strrpos( $api->url, '/'));
                $end_point = $base_url.'/newOrder'.'/'.$game->target_id.'/params?qty='.$quantity.'&playerID='.$request->user_game_id.'&order_uuid='.Str::uuid();
                $response = callAPI($end_point , $api->token,[],'GET','3');
        
                if(json_decode(@$response->message)->status != 'OK'){
                    $message = "الخدمة غير متوفرة حاليا ، راجع إدارة التطبيق";
                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
                }else{
                    $gameRequest->api_id = $api->id;
                    $gameRequest->api_order_id = json_decode(@$response->message)->data->order_id;
                    $gameRequest->api_total_price = json_decode(@$response->message)->data->price;
                    $gameRequest->api_order_status =json_decode(@$response->message)->data->status;
                    $gameRequest->save();
                    $this->store_wallet_data($request , $game , $user_id,$quentity);
                    $message = __('api.ok');
                }
            }
            
        }else{
            if(get_user_carrency_from_api() == "turkey")
            {
                if(isset($game->is_dollar) && $game->is_dollar == 1)
                {
                    $game->price =  $game->result($game->price);
                    $game->purchasing_price = $game->result($game->purchasing_price);
                }
            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
                if(isset($game->is_dollar) && $game->is_dollar == 1)
                {
                    $game->price = $game->price;
                    $game->purchasing_price = $game->purchasing_price;
                }
                else
                {
                    $game->price =  "".$game->result1($game->price);
                    $game->purchasing_price =  "".$game->result1($game->purchasing_price);
                }

            }


        if($game->price > $balance ){
            $message = __('api.noBalance');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }


        $lolo= $game->price * $quantity;
        $PriceDiscount = $lolo * $discount/100;
        $koko= $game->purchasing_price *$quantity;
        $soso = $lolo-$koko;
        $priceAfterDiscount = $lolo - $PriceDiscount;

        $Wellet_profit=new Wellet_profit();
        $Wellet_profit->user_id=$user_id;
        $Wellet_profit->profit =$soso;
        $Wellet_profit->purchasing_price= $koko ;
        $Wellet_profit->details=' اسم الخدمة ' .$game->size . ' السعر ' . $priceAfterDiscount;
        $Wellet_profit->type =0;
        $Wellet_profit->city_id =$request->get('city_id');
        $Wellet_profit->service_name =1;
        $Wellet_profit->status_wellet =1;
        $Wellet_profit->save();
        $Wellet_profit->id;

        $wallet=new UserWallet();
        $wallet->user_id=$user_id;
        $wallet->total_price =$priceAfterDiscount;
        $wallet->title= 'الألعاب Games' ;
        $wallet->details=' اسم الخدمة ' .$game->size . ' السعر ' . $priceAfterDiscount;
        $wallet->type =1;
        $wallet->save();
        $wallet_J=new UserWallet();
        $wallet_J->user_id=1;
        $wallet_J->total_price =$priceAfterDiscount;
        $wallet_J->title= 'الألعاب Games' ;
        $wallet_J->details=' كود المستخدم ' .$user_id .' اسم الخدمة ' .$game->size . ' السعر ' . $priceAfterDiscount;
        $wallet_J->type =0;
        $wallet_J->save();

        $gameRequest = new  GameRequest();
        $gameRequest->game_id = $request->get('game_id');
        $gameRequest->city_id = $request->get('city_id');
        $gameRequest->servies_id = $request->get('servies_id');
        $gameRequest->price = $game->price;
        $gameRequest->mobile = $request->get('mobile')??"";
        $gameRequest->user_id =$user_id;
        $gameRequest->wellet_profit_id =  $Wellet_profit->id;
        $gameRequest->user_game_id =$request->get('user_game_id')??"";
        $gameRequest->currency = get_user_carrency_from_api();
        $gameRequest->save();
        $message = __('api.ok');
        }
        
            $notify = new Notify();
            $notify->user_id = $user_id;
            $notify->order_id = 0;
            $notify->messag_type = 0;
            $notify->message = 'تم طلب الخدمة  للمتابعة رقم الطلب ' . $gameRequest->id ;
            $notify->save();
        
        
        return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
    }
    
    public function store_wallet_data_old($request , $game , $user_id,$quentity){
                    $newPrice = $game->new_price;
            $purchasingPrice = $game->purchasing_price;
        
                if(isset($game->is_dollar) && $game->is_dollar == 1)
                {
                     $newPrice =  $game->result($game->new_price);
                    $purchasingPrice = $game->result($game->purchasing_price);
                }
       
                $lolo= $newPrice * $quentity;
                $koko= $purchasingPrice * $quentity;
                $soso = $lolo -$koko ;
        
                $Wellet_profit=new Wellet_profit();
                $Wellet_profit->user_id=$user_id;
                $Wellet_profit->profit =$soso;
                $Wellet_profit->purchasing_price= $koko ;
                $Wellet_profit->details=' اسم الخدمة ' .$game->name . ' السعر ' . $lolo;
                $Wellet_profit->type =0;
                $Wellet_profit->city_id =$request->get('city_id');
                $Wellet_profit->service_name =1;
                $Wellet_profit->status_wellet =1;
                $Wellet_profit->save();
                $Wellet_profit->id;
        
                $wallet=new UserWallet();
                $wallet->user_id=$user_id;
                $wallet->total_price =$lolo;
                $wallet->title= 'الألعاب Games' ;
                $wallet->details=' اسم الخدمة ' .$game->size . ' السعر ' . $lolo;
                $wallet->type =1;
                $wallet->save();
                $wallet_J=new UserWallet();
                $wallet_J->user_id=1;
                $wallet_J->total_price =$lolo;
                $wallet_J->title= 'الألعاب Games' ;
                $wallet_J->details=' كود المستخدم ' .$user_id .' اسم الخدمة ' .$game->name . ' السعر ' . $lolo;
                $wallet_J->type =0;
                $wallet_J->save();
        
    }
  
    public function store_wallet_data($request , $game , $user_id,$quentity){
            
         
          if(get_user_carrency_from_api() == "turkey")
            {
              
                if(isset($game->is_dollar) && $game->is_dollar == 1)
                {
                    $game->price =  $game->result($game->new_price);
                    $game->nagma =  $game->result($game->nagma_price);
                    $purchasingPrice = $game->result($game->purchasing_price);

                }
                
            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
               
                if(isset($game->is_dollar) && $game->is_dollar == 1)
                {
                    $game->price =  $game->new_price;
                    $game->nagma =  $game->nagma_price;
                    $purchasingPrice = $game->purchasing_price;
                }
                else
                {
                    $game->price =  $game->result1($game->new_price);
                    $game->nagma =  $game->result1($game->nagma_price);
                    $purchasingPrice = $game->result1($game->purchasing_price);
                }
            
            }
          
            if($game->nagma > 0){
                $newPrice = $game->nagma;
            }else{
                $newPrice = $game->price;
            }
            
           
                $total= $newPrice * $quentity;
                $purchasing_total_price = $purchasingPrice * $quentity;
                $net = $total -$purchasing_total_price ;
        
                $Wellet_profit=new Wellet_profit();
                $Wellet_profit->user_id=$user_id;
                $Wellet_profit->profit =$net;
                $Wellet_profit->purchasing_price= $purchasing_total_price ;
                $Wellet_profit->details=' اسم الخدمة ' .$game->name . ' السعر ' . $total;
                $Wellet_profit->type =0;
                $Wellet_profit->city_id =$request->get('city_id');
                $Wellet_profit->service_name =1;
                $Wellet_profit->status_wellet =1;
                $Wellet_profit->save();
                $Wellet_profit->id;
        
                $wallet=new UserWallet();
                $wallet->user_id=$user_id;
                $wallet->total_price =$total;
                $wallet->title= 'الألعاب Games' ;
                $wallet->details=' اسم الخدمة ' .$game->size . ' السعر ' . $total;
                $wallet->type =1;
                $wallet->save();
                $wallet_J=new UserWallet();
                $wallet_J->user_id=1;
                $wallet_J->total_price =$total;
                $wallet_J->title= 'الألعاب Games' ;
                $wallet_J->details=' كود المستخدم ' .$user_id .' اسم الخدمة ' .$game->name . ' السعر ' . $total;
                $wallet_J->type =0;
                $wallet_J->save();
        
    }
    
    
//tInstitute
    public function getInstitute(Request $request)
    {
        $data = Institute::query();
        if ($request->city_id > 0) {
            $data = $data->where('city_id', $request->city_id);
        }
        $data = $data->where('status', 'active')->get();

        if (count($data) > 0) {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'institute' => $data]);

        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'institute' => $data]);

        }
    }
    public function getInstituteCourses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institute_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }

        $data = InstituteCourses::query()->where(['institute_id'=>$request->institute_id,'status'=>'active'])->with('institute')->orderBy('price','asc')->get();
        if (count($data) > 0) {
            if(get_user_carrency_from_api() == "turkey")
            {
                foreach ($data as $lolo)
                {
                    if(isset($lolo->is_dollar) && $lolo->is_dollar == 1)
                    {
                        $lolo->price =  "".$lolo->result($lolo->price);
                    }
                }
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'instituteCourses' => $data]);

            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
                foreach ($data as $lolo)
                {
                    if(isset($lolo->is_dollar) && $lolo->is_dollar == 1)
                    {
                        $lolo->price =  "".$lolo->price;
                    }
                    else
                    {
                        $lolo->price =  "".$lolo->result1($lolo->price);
                    }
                }
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'instituteCourses' => $data]);

            }
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'instituteCourses' => $data]);

        }
    }

    public function instituteRequest(Request $request)
    {
                $user_id = auth('api')->id();
         $user = User::query()->findOrFail($user_id);
        if($user->status != 'active'){
             $message = 'عذرا .. حسابك غير فعال';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]); 
        }
        $msg = check_version_in_post($request);
        if($msg != "")
        {
            return response()->json(['status' => false, 'code' => 200, 'message' => $msg]);
        }

        $validator = Validator::make($request->all(), [
            'institute_id' => 'required',
            'course_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $course= InstituteCourses::findOrFail($request->course_id);
        $balanceIn=UserWallet::where('user_id',$user_id)->where('type',0)->sum('total_price');
        $balanceOut=UserWallet::where('user_id',$user_id)->where('type',1)->sum('total_price');
        $balance= $balanceIn - $balanceOut;


            if(get_user_carrency_from_api() == "turkey")
            {

                    if(isset($course->is_dollar) && $course->is_dollar == 1)
                    {
                        $course->price =  "".$course->result($course->price);
                    }

            }
            elseif(get_user_carrency_from_api() == "dollar")
            {

                    if(isset($course->is_dollar) && $course->is_dollar == 1)
                    {
                        $course->price =  "".$course->price;
                    }
                    else
                    {
                        $course->price =  "".$course->result1($course->price);
                    }

            }


        if($course->price > $balance ){
            $message = __('api.noBalance');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }

        $user_id_instit = $course->institute->user_id;
        $fofo = $course->institute->app_percent;
        $lolo= $course->price;
        $koko= $fofo*$lolo/100;

        $wallet=new UserWallet();
        $wallet->user_id=$user_id;
        $wallet->total_price = $lolo;
        $wallet->title= 'المعاهد' ;
        $wallet->details=' اسم الخدمة ' .$course->name . ' السعر ' . $lolo;
        $wallet->type =1;
        $wallet->save();

        $Wellet_profit=new Wellet_profit();
        $Wellet_profit->user_id=$user_id;
        $Wellet_profit->profit =$koko;
        $Wellet_profit->details=' اسم الخدمة ' .$course->name . ' النسبة ' . $fofo;
        $Wellet_profit->type =0;
        $Wellet_profit->city_id =$request->get('city_id');
        $Wellet_profit->service_name =2;
        $Wellet_profit->status_wellet =1;
        $Wellet_profit->save();
        $Wellet_profit->id;

        $wallet_instit = new UserWallet();
        $wallet_instit->user_id = $user_id_instit;
        $wallet_instit->total_price = $lolo -$koko ;
        $wallet_instit->title = 'اشتراك معهد ';
        $wallet_instit->details = 'اسم الخدمة'.$course->name.' السعر '. $lolo;
        $wallet_instit->type = 0;
        $wallet_instit->save();

        $wallet_J=new UserWallet();
        $wallet_J->user_id=1;
        $wallet_J->total_price =$koko;
        $wallet_J->title= 'المعاهد' ;
        $wallet_J->details=' اسم الخدمة ' .$course->name . ' نسبة الربح  ' . $fofo;
        $wallet_J->type =0;
        $wallet_J->save();

        $courseRequest = new  CourseRequest();
        $courseRequest->institute_id = $request->get('institute_id');
        $courseRequest->course_id = $request->get('course_id');
        $courseRequest->city_id = $request->get('city_id');
        $courseRequest->price = $course->price;
        $courseRequest->mobile = $request->get('mobile')??"";
        $courseRequest->address = $request->get('address')??"";
        $courseRequest->note = $request->get('note')??"";
        $courseRequest->name = $request->get('name')??"";
        $courseRequest->user_id =$user_id;
        $courseRequest->wellet_profit_id = $Wellet_profit->id;
        $courseRequest->currency = get_user_carrency_from_api();
        $courseRequest->save();
        $message = __('api.ok');

        return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
    }

    public function enableNotificationForInstitute(Request $request)
    {
                $user_id = auth('api')->id();
         $user = User::query()->findOrFail($user_id);
        if($user->status != 'active'){
             $message = 'عذرا .. حسابك غير فعال';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]); 
        }
        $validator = Validator::make($request->all(), [
            'institute_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $chick = EnableNotificationNetwork::where('user_id',$user_id)->where('institute_id',$request->institute_id)->first();
        if($chick){
            $chick->delete();
            $message = 'تم الغاء الاشتراك';
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        }
        else {
            $networkRequest = new  EnableNotificationNetwork();
            $networkRequest->institute_id = $request->get('institute_id');
            $networkRequest->user_id = $user_id;
            $networkRequest->save();
            $message = __('api.ok');

            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        }
    }
    public function enableNotificationForStore(Request $request)
    {
                $user_id = auth('api')->id();
         $user = User::query()->findOrFail($user_id);
        if($user->status != 'active'){
             $message = 'عذرا .. حسابك غير فعال';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]); 
        }
        $validator = Validator::make($request->all(), [
            'store_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $chick = EnableNotificationNetwork::where('user_id',$user_id)->where('store_id',$request->store_id)->first();
        if($chick){
            $koko = EnableNotificationNetwork::where('user_id',$user_id)->where('store_id',$request->store_id)->get();

            foreach ($koko as $lolo)
            {
                $lolo->delete();
            }

            $message = 'تم الغاء الاشتراك';
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        }
        else {
            $networkRequest = new  EnableNotificationNetwork();
            $networkRequest->store_id = $request->get('store_id');
            $networkRequest->user_id = $user_id;
            $networkRequest->save();
            $message = __('api.ok');

            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        }
    }
    public function getAllMobileCompany()
    {
        $data = MobileCompany::query()->where('Status', 'active')->orderBy('id','asc')->get();
        if (count($data) > 0) {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'mc' => $data]);

        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'mc' => $data]);

        }
    }

    public function getMobileNetworkPackagesByCompanyID($networkId)
    {

        $data = MobileNetworkPackages::query()->where(['mobile_companies_id'=>$networkId])->orderBy('id','asc')->get();
        if (count($data) > 0) {
            if(get_user_carrency_from_api() == "turkey")
            {
                foreach ($data as $lolo)
                {
                    if(isset($lolo->is_dollar) && $lolo->is_dollar == 1)
                    {
                        $lolo->price = $lolo->result($lolo->price);
                    }
                }
                $message = __('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'mnp' => $data]);

            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
                foreach ($data as $lolo)
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
                return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'mnp' => $data]);

            }
        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'mnp' => $data]);

        }
    }

    public function requestMobileNumber(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_id' => 'required',
            'mobile' => 'required',
            'network_id' => 'required',
            'network_packages_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'message' => implode("\n", $validator->messages()->all())]);
        }

        $package = MobileNetworkPackages::query()->where(['id'=>$request->network_packages_id])->first();
                $user_id = auth('api')->id();
         $user = User::query()->findOrFail($user_id);
        if($user->status != 'active'){
             $message = 'عذرا .. حسابك غير فعال';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]); 
        }

        $msg = check_version_in_post($request);
        if($msg != "")
        {
            return response()->json(['status' => false, 'code' => 200, 'message' => $msg]);
        }

        $balanceIn=UserWallet::where('user_id',$user_id)->where('type',0)->sum('total_price');
        $balanceOut=UserWallet::where('user_id',$user_id)->where('type',1)->sum('total_price');
        $balance= $balanceIn - $balanceOut;

            if(get_user_carrency_from_api() == "turkey")
            {
                if(isset($package->is_dollar) && $package->is_dollar == 1)
                {
                    $package->price =  $package->result($package->price);
                    $package->purchasing_price = $package->result($package->purchasing_price);
                }
            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
                if(isset($package->is_dollar) && $package->is_dollar == 1)
                {
                    $package->price = $package->price;
                    $package->purchasing_price = $package->purchasing_price;
                }
                else
                {
                    $package->price =  $package->result1($package->price);
                    $package->purchasing_price =  $package->result1($package->purchasing_price);
                }
            }

        if($package->price > $balance )
        {
            $message = __('api.noBalance');
            return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }
        else
        {
            $koko = $package->price - $package->purchasing_price;

            $wallet=new UserWallet();
            $wallet->user_id=$user_id;
            $wallet->total_price = $package->price;
            $wallet->title= 'طلب رصيد' ;
            $wallet->details=' طلب رصيد ' .$package->name . ' السعر ' . $package->price;
            $wallet->type =1;
            $wallet->save();

            $Wellet_profit= new Wellet_profit();
            $Wellet_profit->user_id=$user_id;
            $Wellet_profit->profit =$koko;
            $Wellet_profit->purchasing_price=$package->purchasing_price;
            $Wellet_profit->details=' طلب رصيد ' .$package->name . ' السعر ' . $package->price;
            $Wellet_profit->type =0;
            $Wellet_profit->city_id =$request->city_id;
            $Wellet_profit->service_name =3;
            $Wellet_profit->status_wellet =1;
            $Wellet_profit->save();
            $Wellet_profit->id;

            $wallet_J=new UserWallet();
            $wallet_J->user_id=1;
            $wallet_J->total_price = $package->price;
            $wallet_J->title= 'طلب رصيد' ;
            $wallet_J->details=' طلب رصيد ' .$package->name . ' السعر ' . $package->price;
            $wallet_J->type =0;
            $wallet_J->save();

            $request_mobile_balance = new RequestMobileBalance();
            $request_mobile_balance->user_id = $user_id;
            $request_mobile_balance->city_id = $request->city_id;
            $request_mobile_balance->mobile = $request->mobile;
            $request_mobile_balance->balance = $package->price;
            $request_mobile_balance->network_id = $request->network_id;
            $request_mobile_balance->action = 0;
            $request_mobile_balance->status = "active";
            $request_mobile_balance->wellet_profit_id = $Wellet_profit->id;
            $request_mobile_balance->network_packages_id = $request->network_packages_id;
            $request_mobile_balance->currency = get_user_carrency_from_api();
            $request_mobile_balance->save();

            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);

        }
    }

    public function getAds()
    {

        $data = Ad::query()->where('status', 'active')->orderBy('ordering','asc')->take(30)->get();
        if (count($data) > 0) {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'ads' => $data]);

        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'ads' => $data]);

        }
    }
    public function getSliders()
    {
                $user_id = auth('api')->id();
         $user = User::query()->findOrFail($user_id);
        if($user->status != 'active'){
             $message = 'عذرا .. حسابك غير فعال';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]); 
        }
        //    return $user_id;
        if($user_id){
            $user = User::query()->findOrFail($user_id);
            $balanceIn=UserWallet::where('user_id',$user_id)->where('type',0)->sum('total_price');
            $balanceOut=UserWallet::where('user_id',$user_id)->where('type',1)->sum('total_price');

        }

        else{
            $balanceIn= 0;
            $balanceOut= 0;
        }


        $data = Slider::query()->where('status', 'active')->where('store_id', 0)->orderBy('ordering','asc')->take(30)->get();
        $data['balance']= $balanceIn - $balanceOut;

        if (count($data) > 0) {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'sliders' => $data]);

        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'sliders' => $data]);

        }
    }
    public function getAzkar()
    {
        $data = Azkar::query()->where('status', 'active')->orderBy('ordering','asc')->take(30)->get();
        if (count($data) > 0) {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'azkar' => $data]);

        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'azkar' => $data]);

        }
    }
    public function getAzkarDetails($id)
    {
        $data = AzkarDetails::query()->where('azkar_id',$id)->where('status', 'active')->orderBy('id','desc')->get();
        if (count($data) > 0) {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'azkarDetails' => $data]);

        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'azkarDetails' => $data]);

        }
    }
    public function getNews()
    {
        $data = News::query()->where('status', 'active')->where('store_id', 0)->orderBy('id','desc')->take(10)->get();
        if (count($data) > 0) {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'news' => $data]);

        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'news' => $data]);

        }
    }
    public function pages($id)
    {

        $page = Page::where('id',$id)->first();

        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' =>$message,'page'=>$page]);

    }
    public function getSetting()
    {

        $settings = Setting::query()->first();
        $settings['cities'] = City::where('status', 'active')->get();
        $settings['countries'] = Country::query()->get();
        $settings['service'] = Service::where('status', 'active')->get();
        $settings['mobileCompany'] = MobileCompany::where('status', 'active')->get();
        $settings['storeCategories'] = StoreCategory::all();
        $settings['colors'] = Color::where('status', 'active')->get();
        $settings['sizes'] = Size::where('status', 'active')->get();


        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' =>$message,'settings'=>$settings]);

    }
    public function getAshabGames()
    {
        $service = AshabGames::query()->get();
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'ashab_games' => $service]);
    }
    public function checkAshabBalance(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'price' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 200, 'message' => implode("\n", $validator->messages()->all())]);
            }

                    $user_id = auth('api')->id();
         $user = User::query()->findOrFail($user_id);
        if($user->status != 'active'){
             $message = 'عذرا .. حسابك غير فعال';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]); 
        }
            $msg = check_version_in_post($request);
            if($msg != "")
            {
                return response()->json(['status' => false, 'code' => 200, 'message' => $msg]);
            }

            $as_req = AshabRequest::where("user_id",$user_id)->latest()->first();

            $minute =  2;

            if(isset($as_req->id) && $as_req->created_at != null && $as_req->created_at->diffInMinutes(Carbon::now()) < $minute )
            {
                return response()->json(['status' => false, 'code' => 200, 'message' => "  الرجاء الانتظار  ".($minute - ($as_req->created_at->diffInMinutes(Carbon::now())))." دقيقة "]);
            }

            $as_req2 = AshabLog::where("user_id",$user_id)->latest()->first();

            if(isset($as_req2->id) && $as_req2->created_at != null && $as_req2->created_at->diffInMinutes(Carbon::now()) < $minute )
            {
                return response()->json(['status' => false, 'code' => 200, 'message' => "  الرجاء الانتظار  ".($minute - ($as_req2->created_at->diffInMinutes(Carbon::now())))." دقيقة "]);
            }

            $user = User::query()->findOrFail($user_id);
            $balanceIn = UserWallet::where('user_id', $user_id)->where('type', 0)->sum('total_price');
            $balanceOut = UserWallet::where('user_id', $user_id)->where('type', 1)->sum('total_price');
            $balance = $balanceIn - $balanceOut;
            if ($request->price <= $balance) {

                $ashab_log = new AshabLog();
                $ashab_log->user_id = $user_id;
                $ashab_log->page_id = $request->page_id;
                $ashab_log->order_id = " عند الموافقة"." currency:(".get_user_carrency_from_api().")";
                $ashab_log->game_id = $request->game_id;
                $ashab_log->denomination_id = $request->denomination_id;
                $ashab_log->price = $request->price;
                $ashab_log->qty = $request->qty;
                $ashab_log->playerid = $request->player_id;
                $ashab_log->city_id = $request->city_id;
                $ashab_log->save();
                $ashab_log->id;

                //customer
                $wallet_c = new UserWallet();
                $wallet_c->user_id = $user_id;
                $wallet_c->total_price = $request->price;
                $wallet_c->title = 'منتج من خدمات جوهر';
                $wallet_c->details =  ' السعر ' .$request->price;
                $wallet_c->type = 1;
                $wallet_c->save();

                //jawhar
                $wallet_j = new UserWallet();
                $wallet_j->user_id = 1;
                $wallet_j->total_price = $request->price;
                $wallet_j->title = 'منتج من خدمات جوهر';
                $wallet_j->details =   ' السعر ' .$request->price." log:(".$ashab_log->id.")";
                $wallet_j->type = 0;
                $wallet_j->save();

                $message = 'good balance';
                return response()->json(['status' => true, 'code' => 200, 'message' => $message]);

            }else{
                $message = __('api.noBalance');
                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
            }
        }catch (\Exception $e)
        {
            return response()->json(['status' => false, 'code' => 200, 'message' => $e->getMessage()]);
        }
    }
    public function doAshabResultOrder(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'price' => 'required',
                'orderid' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 200, 'message' => implode("\n", $validator->messages()->all())]);
            }
        $user_id = auth('api')->id();
         $user = User::query()->findOrFail($user_id);
        if($user->status != 'active'){
             $message = 'عذرا .. حسابك غير فعال';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]); 
        }
            $balanceIn = UserWallet::where('user_id', $user_id)->where('type', 0)->sum('total_price');
            $balanceOut = UserWallet::where('user_id', $user_id)->where('type', 1)->sum('total_price');
            $balance = $balanceIn - $balanceOut;

            $data_denomination = getAshabGameInfo($request->game_id);
            $cost_ = 0;
            foreach ($data_denomination->products as $one_pro)
            {
                if($one_pro->denomination_id == $request->denomination_id)
                {
                    $cost_ =  $one_pro->product_price;
                    break;
                }
            }


        $is_maintenance = Setting::orderByDesc('id')->first()->is_maintenance;

        if($is_maintenance == 1){
                            $message = 'عذرا .. التطبيق في وضع الصيانة';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]);
        }


            $lolo= $request->price;
//            $koko= from_doller_to_turky($this->getlolo($request));
            if(get_user_carrency_from_api() == "turkey") {
                $koko = from_doller_to_turky($cost_);
            }
            elseif(get_user_carrency_from_api() == "dollar")
            {
                $koko = $cost_;
            }
            $soso = $lolo - $koko;
//            $soso = round($soso,2);

            $Wellet_profit=new Wellet_profit();
            $Wellet_profit->user_id=$user_id;
            $Wellet_profit->profit = $soso;
            $Wellet_profit->purchasing_price= $koko;
            $Wellet_profit->details= ' رقم الطلب ' .  $request->orderid . ' السعر ' .$request->price;
            $Wellet_profit->type =0;
            $Wellet_profit->city_id =$request->city_id;
            $Wellet_profit->service_name =4;
            $Wellet_profit->status_wellet = 1;
            $Wellet_profit->save();
            $Wellet_profit->id;

            $notify = new Notify();
            $notify->user_id = $user_id;
            $notify->order_id = 0;
            $notify->messag_type = 0;
            $notify->message = ' رقم الطلب ' .  $request->orderid . ' السعر ' .$request->price;
            $notify->save();

            $AshabRequest = new AshabRequest();
            $AshabRequest->user_id = $user_id;
            $AshabRequest->wellet_profit_id =$Wellet_profit->id;
            $AshabRequest->order_id =$request->orderid;
            $AshabRequest->price =  $request->price;
            $AshabRequest->currency = get_user_carrency_from_api();
            $AshabRequest->status = 1;
            $data_from_ashab = getAshabOrderId($request->orderid);
            if(isset($data_from_ashab->order_status))
            {
                $AshabRequest->status_ashab = $data_from_ashab->order_status;
            }
            $AshabRequest->save();

            if( $AshabRequest->status_ashab == "completed")
            {
                $Wellet_profit->status_wellet = 0;
                $Wellet_profit->save();
                $AshabRequest->status = 0;
                $AshabRequest->save();
            }

            if($request->has('page_id'))
            {
                $ashab_log = new AshabLog();
                $ashab_log->user_id = $user_id;
                $ashab_log->page_id = $request->page_id;
                $ashab_log->order_id = $request->orderid;
                $ashab_log->game_id = $request->game_id;
                $ashab_log->denomination_id = $request->denomination_id;
                $ashab_log->price = $request->price;
                $ashab_log->qty = $request->qty;
                $ashab_log->playerid = $request->player_id;
                $ashab_log->city_id = $request->city_id;
                $ashab_log->save();
            }

            $message = ' رقم الطلب ' .  $request->orderid . ' السعر ' .$request->price;
            $tokens = Token::where('user_id',$user_id)->pluck('fcm_token')->toArray();
            sendNotificationToUsers( $tokens,$message,"2",$request->orderid );
            $message = "تم الدفع بنجاح";
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        }catch (\Exception $e)
        {
            return response()->json(['status' => false, 'code' => 200, 'message' => $e->getMessage()]);
        }
    }
//    public function getlolo(Request $request)
//    {
//        $data_orders = getAshabOrderId( $request->orderid );
//
//        $all_data_games = getAshabGames();
//        $cost_ = 0;
//
//        foreach ($all_data_games->products as $one_pro)
//        {
//            $data_denomination = getAshabGameInfo($one_pro->id);
//
//            foreach ($data_denomination->products as $one_denomination)
//            {
//                if($one_denomination->product_name == $data_orders->order_product)
//                {
//                    $cost_ =  $one_denomination->product_price;
//                    return $cost_;
//                }
//            }
//
//        }
//
//        return $cost_;
//
//    }
    public function getAshabGameCards($gameId)
    {
                $user_id = auth('api')->id();
         $user = User::query()->findOrFail($user_id);
        if($user->status != 'active'){
             $message = 'عذرا .. حسابك غير فعال';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]); 
        }

        $rr = AshabGamesCards::where("ashab_game_id",$gameId)->get();
        foreach ($rr as $r)
        {
            $r->nagma_average = $this->getNagma($r->id,$user);
        }
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'ashab_game_cards' => $rr]);
    }
    public function getNagma($ashab_game_card,$user)
    {

        if(isset($user->nagma_ashab_id))
        {
            $nagma_ashab = NagmaAshab::where("id",$user->nagma_ashab_id)->first();
            if(isset($nagma_ashab->ashab_cards_ids) && $nagma_ashab->status == 0)
            {
                $nagma = explode(',',$nagma_ashab->ashab_cards_ids);
                if($nagma != null && isset($nagma))
                {
                    foreach ($nagma as $one)
                    {
                        if($ashab_game_card == $one)
                        {
                            return $nagma_ashab->average;
                        }
                    }
                }
            }
        }

        return 0;

    }
    public function contactUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'mobile' => 'required|digits_between:8,12',
            'name' => 'required',
            'message' => 'required',
            //     'type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }

        $contact = new  Contact();
        $contact->email = $request->get('email');
        $contact->name = $request->get('name');
        $contact->city_id=$request->city_id ?? 0;
        $contact->mobile = $request->get('mobile');
        $contact->message = $request->get('message');
        $contact->subject = 'اقتراح';
        $contact->read = 0;
        $contact->save();
        $message = __('api.ok');

        return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
    }
    public function networkRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //  'email' => 'required|email',
            'mobile' => 'required|digits_between:8,12',
            'name' => 'required',
            'address' => 'required',
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }

        $networkRequest = new  NetworkRequest();
        $networkRequest->name = $request->get('name');
        $networkRequest->mobile = $request->get('mobile');
        $networkRequest->address = $request->get('address');
        $networkRequest->user_id = $request->get('user_id');
        $networkRequest->save();
        $message = __('api.ok');

        return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
    }
    public function enableNotificationForNetwork(Request $request)
    {
                $user_id = auth('api')->id();
         $user = User::query()->findOrFail($user_id);
        if($user->status != 'active'){
             $message = 'عذرا .. حسابك غير فعال';
                return response()->json(['status' => false, 'code' => 200, 'message' => $message  ]); 
        }
        $validator = Validator::make($request->all(), [
            'network_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $chick = EnableNotificationNetwork::where('user_id',$user_id)->where('network_id',$request->network_id)->first();
        $network = Wifi::query()->where('status', 'active')->where('id', $request->network_id)->first();

        if($chick){
            $chick->delete();
            $message = 'تم الغاء الاشتراك';
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        }
        else {
            $networkRequest = new  EnableNotificationNetwork();
            $networkRequest->network_id = $request->get('network_id');
            $networkRequest->user_id = $user_id;
            $networkRequest->store_id = $network->store_id;
            $networkRequest->save();
            $message = __('api.ok');

            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
        }
    }
    public function addAzkar(Request $request)
    {
        $rules = [
            'name_ar' => 'required',
            'name_en' => 'required',
            'details_ar' => 'required',
            'details_en' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'message' => implode("\n",$validator->messages()->all())]);
        }

        $locales = Language::all()->pluck('lang');

        $product = new Azkar();
        foreach ($locales as $locale)
        {
            $product->translateOrNew($locale)->name = $request->get('name_' . $locale);
            $product->translateOrNew($locale)->details = $request->get('details_' . $locale);
        }


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $image = str_random(15) . "_" . rand(1000000, 9999999) . "_" . time() . "_" . rand(1000000, 9999999) . ".jpg";
            Image::make($file)->resize(800, null, function ($constraint) {$constraint->aspectRatio();})->save("uploads/products/$image");
            $product->image = $image;
        }

        $product->save();
    }
    public function allQuestions()
    {
        $questions = Question::query()->where('status', 'active')->get();
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'questions' => $questions]);
    }
    public function getRecharge(Request $request)
    {
        $data = Recharge_info::query()->orderBy('id','asc')->get();
        if (count($data) > 0) {
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'mc' => $data]);

        } else {
            $message = __('api.not_found');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'mc' => $data]);

        }
    }
    
    
    
    function gen_uuid() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

      // 32 bits for "time_low"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff),

      // 16 bits for "time_mid"
      mt_rand(0, 0xffff),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand(0, 0x0fff) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand(0, 0x3fff) | 0x8000,

      // 48 bits for "node"
      mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}
    
    
}

