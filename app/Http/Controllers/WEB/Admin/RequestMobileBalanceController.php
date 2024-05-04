<?php

namespace App\Http\Controllers\WEB\Admin;


use App\Models\Language;
use App\Models\RequestMobileBalance;
use App\Models\MobileCompany;
use App\Models\Setting;
use App\Models\Token;
use App\Models\Notifiy;
use App\Models\UserWallet;
use App\Models\Wellet_profit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use Auth;
class RequestMobileBalanceController extends Controller
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
        $request = RequestMobileBalance::query();
              $admin = auth('admin')->user();
        if ($admin->city_id > 0){
            $request->where('city_id',$admin->city_id);
        }

        $request = $request->orderBy('id', 'desc')->get();//paginate(20);
        return view('admin.requestMobileBalance.home', [
            'request' => $request,
        ]);
    }

    public function edit($id)
    {
        //
        $item=RequestMobileBalance::findOrFail($id);
        return view('admin.requestMobileBalance.edit', [
            'item' => $item ,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $roles = [
          //'type'   => 'required',
         //'image' => 'image|mimes:jpeg,jpg,png',
        ];

        $this->validate($request, $roles);

        $item = RequestMobileBalance::query()->findOrFail($id);
        set_currency($item);
        $item->action=$request->action;
        $item->admin_response=$request->admin_response;
        $item->save();

        if($item->action == 0)
        {
            $message =  __('جديد');
        }
        elseif($item->action == 1)
        {
            $message =  __('قيد التحظير');
        }
        elseif($item->action == 2)
        {
             $message =  __('طلبك مكتمل'.'  ' .$request->admin_response);

            $Wellet_profit = Wellet_profit::where('id',$item->wellet_profit_id)->first();
            $Wellet_profit->status_wellet=0;
            $Wellet_profit->created_at= Carbon::now()->format('YmdHis');
            $Wellet_profit->save();
        }
        elseif($item->action == 3)
        {
          $message =  __('api.OrderIsCancel');
            if (User_Wallet_Check_Balance(1,  $item->balance))
            {
          $wallet=new UserWallet();
          $wallet->user_id=$item->user_id;
          $wallet->order_id=0;
          $wallet->title= 'اعادة رصيد' ;
          $wallet->details=' رصيد موبايل بقيمة : '.$item->balance ;
          $wallet->total_price =$item->balance;
          $wallet->type =0;
          $wallet->save();

          $wallet_J=new UserWallet();
          $wallet_J->user_id=1;
          $wallet_J->order_id=0;
          $wallet_J->title= 'اعادة رصيد' ;
          $wallet_J->details=' رصيد موبايل بقيمة : '.$item->balance ;
          $wallet_J->total_price =$item->balance;
          $wallet_J->type =1;
          $wallet_J->save();

          $Wellet_profit = Wellet_profit::where('id',$item->wellet_profit_id)->first();
          $Wellet_profit->delete();

            $item->admin_response=$request->admin_response;
            }
            else
            {
                return redirect()->back()->with('error', __('احد المستفيدين من ارباح هذه الخدمة ليس لديه رصيد كافي في محفظته '));
            }
        }
        $item->save();

        $tokens = Token::where('user_id',$item->user_id)->pluck('fcm_token')->toArray();
        sendNotificationToUsers( $tokens,  'طلب رصيد : '.$item->balance." ".$message,'0','0' );
        $notifiy= New Notifiy();
        $notifiy->user_id = $item->user_id;
        $notifiy->order_id =0;
        $notifiy->message = $message;
        $notifiy->save();
        set_currency("");
        return redirect()->back()->with('status', __('cp.update'));
    }


    public function destroy($id)
    {
        //
        $item = Azkar::query()->findOrFail($id);
        if ($item) {
            Azkar::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }
    
    

}
