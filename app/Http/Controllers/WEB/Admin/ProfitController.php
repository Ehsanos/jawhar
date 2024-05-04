<?php

namespace App\Http\Controllers\WEB\Admin;


use App\Models\Admin;

use App\Models\City;
use App\Models\UserWallet;
use App\Models\wallet_profits_dollar;
use App\Models\Wellet_profit;

use App\Models\WhatsappUsers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NewPostNotification;

class ProfitController extends Controller
{
    public function index(Request $request)
    {
        set_currency_now($request->currency);
        //dd(app(UserWallet::class)->getTable());
        $expenses = $this->get_Wellet_expenses($request);
        $data = $this->get_Wellet_profit($request);
        $data_all = $this-> get_Wellet_profit_whth_active($request);
        set_currency_now("");
        return view('admin.profit.home', [
            'expenses' => $expenses  ,
            'data' => $data  ,
           'data_all' =>$data_all
        ]);
    }
    public function get_Wellet_expenses(Request $request)
    {
        $data = [];

        if($request->has("type") && $request->type == 0)
        {
            $profit = Wellet_profit::query()->where("delete_col" ,0);
            //dd(app(Wellet_profit::class)->getTable());
            $profit->where("type",1);

            if($request->has("start") && $request->start != "" && $request->has("end") && $request->end != "" )
            {
                $start = Carbon::createFromFormat('m/d/Y', $request->start)->format('Y-m-d');
                $end = Carbon::createFromFormat('m/d/Y', $request->end)->format('Y-m-d');
                $profit->whereBetween('created_at', [$start." 00:00:00", $end." 23:59:59"]);
            }

            if($request->has("city_id") && $request->city_id != "")
            {
                $profit->where("city_id",$request->city_id);
            }

            if($request->has("worker_id") && $request->worker_id != "")
            {
                $profit->where("worker_id",$request->worker_id);
            }

            if($request->has("service_name") && $request->service_name != "")
            {
                $profit->where("service_name",$request->service_name);
            }

            $profit = $profit->where('status_wellet',0)->get();

            $profit_sum = 0;
            $profit_worker_sum = 0;
            $capital_sum = 0;

            foreach ($profit as $pro)
            {
                $profit_sum += $pro->profit;
                $profit_worker_sum += $pro->worker_profit;
                $capital_sum += $pro->purchasing_price;
            }
            $profit_sum=number_format($profit_sum, 2, '.', '');
            $profit_worker_sum=number_format($profit_worker_sum, 2, '.', '');
            $capital_sum=number_format($capital_sum, 2, '.', '');

            $cities =Wellet_profit::query();

            if($request->has("type") && $request->type != "")
            {
                $cities->where("type",$request->type);
            }
            $cities = $cities->select('city_id')->distinct()->get();

            $workers =Wellet_profit::where("worker_id","!=",null);

            if($request->has("type") && $request->type != "")
            {
                $workers->where("type",$request->type);
            }

            $workers = $workers->select('worker_id')->distinct()->get();

            $data["profit"] = $profit;
            $data["cities"] = $cities;
            $data["workers"] = $workers;
            $data["profit_sum"] = $profit_sum;
            $data["profit_worker_sum"] = $profit_worker_sum;
            $data["capital_sum"] = $capital_sum;

        }


        return $data;
    }
    public function get_Wellet_profit(Request $request)
    {
        $data = [];

        $profit = Wellet_profit::query()->where("delete_col" ,0);

        if($request->has("type") && $request->type != "")
        {
            $profit->where("type",$request->type);
        }

        if($request->has("start") && $request->start != "" && $request->has("end") && $request->end != "" )
        {
            $start = Carbon::createFromFormat('m/d/Y', $request->start)->format('Y-m-d');
            $end = Carbon::createFromFormat('m/d/Y', $request->end)->format('Y-m-d');
            $profit->whereBetween('created_at', [$start." 00:00:00", $end." 23:59:59"]);
        }

        if($request->has("city_id") && $request->city_id != "")
        {
            $profit->where("city_id",$request->city_id);
        }

        if($request->has("worker_id") && $request->worker_id != "")
        {
            $profit->where("worker_id",$request->worker_id);
        }

        if($request->has("service_name") && $request->service_name != "")
        {
            $profit->where("service_name",$request->service_name);
        }

        $profit = $profit->where('status_wellet',0)->get();

        $profit_sum = 0;
        $profit_worker_sum = 0;
        $capital_sum = 0;

        foreach ($profit as $pro)
        {
            $profit_sum += $pro->profit;
            $profit_worker_sum += $pro->worker_profit;
            $capital_sum += $pro->purchasing_price;
        }
        $profit_sum=number_format($profit_sum, 2, '.', '');
        $profit_worker_sum=number_format($profit_worker_sum, 2, '.', '');
        $capital_sum=number_format($capital_sum, 2, '.', '');


        $cities =Wellet_profit::query();

        if($request->has("type") && $request->type != "")
        {
            $cities->where("type",$request->type);
        }
        $cities = $cities->select('city_id')->distinct()->get();

        $workers =Wellet_profit::where("worker_id","!=",null);

        if($request->has("type") && $request->type != "")
        {
            $workers->where("type",$request->type);
        }

        $workers = $workers->select('worker_id')->distinct()->get();

        $data["profit"] = $profit;
        $data["cities"] = $cities;
        $data["workers"] = $workers;
        $data["profit_sum"] = $profit_sum;
        $data["profit_worker_sum"] = $profit_worker_sum;
        $data["capital_sum"] = $capital_sum;

        return $data;
    }
    public function get_Wellet_profit_whth_active(Request $request)
    {
        $data = [];

        $profit = Wellet_profit::query()->where("delete_col" ,0);

        if($request->has("type") && $request->type != "")
        {
            $profit->where("type",$request->type);
        }

        if($request->has("start") && $request->start != "" && $request->has("end") && $request->end != "" )
        {
            $start = Carbon::createFromFormat('m/d/Y', $request->start)->format('Y-m-d');
            $end = Carbon::createFromFormat('m/d/Y', $request->end)->format('Y-m-d');
            $profit->whereBetween('created_at', [$start." 00:00:00", $end." 23:59:59"]);
        }

        if($request->has("city_id") && $request->city_id != "")
        {
            $profit->where("city_id",$request->city_id);
        }

        if($request->has("worker_id") && $request->worker_id != "")
        {
            $profit->where("worker_id",$request->worker_id);
        }

        if($request->has("service_name") && $request->service_name != "")
        {
            $profit->where("service_name",$request->service_name);
        }

        $profit = $profit->get();

        $profit_sum = 0;
        $profit_worker_sum = 0;
        $capital_sum = 0;

        foreach ($profit as $pro)
        {
            $profit_sum += $pro->profit;
            $profit_worker_sum += $pro->worker_profit;
            $capital_sum += $pro->purchasing_price;
        }
        $profit_sum=number_format($profit_sum, 2, '.', '');
        $profit_worker_sum=number_format($profit_worker_sum, 2, '.', '');
        $capital_sum=number_format($capital_sum, 2, '.', '');


        $cities =Wellet_profit::query();

        if($request->has("type") && $request->type != "")
        {
            $cities->where("type",$request->type);
        }
        $cities = $cities->select('city_id')->distinct()->get();

        $workers =Wellet_profit::where("worker_id","!=",null);

        if($request->has("type") && $request->type != "")
        {
            $workers->where("type",$request->type);
        }

        $workers = $workers->select('worker_id')->distinct()->get();

        $data["profit"] = $profit;
        $data["cities"] = $cities;
        $data["workers"] = $workers;
        $data["profit_sum"] = $profit_sum;
        $data["profit_worker_sum"] = $profit_worker_sum;
        $data["capital_sum"] = $capital_sum;
        //dd(app(Wellet_profit::class)->getTable());
        return $data;
    }
    public function reset(Request $request)
    {
        $profit = $request->profit_id;
        $expenses_id = $request->expenses_id;
        $profit= rtrim($profit, ",");
        $expenses_id= rtrim($expenses_id, ",");
        $profit_totel = $request->profit_totel;
        $profit_capital=$request->profit_capital;
        $profit_worker=$request->profit_worker;

        $totel_profit=$profit_totel+$profit_capital;

//        $wallet_j = new UserWallet();
//        $wallet_j->user_id = 1;
//        $wallet_j->total_price = $totel_profit;
//        $wallet_j->title = 'عملية تصفير';
//        $wallet_j->details =  ' ارسال رصيد من جوهر الى الحساب الوهمي ';
//        $wallet_j->type = 1;
//        $wallet_j->save();
//
//        $wallet_c = new UserWallet();
//        $wallet_c->user_id = 2212;
//        $wallet_c->total_price = $totel_profit;
//        $wallet_c->title = 'عملية تصفير';
//        $wallet_c->details = 'استلام رصيد من جوهر على الحساب الوهمي ';
//        $wallet_c->type = 0;
//        $wallet_c->save();

        $profit_totel  =  $profit_totel / $request->is_dollar;
        $profit_capital  =  $profit_capital / $request->is_dollar;
        $profit_worker  =  $profit_worker / $request->is_dollar;

        $wallet_profits_dollar=new wallet_profits_dollar();
        $wallet_profits_dollar->user_id=1;
        $wallet_profits_dollar->profit = $profit_totel;
        $wallet_profits_dollar->purchasing_price= $profit_capital;
        $wallet_profits_dollar->details= 'تحويل رصيد من محفظة التركي الى الدولار';
        $wallet_profits_dollar->type =0;
        $wallet_profits_dollar->city_id =6;
        $wallet_profits_dollar->worker_profit =$profit_worker;
        $wallet_profits_dollar->service_name =9;
        $wallet_profits_dollar->status_wellet = 0;
        $wallet_profits_dollar->save();
        $wallet_profits_dollar->id;



        foreach (explode(',',$profit) as $item)
       {
           $Wellet_profit = Wellet_profit::where("id",$item)->first();
           $Wellet_profit->reset_wallet_id =   $wallet_profits_dollar->id;
           $Wellet_profit->delete_col = 1;
           $Wellet_profit->save();
         }
        if ($expenses_id !='')
        {
            foreach (explode(',',$expenses_id) as $item)
            {
                $Wellet_profit = Wellet_profit::where("id",$item)->first();
                $Wellet_profit->reset_wallet_id =   $wallet_profits_dollar->id;
                $Wellet_profit->delete_col = 1;
                $Wellet_profit->save();
            }
        }


        return redirect()->back()->with('status', __('تم التصفير بنجاح'));
    }

