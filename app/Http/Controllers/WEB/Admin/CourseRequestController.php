<?php
namespace App\Http\Controllers\WEB\Admin;
use App\Models\Wellet_profit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Course;
use App\Models\CourseRequest;
use App\Models\Token;
use App\Models\Notifiy;
use App\Models\UserWallet;

class CourseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->locales = Language::all();
        $this->settings = Setting::query()->first();
        view()->share([
            'locales' => $this->locales,
            'settings' => $this->settings,

        ]);
    }
    public function index(Request $request)
    {
                $items = CourseRequest::query();
        
          $admin = auth('admin')->user();
        if ($admin->city_id > 0){
            $items->where('city_id',$admin->city_id);
        }
        if ($request->has('statusUser')) {
            if ($request->get('statusUser') != null)
                $items->where('status',  $request->get('statusUser'));
        }        $items = $items->orderBy('id', 'desc')->get();
        return view('admin.courseRequest.home', [
            'items' => $items  ,
        ]);
    }
    public function edit($id)
    {
        $order = CourseRequest::findOrFail($id);
        return view('admin.courseRequest.edit', [
            'order' => $order,
        ]);

    }
    public function update(Request $request, $id)
    {
        $order = CourseRequest::findOrFail($id);
        set_currency($order);


        $user_id_instit = $order->institute->user_id;
        $fofo= $order->institute->app_percent;
        $lolo= $order->price;
        $koko=$fofo*$lolo/100;
        $soso=$lolo - $koko;
        if($request->status == 0){
            $message =  __('api.OrderIsPreparing');
            $order->status = $request->status;
            $order->save();
        }
        elseif($request->status == 1){
            $message =  __('api.OrderIsOnDelivery');
            $order->status = $request->status;
            $order->save();
        }
        elseif($request->status == 2){
             $message =  __('api.OrderIsComplete');
            $order->status = $request->status;
            $order->save();
            $Wellet_profit = Wellet_profit::where('id',$order->wellet_profit_id)->first();
            $Wellet_profit->status_wellet=0;
            $Wellet_profit->save();
        }
        elseif($request->status == 3)
        {
            $message =  __('api.OrderIsCancel');
            if(User_Wallet_Check_Balance(1,$koko) && User_Wallet_Check_Balance($user_id_instit,$soso))
            {
                if($order->payment == 0) {
                    $order->status = $request->status;
                    $order->save();
                    $wallet = new UserWallet();
                    $wallet->user_id = $order->user_id;
                    $wallet->order_id = $order->id;
                    $wallet->title = 'اعادة رصيد';
                    $wallet->details = 'رقم الطلب: ' . $order->id;
                    $wallet->total_price = $order->price;
                    $wallet->type = 0;
                    $wallet->save();

                    $wallet_j = new UserWallet();
                    $wallet_j->user_id = 1;
                    $wallet_j->order_id = $order->id;
                    $wallet_j->title = 'اشتراك معهد اعادة رصيد';
                    $wallet_j->details = ' اسم الخدمة ' . $order->name . ' نسبة الربح ' . $fofo;
                    $wallet_j->total_price = $koko;;
                    $wallet_j->type = 1;
                    $wallet_j->save();

                    $wallet_instit = new UserWallet();
                    $wallet_instit->user_id = $user_id_instit;
                    $wallet_instit->total_price = $soso;
                    $wallet_instit->title = 'اشتراك معهد اعادة رصيد  ';
                    $wallet_instit->details = ' اسم الخدمة ' . $order->name . ' السعر ' . $soso;
                    $wallet_instit->type = 1;
                    $wallet_instit->save();

                    $Wellet_profit = Wellet_profit::where('id', $order->wellet_profit_id)->first();
                    $Wellet_profit->delete();
                }
            }else
            {
                return redirect()->back()->with('error', __('احد المستفيدين من ارباح هذهن الخدمة ليس لديه رصيد كافي في محفظته '));
            }
        } elseif($request->status == 4)
        { $message =  __('api.refund');
            if(User_Wallet_Check_Balance(1,$koko) && User_Wallet_Check_Balance($user_id_instit,$soso))
        {

            if($order->payment == 0)
            {
                $order->status = $request->status;
                $order->save();
             $wallet=new UserWallet();
             $wallet->user_id=$order->user_id;
             $wallet->order_id=$order->id;
             $wallet->title= 'اعادة رصيد' ;
             $wallet->details='رقم الطلب: '. $order->id ;
             $wallet->total_price =$order->price;
             $wallet->type =0;
             $wallet->save();
            $wallet_j = new UserWallet();
            $wallet_j->user_id = 1;
            $wallet_j->order_id = $order->id;
            $wallet_j->title = 'اشتراك معهد اعادة رصيد';
            $wallet_j->details = ' اسم الخدمة ' .$order->name . ' نسبة الربح ' .$fofo;
            $wallet_j->total_price = $koko;  ;
            $wallet_j->type = 1;
            $wallet_j->save();

            $wallet_instit = new UserWallet();
            $wallet_instit->user_id = $user_id_instit;
            $wallet_instit->total_price = $lolo -$koko ;
            $wallet_instit->title = 'اشتراك معهد اعادة رصيد  ';
            $wallet_instit->details = ' اسم الخدمة ' .$order->name . ' السعر ' .  $soso;
            $wallet_instit->type = 1;
            $wallet_instit->save();

            $Wellet_profit = Wellet_profit::where('id',$order->wellet_profit_id)->first();
            $Wellet_profit->delete();

            }
             if($order->payment == 1)
             {
                 $setting = Setting::first();
                 $wallet=new UserWallet();
                 $wallet->user_id=$order->user_id;
                 $wallet->order_id=$order->id;
                 $wallet->title= 'اعادة رصيد' ;
                 $wallet->details='رقم الطلب: '. $order->id ;
                 $wallet->total_price =$setting->min_order;
                 $wallet->type =0;
                 $wallet->save();

                 $wallet_j = new UserWallet();
                 $wallet_j->user_id = 1;
                 $wallet_j->order_id = $order->id;
                 $wallet_j->title = 'اشتراك معهد اعادة رصيد';
                 $wallet_j->details = ' اسم الخدمة ' .$order->name . ' نسبة الربح ' .$fofo;
                 $wallet_j->total_price = $koko;  ;
                 $wallet_j->type = 1;
                 $wallet_j->save();

                 $wallet_instit = new UserWallet();
                 $wallet_instit->user_id = $user_id_instit;
                 $wallet_instit->total_price = $lolo -$koko ;
                 $wallet_instit->title = 'اشتراك معهد اعادة رصيد  ';
                 $wallet_instit->details = ' اسم الخدمة ' .$order->name . ' السعر ' . $soso;
                 $wallet_instit->type = 1;
                 $wallet_instit->save();

                 $Wellet_profit = Wellet_profit::where('id',$order->wellet_profit_id)->first();
                 $Wellet_profit->delete();
              }
            }else
        {
            return redirect()->back()->with('error', __('احد المستفيدين من ارباح هذه الخدمة ليس لديه رصيد كافي في محفظته '));
        }
        }
            $order_id = $id;
            $tokens = Token::where('user_id',$order->user_id)->pluck('fcm_token')->toArray();
            sendNotificationToUsers( $tokens,$message,"2",$id );
            $notifiy= New Notifiy();
            $notifiy->user_id = $order->user_id;
            $notifiy->order_id =$order_id;
            $notifiy->message = $message;
            $notifiy->save();
            set_currency("");
            return redirect()->back()->with('status', __('cp.update'));
    }

}
