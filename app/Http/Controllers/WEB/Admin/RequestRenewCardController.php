<?php

namespace App\Http\Controllers\WEB\Admin;


use App\Models\Language;
use App\Models\NetworkSections;
use App\Models\Notifiy;
use App\Models\RequestRenewCard;
use App\Models\MobileCompany;
use App\Models\Setting;
use App\Models\Store;
use App\Models\Token;
use App\Models\UserPermission;
use App\Models\UserWallet;
use App\Models\Wellet_profit;
use App\Models\Wifi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use Auth;

class RequestRenewCardController extends Controller
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

    public function index($my_store_id = null)
    {
        $req = RequestRenewCard::query();
        $admin = auth('admin')->user();
        $admin_perm = UserPermission::where('user_id', $admin->id)->first();
        if($my_store_id != null)
        {
            $wifi= Wifi::where("store_id",$my_store_id)->first();
            if (isset($wifi->id)) {
                $req->where('wifi_id', $wifi->id);
            }
        }
        if ($admin->city_id > 0) {
            $req->where('city_id', $admin->city_id);
            if (isset($admin_perm->store_id) && $admin_perm->store_id > 0) {
                $momo = Wifi::where("store_id", $admin_perm->store_id)->first();
                if (isset($momo->id)) {
                    $req->where('wifi_id', $momo->id);
                }
            }
        }
        $req = $req->orderBy('id', 'desc')->get();//paginate(20);
        return view('admin.requestRenewCard.home', [
            'request' => $req,
        ]);
    }

    public function edit($id)
    {
        $admin = auth('admin')->user();
        if ($admin->city_id > 0) {
            $item = RequestRenewCard::where('city_id', $admin->city_id)->findOrFail($id);
        } else {
            $item = RequestRenewCard::findOrFail($id);
        }
        return view('admin.requestRenewCard.edit', [
            'item' => $item,
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $roles = [
            //  'type'   => 'required',
            //     'image' => 'image|mimes:jpeg,jpg,png',
        ];

        $this->validate($request, $roles);

        $RequestRenewCardt_order = RequestRenewCard::findOrFail($id);
        set_currency($RequestRenewCardt_order);
        $soso = $RequestRenewCardt_order->balance - $RequestRenewCardt_order->app_percent;
        $store_user_id = Store::where("id", $RequestRenewCardt_order->store_id)->first()->user_id;
        if ($request->action == 0) {
            $RequestRenewCardt_order->action = $request->action;
            $RequestRenewCardt_order->save();
        }
        elseif ($request->action == 1)
        {
            $RequestRenewCardt_order->action = $request->action;
            $RequestRenewCardt_order->save();
            $message =  __('طلبك مكتمل'.'  ' .$request->admin_response);
            $Wellet_profit = Wellet_profit::where('id',$RequestRenewCardt_order->wellet_profit_id)->first();
            $Wellet_profit->status_wellet=0;
            $Wellet_profit->created_at= Carbon::now()->format('YmdHis');
            $Wellet_profit->save();

        }
        elseif ($request->action == 2)
        { $message = __('api.OrderIsCancel');
            if(User_Wallet_Check_Balance(1,$RequestRenewCardt_order->app_percent) && User_Wallet_Check_Balance($store_user_id->Store->user_id,$soso))
            {

                $RequestRenewCardt_order->action = $request->action;
                $RequestRenewCardt_order->save();

                $wallet = new UserWallet();
                $wallet->user_id = $RequestRenewCardt_order->user_id;
                $wallet->title = 'اعادة رصيد';
                $wallet->details = 'رقم الطلب: ' . $RequestRenewCardt_order->id;
                $wallet->total_price = $RequestRenewCardt_order->balance;
                $wallet->type = 0;
                $wallet->save();

                $wallet_j = new UserWallet();
                $wallet_j->user_id = 1;
                $wallet_j->total_price = $RequestRenewCardt_order->app_percent;
                $wallet_j->title = 'الغاء طلب تجديد شبكة';
                $wallet_j->details = 'رقم الطلب: ' . $RequestRenewCardt_order->id . ' واي فاي ' . $RequestRenewCardt_order->name . ' السعر: ' . $RequestRenewCardt_order->app_percent;
                $wallet_j->type = 1;
                $wallet_j->save();

                $Wellet_profit = Wellet_profit::where('id', $RequestRenewCardt_order->wellet_profit_id)->first();
                $Wellet_profit->delete();

                if (isset($RequestRenewCardt_order->selected_user_id) && is_numeric($RequestRenewCardt_order->selected_user_id))
                {
                    if(User_Wallet_Check_Balance($RequestRenewCardt_order->selected_user_id,$RequestRenewCardt_order->selected_user_reNewNetwork_percent) && User_Wallet_Check_Balance($store_user_id->Store->user_id,$RequestRenewCardt_order->network_user_reNewNetwork_percent))
                    {
                    $wallet_n = new UserWallet();
                    $wallet_n->user_id = $RequestRenewCardt_order->selected_user_id;
                    $wallet_n->total_price = $RequestRenewCardt_order->selected_user_reNewNetwork_percent;
                    $wallet_n->title = 'الغاء طلب تجديد شبكة';
                    $wallet_n->details = ' واي فاي ' . $RequestRenewCardt_order->name . ' السعر: ' . $RequestRenewCardt_order->selected_user_reNewNetwork_percent;
                    $wallet_n->type = 1;
                    $wallet_n->save();

                    $wallet_n = new UserWallet();
                    $wallet_n->user_id = $store_user_id;
                    $wallet_n->total_price = $RequestRenewCardt_order->network_user_reNewNetwork_percent;
                    $wallet_n->title = 'الغاء طلب تجديد شبكة';
                    $wallet_n->details = ' واي فاي ' . $RequestRenewCardt_order->name . ' السعر: ' . $RequestRenewCardt_order->network_user_reNewNetwork_percent;
                    $wallet_n->type = 1;
                    $wallet_n->save();
                    }
                    else
                    {
                        return redirect()->back()->with('error', __('احد المستفيدين من ارباح هذه الخدمة ليس لديه رصيد كافي في محفظته '));
                    }
                } else {
                    $wallet_n = new UserWallet();
                    $wallet_n->user_id = $store_user_id;
                    $wallet_n->total_price = $RequestRenewCardt_order->balance - $RequestRenewCardt_order->app_percent;
                    $wallet_n->title = 'الغاء طلب تجديد شبكة';
                    $wallet_n->details = ' واي فاي ' . $RequestRenewCardt_order->name . ' السعر: ' . $wallet_n->total_price;
                    $wallet_n->type = 1;
                    $wallet_n->save();
                }
            }
            else
            {
                return redirect()->back()->with('error', __('احد المستفيدين من ارباح هذه الخدمة ليس لديه رصيد كافي في محفظته '));
            }
        }

        $tokens = Token::where('user_id',$RequestRenewCardt_order->user_id)->pluck('fcm_token')->toArray();
        sendNotificationToUsers( $tokens,  'الغاء طلب تجديد شبكة : '.$RequestRenewCardt_order->balance." ".$message,'0','0' );
        $notifiy= New Notifiy();
        $notifiy->user_id = $RequestRenewCardt_order->user_id;
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