    public function index1(Request $request)
    {
        $adminId = auth()->guard('admin')->user()->id;
        if ($adminId != 1){
            $profit = Wellet_profit::query()->where("type",0)->where("manual_addition",1)->where("delete_col" ,0)->where('worker_id',$adminId)->get();
        }
        else{

            $profit = Wellet_profit::query()->where("type",0)->where("manual_addition",1)->where("delete_col" ,0)->get();
        }
        return view('admin.Profit_Turkey.home',
            [
                'profit' => $profit  ,
            ]);
    }


    public function create()
    {
        $workers = WhatsappUsers::all();
        $user = User::orderBy('id', 'desc')->get();
        $cities = City::where('status','active')->get();
        return view('admin.Profit_Turkey.create',['cities'=>$cities, 'user'=>$user,'workers'=>$workers]);
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
            'user_id' => 'required',
            'city_id' => 'required',
            'service_name' => 'required',
            'details' => 'required',
            'profit' => 'required',
        ];
        $this->validate($request, $roles);

        $profit = new Wellet_profit();
        $profit->user_id = $request->user_id;
        $profit->details = $request->details;
        $profit->profit = $request->profit;
        $profit->purchasing_price = $request->purchasing_price;
        $profit->worker_id = $request->worker_id;
        $profit->worker_profit = $request->worker_profit;
        $profit->city_id = $request->city_id;
        $profit->service_name = $request->service_name;
        $profit->manual_addition = 1;
        $profit->type = 0;
        $profit->save();
        return redirect()->back()->with('status', __('cp.create'));
    }


    public function edit($id)
    {
        $cities = City::where('status','active')->get();
        $item = Wellet_profit::findOrFail($id);
        $whatsapp = WhatsappUsers::all();
        $user = User::orderBy('id', 'desc')->get();
        return view('admin.Profit_Turkey.edit', [
            'item' => $item,
            'cities'=>$cities,
            'user'=>$user,
            'whatsapp'=> $whatsapp,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\wallet_profits_dollar  $wallet_profits_dollar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $roles = [
            'user_id' => 'required',
            'city_id' => 'required',
            'service_name' => 'required',
            'details' => 'required',
            'profit' => 'required',
        ];
        $this->validate($request, $roles);

        $profit = Wellet_profit::query()->findOrFail($id);

        $profit->user_id = $request->user_id;
        $profit->details = $request->details;
        $profit->profit = $request->profit;
        $profit->purchasing_price = $request->purchasing_price;
        $profit->worker_id = $request->worker_id;
        $profit->worker_profit = $request->worker_profit;
        $profit->city_id = $request->city_id;
        $profit->service_name = $request->service_name;
        $profit->manual_addition = 1;
        $profit->type = 0;
        $profit->save();
        return redirect()->back()->with('status', __('cp.create'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\wallet_profits_dollar  $wallet_profits_dollar
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }
}
