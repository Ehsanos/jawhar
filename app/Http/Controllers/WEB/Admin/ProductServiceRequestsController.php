<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Notifiy;
use App\Models\ProductServiceRequest;
use App\Models\Setting;
use App\Models\Token;
use App\Models\UserWallet;
use App\Models\Wellet_profit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductServiceRequestsController extends Controller
{
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
        $items = ProductServiceRequest::query();

        $admin = auth('admin')->user();
//        if ($admin->city_id > 0){
//            $items->where('city_id',$admin->city_id);
//        }
//
//        if ($request->has('status')) {
//            if ($request->get('status') != null)
//                $items->where('status',  $request->get('status'));
//        }

        $items = $items->orderBy('id', 'desc')->get();
        return view('admin.ProductServicesRequest.home', [
            'items' => $items  ,
        ]);
    }

    public function edit($id)
    {
//        $admin = auth('admin')->user();
//        if ($admin->city_id > 0){
//            $order=ProductServiceRequest::where('city_id',$admin->city_id)->findOrFail($id);
//        }
//        else{
//
//        }
        $order = ProductServiceRequest::findOrFail($id);
        return view('admin.ProductServicesRequest.edit', [
            'order' => $order,
        ]);

    }

    public function update(Request $request, $id)
    {

        $roles = [
            'status' => 'required',
        ];
        if($request->status == 1)
        {
            $roles['number'] = 'required|integer';
        }
        $this->validate($request, $roles);
        $order = ProductServiceRequest::findOrFail($id);
        set_currency($order);
        if($request->status == 1 )
        {
            $Wellet_profit = Wellet_profit::where('id',$order->wellet_profit_id)->first();
            $Wellet_profit->status_wellet=0;
            $Wellet_profit->created_at= Carbon::now()->format('YmdHis');
            $Wellet_profit->save();
          if($request->number == "")
          {
                return redirect()->back()->withInput()->withErrors('الرجاء ادخال الرقام الذي تم بيعه');
          }
        }
        if($request->status == 2)
        {
            if (User_Wallet_Check_Balance(1,  $order->j_price) && User_Wallet_Check_Balance($order->Whatsapp->user_id,  $order->wapp_owner_price)) {
            $wallet_c=new UserWallet();
            $wallet_c->user_id=$order->user_id;
            $wallet_c->order_id=$order->id;
            $wallet_c->title= 'اعادة رصيد' ;
            $wallet_c->details='رقم الطلب: '. $order->id ;
            $wallet_c->total_price =$order->user_price;
            $wallet_c->type =0;
            $wallet_c->save();

            $wallet=new UserWallet();
            $wallet->user_id=$order->Whatsapp->user_id;
            $wallet->order_id=$order->id;
            $wallet->title= 'اعادة رصيد' ;
            $wallet->details='رقم الطلب: '. $order->id ;
            $wallet->total_price =$order->wapp_owner_price;
            $wallet->type =1;
            $wallet->save();

            $wallet_J=new UserWallet();
            $wallet_J->user_id=1;
            $wallet_J->order_id=$order->id;
            $wallet_J->title= 'إلغاء طلبد' ;
            $wallet_J->details='رقم الطلب: '. $order->id .' اسم الخدمة ' . $order->productService->name . ' السعر ' . $order->price;
            $wallet_J->total_price = $order->j_price;
            $wallet_J->type =1;
            $wallet_J->save();

            $Wellet_profit = Wellet_profit::where('id',$order->wellet_profit_id)->first();
            $Wellet_profit->delete();
            }
            else
            {
                return redirect()->back()->with('error', __('احد المستفيدين من ارباح هذه الخدمة ليس لديه رصيد كافي في محفظته '));
            }
        }

        $order->status = $request->status;

        if ($request->has('number')) {
            $order->number = $request->number;
        }

        $order->save();

        if($request->status == 1){
            $message =  __('api.OrderIsComplete'). '<br>'. 'الرقم المباع:' . $order->number . '<br>'.'رقم الطلب: '. $order->id . '<br>' .'خدمة :' .  $order->productService->name . '<br>' . 'خدمة العملاء:' . $order->Whatsapp->phone;
        }
        elseif($request->status == 2) {
            $message = __('api.OrderIsCancel'). '<br>'.'رقم الطلب: '. $order->id . '<br>' .'خدمة :' .  $order->productService->name . '<br>' . 'خدمة العملاء:' . $order->Whatsapp->phone;
        }

        $tokens = Token::where('user_id',$order->user_id)->pluck('fcm_token')->toArray();
        sendNotificationToUsers( $tokens,$message,"2",$id );

        $notifiy= New Notifiy();
        $notifiy->user_id = $order->user_id;
        $notifiy->order_id =$order->id;
        $notifiy->message = $message;
        $notifiy->save();

        set_currency("");
        return redirect()->back()->with('status', __('cp.update'));
    }
}
