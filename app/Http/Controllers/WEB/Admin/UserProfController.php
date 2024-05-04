<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\capital;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\UserWallet;
use App\Models\Wellet_profit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserProfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $currency = isset($request->currency) && $request->currency == "dollar" ? "dollar" : "turkey";

        set_currency_now($currency);

        $all_sum = 0;

        $setting = Setting::query()->first();

        $real_leaders = collect();

        //اضافة ثلاث كولومات في اليوزر
        // اضافة كولمين في ستينج
        $data = User::query()
            ->where("user_prof", "!=", null)
            ->orwhere("user_prof", "!=", "")
            ->get();

        $leaders = collect();

        foreach ($data as $one) {

            $user = User::where("user_code", $one->user_prof)->first();

            $leaders->push($user);
        }

        $leaders = $leaders->unique();

        foreach ($leaders as $leader) {
            $childrens = User::where("user_prof", $leader->user_code)->get();

            $rows = [];

            foreach ($childrens as $child) {
                $row = [];

                $all_profit_child = Wellet_profit::where("user_id", $child->id)
                    ->where("type", 0);

                if($currency == "turkey")
                    if (isset($child->last_wellet_profit_turkey) && is_numeric($child->last_wellet_profit_turkey))
                        $all_profit_child = $all_profit_child->where('id', '>', $child->last_wellet_profit_turkey);

                if($currency == "dollar")
                    if (isset($child->last_wellet_profit_dollar) && is_numeric($child->last_wellet_profit_dollar))
                        $all_profit_child = $all_profit_child->where('id', '>', $child->last_wellet_profit_dollar);

                $all_profit_child = $all_profit_child->sum('profit');
                //dd(app(Wellet_profit::class)->getTable());

                $row["child"] = $child;

                if ($all_profit_child > 0) {
                    $row["child_profit"] = $all_profit_child;
                    $row["leader_profit"] = ($row["child_profit"] * $setting->leader_profit) / 100;

                }

                $rows[] = $row;
            }

            $leader->rows = $rows;

            $sum = 0;
            foreach ($leader->rows as $o) {
                $sum += $o["leader_profit"];
            }

            $leader->sum = $sum;

            $all_sum += $sum;

            if($leader->sum > 0)
                $real_leaders->push($leader);

        }

        return view('admin.user_prof.home', [
            'leaders' => $real_leaders,
            'setting' => $setting,
            'all_sum' => $all_sum,
            'currency' => $currency,
            'leader_profit_status' => $setting->leader_profit_status,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        set_currency_now($request->currency);

        $last_id = Wellet_profit::query()->latest()->first()->id;

        $i = 0;

        foreach ($request->ids as $user_id) {

            $user = User::where("id",$user_id)->first();

            $wallet = new UserWallet();
            $wallet->user_id = $user_id;
            $wallet->total_price = $request->values[$i];
            $wallet->title = " ربح من جوهر ";
            $wallet->details = " ربح من جوهر ".$request->values[$i];
            $wallet->type = 0;
            $wallet->save();

            $wallet_J = new UserWallet();
            $wallet_J->user_id = 1;
            $wallet_J->total_price = $request->values[$i];
            $wallet_J->title = " سحب ربح لللاعب ".$user->name;
            $wallet_J->details = " سحب ربح لللاعب ".$user->name." وهو ".$request->values[$i];
            $wallet_J->type = 1;
            $wallet_J->save();

            $profit= new Wellet_profit();
            $profit->user_id = $user_id;
            $profit->city_id = 6;
            $profit->details = " سحب ربح لللاعب ".$user->name." وهو ".$request->values[$i];
            $profit->profit = $request->values[$i];
            $profit->worker_id = 1;
            $profit->service_name = 4;
            $profit->type = 1;
            $profit->save();

            $childrens = User::where("user_prof",$user->user_code)->get();

            foreach ($childrens as $child)
            {
                if($request->currency == "turkey")
                    $child->last_wellet_profit_turkey = $last_id;

                if($request->currency == "dollar")
                    $child->last_wellet_profit_dollar = $last_id;

                $child->save();
            }

            ++$i;

        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\capital $capital
     * @return \Illuminate\Http\Response
     */
    public function show(capital $capital)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\capital $capital
     * @return \Illuminate\Http\Response
     */
    public function edit(capital $capital)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\capital $capital
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, capital $capital)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\capital $capital
     * @return \Illuminate\Http\Response
     */
    public function destroy(capital $capital)
    {
        //
    }
}