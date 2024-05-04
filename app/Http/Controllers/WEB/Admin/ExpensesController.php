<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Language;
use App\Models\Setting;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use App\Models\Wellet_profit;
class ExpensesController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        $currency = isset($request->currency) && $request->currency == "dollar" ? "dollar" : "turkey";

        set_currency_now($currency);

        $adminId = auth()->guard('admin')->user()->id;
        if ($adminId != 1){
            $profit = Wellet_profit::query()->where("type",1)->where('worker_id',$adminId)->get();
        }
        else{

                $profit = Wellet_profit::query()->where("type",1)->get();
        }

        $cities = City::where('status','active')->get();
        return view('admin.Expenses.home',
        [
            'profit' => $profit  ,
            'cities' => $cities  ,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $currency = isset($request->currency) && $request->currency == "dollar" ? "dollar" : "turkey";

        set_currency_now($currency);

        $cities = City::where('status','active')->get();
        return view('admin.Expenses.create',[
            'cities'=>$cities,
            'currency'=>$currency,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $adminId = auth()->guard('admin')->user()->id;
        $roles = [
            'city_id' => 'required',
            'service_name' => 'required',
            'details' => 'required',
            'profit' => 'required|numeric',

        ];

        $currency = isset($request->currency) && $request->currency == "dollar" ? "dollar" : "turkey";

        set_currency_now($currency);

        $this->validate($request, $roles);

        $profit= new Wellet_profit();
        $profit->user_id = 1;
        $profit->city_id = $request->city_id;
        $profit->details = $request->details;
        $profit->profit = $request->profit;
        $profit->worker_id = $adminId;
        $profit->service_name = $request->service_name;
        $profit->type = 1;
        $profit->save();
        return redirect()->back()->with('status', __('cp.create'));
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id,Request $request)
    {
        $currency = isset($request->currency) && $request->currency == "dollar" ? "dollar" : "turkey";

        set_currency_now($currency);

        $cities = City::where('status','active')->get();

        $item = Wellet_profit::findOrFail($id);
        return view('admin.Expenses.edit', [
            'item' => $item,
            'cities'=>$cities,
            'currency' => $currency,
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
        $adminId = auth()->guard('admin')->user()->id;
        $roles = [
            'city_id' => 'required',
            'service_name' => 'required',
            'details' => 'required',
            'profit' => 'required|numeric',

        ];
        $currency = isset($request->currency) && $request->currency == "dollar" ? "dollar" : "turkey";

        set_currency_now($currency);

        $this->validate($request, $roles);
        $profit= Wellet_profit::query()->findOrFail($id);
        $profit->user_id = 1;
        $profit->city_id = $request->city_id;
        $profit->details = $request->details;
        $profit->profit = $request->profit;
        $profit->worker_id = $adminId;
        $profit->service_name = $request->service_name;
        $profit->type = 1;
        $profit->save();

        return redirect()->back()->with('status', __('cp.update'));
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
