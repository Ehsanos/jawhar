<?php


namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Payment;
use App\Models\Wallet;
use App\Models\Notify;
use App\Models\Token;

class PaymentsController extends Controller
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
    public function index()
    {
        //
        $items = Payment::with('user')->orderByDesc('id')->get();

        return view('admin.payments.home', [
            'items' => $items,
        ]);
    }


    public function show($id)
    {
        //
        $item = Payment::findOrFail($id);
    }


    public function edit($id)
    {

        $item = Payment::with('user')->findOrFail($id);
        return view('admin.payments.edit',['item'=>$item]);
    }



    public function update(Request $request, $id)
    {
            $locales = Language::all()->pluck('lang');
            $item = Payment::findOrFail($id);
            $item->status = $request->status;
            $item->save();
            
                        if($request->status == 3) {
       $message3 =  __('cp.paymentCancel');
        $tokens = Token::where('user_id',$item->user_id)->pluck('fcm_token')->toArray();
        sendNotificationToUsers($tokens,$message3,'0','0');
        $notify = new Notify();
        $notify->user_id = $item->user_id;
        $notify->messag_type = 0;
       // $notify->message = $message3;
                       foreach ($locales as $locale)
        {
            $notify->translateOrNew($locale)->title = __('cp.withdraw',[],$locale);
            $notify->translateOrNew($locale)->message =  __('cp.paymentCancel',[],$locale);
        }
        $notify->save();
            }

            if($request->status == 1) {
       $message1 =  __('cp.paymentProgress');
        $tokens = Token::where('user_id',$item->user_id)->pluck('fcm_token')->toArray();
        sendNotificationToUsers($tokens,$message1,'0','0');
        $notify = new Notify();
        $notify->user_id = $item->user_id;
        $notify->messag_type = 0;
        
      //  $notify->message = $message1;
                foreach ($locales as $locale)
        {
            $notify->translateOrNew($locale)->title = __('cp.withdraw',[],$locale);
            $notify->translateOrNew($locale)->message =  __('cp.paymentProgress',[],$locale);
        }      
        $notify->save();
            }

            if($request->status == 2) {
       $wallet = new Wallet();
       $wallet->user_id = $item->user_id;
       $wallet->type =2;// outcome
       $wallet->amount =$item->amount;
       $wallet->save();
       $message2 =  __('cp.paymentSend');
        $tokens = Token::where('user_id',$item->user_id)->pluck('fcm_token')->toArray();
        sendNotificationToUsers($tokens,$message2,'0','0');
        $notify = new Notify();
        $notify->user_id = $item->user_id;
       // $Notify->message = $message2;
                foreach ($locales as $locale)
        {
            $notify->translateOrNew($locale)->title = __('cp.withdraw',[],$locale);
            $notify->translateOrNew($locale)->message =  __('cp.paymentSend',[],$locale);
        }
        $notify->save();
            }

        return redirect()->back()->with('status', __('cp.update'));
    }


    public function destroy($id)
    {

        $item = News::query()->findOrFail($id);
        if ($item) {
            News::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }
}
