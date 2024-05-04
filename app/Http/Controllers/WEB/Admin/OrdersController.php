<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;


use App\Models\Store;
use App\Models\Wellet_profit;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Language;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Token;
use App\Models\Notifiy;
use App\Models\Setting;
use App\Models\UserWallet;
use DB ;


class OrdersController extends Controller
{


    public function image_extensions()
    {

        return array('jpg', 'png', 'jpeg', 'gif', 'bmp', 'pdf');

    }

    public function index(Request $request)
    {
                $items = Order::query();

        
      $admin = auth('admin')->user();
        if ($admin->city_id > 0){
            $items->where('delivery_city_id',$admin->city_id);
        }
        $locales = Language::all();
        $orders0=Order::where("status" ,"0")->count();
        $orders1=Order::where("status" ,"1")->count();
        $orders2=Order::where("status" ,"2")->count();

        if ($request->has('statusUser')) {
            if ($request->get('statusUser') != null)
                $items->where('status',  $request->get('statusUser'));
        }

        if ($request->has('userName')) {
            if ($request->get('userName') != null)
                $items->whereHas('user',function ($query) use($request){$query->where('name',  $request->get('userName'));});
        }


        $items = $items->orderBy('id', 'desc')->get();


        // return view('admin.order.home', compact('items', 'locales'));

        return view('admin.orders.home', [
            'items' => $items,
            'locales' => $locales,
            'orders0' => $orders0,
            'orders1' => $orders1,
            'orders2' => $orders2,
        ]);
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $products =OrderProduct::where('order_id',$order->id)->get();
        $store = Store::where('id',$order->store_id)-> first();
        $setting = Setting::first();
        return view('admin.orders.edit', [
            'order' => $order ,
            'products' => $products]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $store = Store::where('id',$order->store_id)-> first();
        $setting = Setting::first();
        set_currency($order);

        $app_percent = $order->total_price * $order->Store->app_percent / 100;
        $soso = $setting->min_order + $app_percent;
        if($request->status == 0){
            $order->status = $request->status;
            $order->invoice_discount = $request->invoice_discount;
            $order->save();
            $message =  __('api.OrderIsPreparing');
        }
        elseif($request->status == 1){
            $order->status = $request->status;
            $order->invoice_discount = $request->invoice_discount;
            $order->save();
            $message =  __('api.OrderIsOnDelivery');
        }
        elseif($request->status == 2){
            $order->status = $request->status;
            $order->invoice_discount = $request->invoice_discount;
            $order->save();
             $message =  __('api.OrderIsComplete');
        }
        elseif($request->status == 3){
            $message =  __('api.OrderIsCancel');
        if(User_Wallet_Check_Balance($store->user_id,$order->total_price))
        {
         $order->status = $request->status;
         $order->invoice_discount = $request->invoice_discount;
         $order->save();

         $wallet=new UserWallet();
         $wallet->user_id=$order->user_id;
         $wallet->order_id=$order->id;
         $wallet->title= 'اعادة رصيد' ;
         $wallet->details='رقم الطلب: '. $order->id ;
         $wallet->total_price =$order->total_price;
         $wallet->type =0;
         $wallet->save();
            $wallet_s = new UserWallet();
            $wallet_s->user_id = $store->user_id;
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
        }
        else
            {
                return redirect()->back()->with('error', __('احد المستفيدين من ارباح هذه الخدمة ليس لديه رصيد كافي في محفظته '));
            }
        }
        elseif($request->status == 4)
        {  $message =  __('api.refund');
        if(User_Wallet_Check_Balance(1,$setting->min_order)&&User_Wallet_Check_Balance($store->user_id,$order->total_price))
        {
         $order->status = $request->status;
            $order->invoice_discount = $request->invoice_discount;
            $order->save();

         $wallet=new UserWallet();
         $wallet->user_id=$order->user_id;
         $wallet->order_id=$order->id;
         $wallet->title= 'اعادة رصيد' ;
         $wallet->details='رقم الطلب: '. $order->id ;
         $wallet->total_price =$order->total_price;
         $wallet->type =0;
         $wallet->save();

            $wallet_s = new UserWallet();
            $wallet_s->user_id = $store->user_id;
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
        }
        else
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

    public function destroy($id)
    {
        // return $id;
        $item = Order::findOrFail($id);
        if ($item) {
            Order::where('id', $id)->delete();
            return "success";
        }
        return "fail";
    }
    
    public function change_orderSts(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->sts;
        $order->save();
        if($request->sts == 0){
            $message =  __('api.OrderIsPreparing');
        }
        elseif($request->sts == 1){
            $message =  __('api.OrderIsOnDelivery');
        }
        elseif($request->sts == 2){
             $message =  __('api.OrderIsComplete');
        }
        $order_id = $id;
        $tokens = Token::where('user_id',$order->user_id)->pluck('fcm_token')->toArray();
        // return $tokens_ios;
        sendNotificationToUsers( $tokens, $message,'2',$id );
        $notifiy= New Notifiy();
        $notifiy->user_id = $order->user_id;
        $notifiy->order_id = $order_id;
        $notifiy->message = $message;
        $notifiy->save();
        return "success";
    }
    
    public function printOrder($id)
    {
        $order = Order::findOrFail($id);
        $products =OrderProduct::where('order_id',$order->id)->get();
        $product =Product::get();

        return view('admin.orders.invoice', [
            'order' => $order ,
            'products' => $products]);
    }


}


