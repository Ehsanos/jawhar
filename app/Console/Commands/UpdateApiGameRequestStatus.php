<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\ImportFile;
use App\Imports\UsersImport;
use App\Imports\UserImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Token;
use App\Models\Notifiy;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Product;
use App\Models\Store;
use App\Models\Api;
use App\Models\GameRequest;
class UpdateApiGameRequestStatus extends Command

{
    /**
     * The name and signature of the console command.
     *
     * @var string

     */
    protected $signature = 'UpdateApiGameRequestStatus:update';

    /**

     * The console command description.
     *
     * @var string

     */

    protected $description = 'Command description';

    /**

     * Create a new command instance.

     *

     * @return void

     */

    public function __construct()

    {
        parent::__construct();

    }

    /**

     * Execute the console command.
     *
     * @return mixed
     */
    // public function handle()

    // {

    //     \Log::info("Cron is working fine!");

    //     /*
    //       Write your database logic we bellow:
    //       Item::create(['name'=>'hello new']);

    //     */
    //     $this->info('Demo:Cron Cummand Run successfully!');

    // }
    
    
    public function handle()
    {
        $api_game_request_from_prince_cash = GameRequest::where('api_id','1')->get();

        $prince_cash_api = Api::where('id','1')->first();
        $string_prince_cash_api='';
        foreach ($api_game_request_from_prince_cash as $key=>$one){
            if($one->api_order_id >0){
                $string_prince_cash_api = $string_prince_cash_api.$one->api_order_id;
                if($key < (count($api_game_request_from_prince_cash) - 1)){
                    $string_prince_cash_api = $string_prince_cash_api.',';
                }

                
            }
        }
        
        $base_url = substr($prince_cash_api->url, 0, strrpos( $prince_cash_api->url, '/'));
        $end_point = $base_url.'/check?orders=['.$string_prince_cash_api.']';
        
        $response = callAPI($end_point , $prince_cash_api->token,[],'GET','1');
        $prince_cash_data = json_decode(@$response->message)->data;
        foreach ($api_game_request_from_prince_cash as $one){
            $item = GameRequest::where(['api_id' => '1' , 'api_order_id'=>$one->order_id])->first();
            if($item->api_order_status != $one->status){
                $this->sendNotification($item->user_id , 0 , $one->status);
            }
            
            GameRequest::where(['api_id' => '1' , 'api_order_id'=>$one->order_id])
            ->update(['api_order_status'=>$one->status]);
            
        }
        
        
        
        
        $api_game_request_from_fast_store = GameRequest::where('api_id','3')->get();

        $fast_store_api = Api::where('id','3')->first();
        $string_fast_store_api='';
        foreach ($api_game_request_from_fast_store as $key=>$one){
            if($one->api_order_id >0){
                $string_fast_store_api = $string_fast_store_api.$one->api_order_id;
                if($key < (count($api_game_request_from_fast_store) - 1)){
                    $string_fast_store_api = $string_fast_store_api.',';
                }

                
            }
        }
        $fast_store_base_url = substr($fast_store_api->url, 0, strrpos( $fast_store_api->url, '/'));
        $fast_store_end_point = $fast_store_base_url.'/check?orders=['.$string_prince_cash_api.']';
        
        $response = callAPI($fast_store_end_point , $fast_store_api->token,[],'GET','3');
        $fast_store_data = json_decode(@$response->message)->data;
        foreach ($api_game_request_from_fast_store as $one){
            $item = GameRequest::where(['api_id' => '3' , 'api_order_id'=>$one->order_id])->first();
            if($item->api_order_status != $one->status){
                $this->sendNotification($item->user_id , 0 , $one->status);
            }
            
            GameRequest::where(['api_id' => '3' , 'api_order_id'=>$one->order_id])
            ->update(['api_order_status'=>$one->status]);
        }
        
         
        
        
        $api_game_request_from_lord_telecom = GameRequest::where('api_id','5')->get();

        $lord_telecom_api = Api::where('id','5')->first();
        foreach ($api_game_request_from_lord_telecom as $key=>$one){
            if($one->api_order_id >0){
                if($one->api_id == 5){
                    $lord_telecom_base_url =  $lord_telecom_api->url;
                    $lord_telecom_end_point = $lord_telecom_base_url.'/OrderStatus?API='.$lord_telecom_api->token.'&orderId='.$one->api_order_id;
                    
                    $response = callAPI($lord_telecom_end_point , $lord_telecom_api->token,[],'GET','5');
                    $lord_telecom_status = json_decode(@$response->message)->statusDesc;
               
                    $item = GameRequest::where(['api_id' => '5' , 'api_order_id'=>$one->order_id])->first();

                    if($item->api_order_status != $lord_telecom_status){
                        $this->sendNotification($item->user_id , 0 , $lord_telecom_status);
                    }
            
            
                    GameRequest::where(['api_id' => '5' , 'api_order_id'=>$one->api_order_id])
                    ->update(['api_order_status'=>$lord_telecom_status]);
                    }
            }
        }
        
        
        
        
        
        $api_game_request_from_speedcard = GameRequest::where('api_id','8')->get();

        $speedcard_api = Api::where('id','8')->first();
        $string_speedcard_api='';
        foreach ($api_game_request_from_speedcard as $key=>$one){
            if($one->api_order_id >0){
                $string_speedcard_api = $string_speedcard_api.$one->api_order_id;
                if($key < (count($api_game_request_from_speedcard) - 1)){
                    $string_speedcard_api = $string_speedcard_api.',';
                }

                
            }
        }
        $speedcard_base_url = substr($speedcard_api->url, 0, strrpos( $speedcard_api->url, '/'));
        $speedcard_end_point = $speedcard_base_url.'/check?orders=['.$string_speedcard_api.']';
        
        $response = callAPI($speedcard_end_point , $speedcard_api->token,[],'GET','8');
        
        $speedcard_data = json_decode(@$response->message)->data;
        // dd($speedcard_data);
        foreach ($speedcard_data as $one){
            $item = GameRequest::where(['api_id' => '8' , 'api_order_id'=>$one->order_id])->first();
            if($item->api_order_status != $one->status){
                $this->sendNotification($item->user_id , 0 , $one->status.'-'.$one->replay_api[0]);
            }
            
            GameRequest::where(['api_id' => '8' , 'api_order_id'=>$one->order_id])
            ->update(['api_order_status'=>$one->status.'-'.$one->replay_api[0]]);
        }
    }
    
    public function sendNotification($user_id ,$order_id, $message){
            $tokens = Token::where('user_id', $user_id)->pluck('fcm_token')->toArray();
            sendNotificationToUsers( $tokens,$message,"2",$order_id );
            $notifiy = new Notifiy();
            $notifiy->user_id = $user_id;
            $notifiy->order_id = $order_id;
            $notifiy->message = $message;
            $notifiy->save();
    }
 

}