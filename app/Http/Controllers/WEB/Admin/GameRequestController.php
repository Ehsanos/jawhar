<?php
namespace App\Http\Controllers\WEB\Admin;
use App\Models\Wellet_profit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Game;
use App\Models\GameRequest;
use App\Models\Token;
use App\Models\Notifiy;
use App\Models\UserWallet;
use App\Models\GameServies;




class GameRequestController extends Controller
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
        $items = GameRequest::query();


        $admin = auth('admin')->user();
        if ($admin->city_id > 0) {
            $items->where('city_id', $admin->city_id);
        }

        if ($request->has('status')) {
            if ($request->get('status') != null)
                $items->where('status', $request->get('status'));
        }

        $items = $items->orderBy('id', 'desc')->get();//paginate(20);
        return view('admin.gameRequest.home', [
            'items' => $items,
        ]);
    }

    public function edit($id)
    {
        $admin = auth('admin')->user();
        if ($admin->city_id > 0) {
            $order = GameRequest::where('city_id', $admin->city_id)->findOrFail($id);
        } else {
            $order = GameRequest::findOrFail($id);
        }
        return view('admin.gameRequest.edit', [
            'order' => $order,
        ]);

    }

    public function update(Request $request, $id)
    {
        $roles = [
            'status' => 'required',
        ];
        $this->validate($request, $roles);
        if ($request->status == "2")
        {
            if ($request->admin_response == "") {

                return redirect()->back()->withErrors('your must add message');
            }
        }
        $order = GameRequest::findOrFail($id);
        set_currency($order);
        $order->status = $request->status;
        $order->admin_response = $request->admin_response;
        $order->save();
        if ($request->status == 0)
        {
            $message = __('api.OrderIsPreparing');
        }
        elseif ($request->status == 1)
        {
            $message = __('api.OrderIsOnDelivery');
        }
        elseif ($request->status == 2)
        {
            $message = __('api.OrderIsComplete') .'  ' . $request->admin_response;
            $Wellet_profit = Wellet_profit::where('id', $order->wellet_profit_id)->first();
          if($Wellet_profit){
                         $Wellet_profit->status_wellet = 0;
            $Wellet_profit->created_at = Carbon::now()->format('YmdHis');
            $Wellet_profit->save(); 
          }

        }
        elseif ($request->status == 3)
        { $message = __('api.OrderIsCancel');
            if (User_Wallet_Check_Balance(1, $order->price))
            {
                $message = __('api.OrderIsCancel');
                if ($order->payment == 0)
                {
                    $wallet = new UserWallet();
                    $wallet->user_id = $order->user_id;
                    $wallet->order_id = $order->id;
                    $wallet->title = 'اعادة رصيد';
                    $wallet->details = 'رقم الطلب: ' . $order->id;
                    $wallet->total_price = $order->price;
                    $wallet->type = 0;
                    $wallet->save();

                    $game = GameServies::where('id', $order->servies_id)->first();
                    $wallet_J = new UserWallet();
                    $wallet_J->user_id = 1;
                    $wallet_J->order_id = $order->id;
                    $wallet_J->title = 'إلغاء طلبد';
                    $wallet_J->details = ' كود المستخدم: ' . $order->user_id . ' اسم الخدمة ' . $game->size . ' السعر ' . $order->price;
                    $wallet_J->total_price = $order->price;
                    $wallet_J->type = 1;
                    $wallet_J->save();

                    $Wellet_profit = Wellet_profit::where('id', $order->wellet_profit_id)->first();
                    $Wellet_profit->delete();
                }
                else
                {
                    return redirect()->back()->with('error', __('احد المستفيدين من ارباح هذه الخدمة ليس لديه رصيد كافي في محفظته '));
                }
            }
        }
        elseif ($request->status == 4)
        {   $message = __('api.refund');
            if (User_Wallet_Check_Balance(1, $order->price))
            {

                if ($order->payment == 0)
                {
                    $wallet = new UserWallet();
                    $wallet->user_id = $order->user_id;
                    $wallet->order_id = $order->id;
                    $wallet->title = 'اعادة رصيد';
                    $wallet->details = 'رقم الطلب: ' . $order->id;
                    $wallet->total_price = $order->price;
                    $wallet->type = 0;
                    $wallet->save();

                    $game = GameServies::where('id', $order->servies_id)->first();
                    $wallet_J = new UserWallet();
                    $wallet_J->user_id = 1;
                    $wallet_J->order_id = $order->id;
                    $wallet_J->title = 'إلغاء طلبد';
                    $wallet_J->details = ' كود المستخدم: ' . $order->user_id . ' اسم الخدمة ' . $game->size . ' السعر ' . $order->price;
                    $wallet_J->total_price = $order->price;
                    $wallet_J->type = 1;
                    $wallet_J->save();

                    $Wellet_profit = Wellet_profit::where('id', $order->wellet_profit_id)->first();
                    $Wellet_profit->delete();
                }
                if ($order->payment == 1)
                {
                    $wallet = new UserWallet();
                    $wallet->user_id = $order->user_id;
                    $wallet->order_id = $order->id;
                    $wallet->title = 'اعادة رصيد';
                    $wallet->details = 'رقم الطلب: ' . $order->id;
                    $wallet->total_price = $order->price;
                    $wallet->type = 0;
                    $wallet->save();

                    $game = GameServies::where('id', $order->servies_id)->first();
                    $wallet_J = new UserWallet();
                    $wallet_J->user_id = 1;
                    $wallet_J->order_id = $order->id;
                    $wallet_J->title = 'إلغاء طلبد';
                    $wallet_J->details = ' كود المستخدم: ' . $order->user_id . ' اسم الخدمة ' . $game->size . ' السعر ' . $order->price;
                    $wallet_J->total_price = $order->price;
                    $wallet_J->type = 1;
                    $wallet_J->save();
                    $Wellet_profit = Wellet_profit::where('id', $order->wellet_profit_id)->first();
                    $Wellet_profit->delete();
                }
            }
            else
            {
                return redirect()->back()->with('error', __('احد المستفيدين من ارباح هذه الخدمة ليس لديه رصيد كافي في محفظته '));
            }
        }

            $order_id = $id;
            $tokens = Token::where('user_id', $order->user_id)->pluck('fcm_token')->toArray();
            sendNotificationToUsers( $tokens,$message,"2",$id );
            $notifiy = new Notifiy();
            $notifiy->user_id = $order->user_id;
            $notifiy->order_id = $order_id;
            $notifiy->message = $message;
            $notifiy->save();
            set_currency("");
            return redirect()->back()->with('status', __('cp.update'));
        }
}
