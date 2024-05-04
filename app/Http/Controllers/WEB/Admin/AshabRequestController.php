<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\AshabRequest;
use App\Models\Language;
use App\Models\Notify;
use App\Models\Setting;
use App\Models\Token;
use App\Models\UserWallet;
use App\Models\Wellet_profit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AshabRequestController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {

        $ashab_request = AshabRequest::orderBy('id', 'desc')->paginate(15);

       foreach($ashab_request as $key=>$one_req)
       {

           $data_from_ashab = getAshabOrderId($one_req->order_id);
           if(isset($data_from_ashab->order_status))
           {
               $one_req->status_from_ashab = $data_from_ashab->order_status;
               $one_req->order_product = $data_from_ashab->order_product;

           }
           else
           {
               $one_req->order_product =  "خطأ order_id";
               $one_req->status_from_ashab = "خطأ order_id";
           }

       }


        return view('admin.AshabRequest.home', [  'ashab_request' => $ashab_request  ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(isset($request->ashab_id) && $request->ashab_id != "") {
            $AshabRequest = AshabRequest::findOrFail($request->ashab_id);

            $data_from_ashab = getAshabOrderId($AshabRequest->order_id);
            if (isset($data_from_ashab->order_status)) {
                $AshabRequest->status_from_ashab = $data_from_ashab->order_status;
                foreach ($data_from_ashab->items as $key => $value) {
                    foreach ($value as $keyy => $valuee) {
                        $AshabRequest->text .= $keyy . ":" . $valuee . "\n";
                    }
                }
            } else {
                $AshabRequest->status_from_ashab = "خطأ order_id";
                $AshabRequest->text = "خطأ order_id";
            }
            return view('admin.AshabRequest.create', [
                'AshabRequest' => $AshabRequest
            ]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $roles = [
            'id' => 'required',
        ];
        $this->validate($request, $roles);

        $AshabRequest =AshabRequest::findOrFail($request->id);

        if($request->status == 0 )
        {
            $notify = new Notify();
            $notify->user_id = $AshabRequest->user_id;
            $notify->order_id = 0;
            $notify->messag_type = 0;
            $notify->message = " رقم الطلب " .  $AshabRequest->order_id ."\n". " النتيجة " .$request->my_text;
            $notify->save();


            $message = " رقم الطلب " .  $AshabRequest->order_id ."\n". " النتيجة " .$request->my_text;
            $tokens = Token::where('user_id',$AshabRequest->user_id)->pluck('fcm_token')->toArray();
            sendNotificationToUsers( $tokens,$message,"2",$AshabRequest->order_id );

            return redirect()->back()->with('status', "تم الارسال بنجاح");

        }
        else
        {
            return redirect()->back()->with('status', "تم ارسال الرد مسبقاً أو تم الغاء الطلب");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {
        $AshabRequest =AshabRequest::findOrFail($id);

        $data_from_ashab = getAshabOrderId($AshabRequest->order_id);
        if(isset($data_from_ashab->order_status))
        {
            $AshabRequest->status_from_ashab = $data_from_ashab->order_status;
        }
        else
        {
            $AshabRequest->status_from_ashab = "خطأ order_id";
        }
        return view('admin.AshabRequest.edit', [
            'AshabRequest' => $AshabRequest
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $roles = [
            'status' => 'required',
        ];
        $this->validate($request, $roles);

        $AshabRequest =AshabRequest::findOrFail($id);
        set_currency($AshabRequest);
        if($request->status == 1  && $AshabRequest->replay == ""){
            if(User_Wallet_Check_Balance(1,$AshabRequest->price)) {
                //customer
                $wallet_c = new UserWallet();
                $wallet_c->user_id = $AshabRequest->user_id;
                $wallet_c->total_price = $AshabRequest->price;
                $wallet_c->title = 'تم الغاء الطلب';
                $wallet_c->details = ' رقم الطلب ' . $AshabRequest->order_id . ' السعر ' . $AshabRequest->price;
                $wallet_c->type = 0;
                $wallet_c->save();

                //jawhar
                $wallet_j = new UserWallet();
                $wallet_j->user_id = 1;
                $wallet_j->total_price = $AshabRequest->price;
                $wallet_j->title = 'الغاء منتج من خدمات جوهر ';
                $wallet_j->details = ' رقم الطلب ' . $AshabRequest->order_id . ' السعر ' . $AshabRequest->price;
                $wallet_j->type = 1;
                $wallet_j->save();

                $Wellet_profit = Wellet_profit::where('id', $AshabRequest->wellet_profit_id)->first();
                $Wellet_profit->delete();

                $notify = new Notify();
                $notify->user_id = $AshabRequest->user_id;
                $notify->order_id = 0;
                $notify->messag_type = 0;
                $notify->message = "تم الغاء الطلب" . "\n" . " رقم الطلب " . $AshabRequest->order_id . " السعر " . $AshabRequest->price;
                $notify->save();

                $message = "تم الغاء الطلب" . "\n" . " رقم الطلب " . $AshabRequest->order_id . " السعر " . $AshabRequest->price;
                $tokens = Token::where('user_id', $AshabRequest->user_id)->pluck('fcm_token')->toArray();
                sendNotificationToUsers($tokens, $message, "2", $AshabRequest->order_id);

                $AshabRequest->status = 1;
                $AshabRequest->replay = "تم رفض  الطلب";
                $AshabRequest->save();
            }else
            {
                return redirect()->back()->with('error', __('احد المستفيدين من ارباح هذه الخدمة ليس لديه رصيد كافي في محفظته '));
            }

            return redirect()->back()->with('status', "تم الغاء الطلب بنجاح");
        }
        elseif($request->status == 0  && $AshabRequest->replay == "")
        {
            $Wellet_profit = Wellet_profit::where('id',$AshabRequest->wellet_profit_id)->first();
            $Wellet_profit->status_wellet=0;
            $Wellet_profit->created_at= Carbon::now()->format('YmdHis');
            $Wellet_profit->save();

            $AshabRequest->status = 0;
            $AshabRequest->replay = "تم الموافقة";
            $AshabRequest->save();

            return redirect()->back()->with('status', "تم قبول الطلب ");
        }
        set_currency("");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
