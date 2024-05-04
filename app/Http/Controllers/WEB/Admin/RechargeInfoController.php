<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Setting;
use App\Models\Recharge_info;
use Illuminate\Http\Request;

class RechargeInfoController extends Controller
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
        $Recharge = Recharge_info::orderBy('id', 'desc')->get();
        return view('admin.RechargeInfo.home', [
            'Recharge' => $Recharge ,
        ]);
    }
    public function create()
    {
        return view('admin.RechargeInfo.create');
    }
    public function edit($id)
    {
        $Recharge = Recharge_info::findOrFail($id);
        return view('admin.RechargeInfo.edit',[
            'item' => $Recharge,
        ]);
    }
    public function store(Request $request)
    {
        $roles = [
            'title' => 'required',
            'details' => 'required',
        ];
        $this->validate($request, $roles);
        $Recharge= new Recharge_info();
        $Recharge->title = $request->title;
        $Recharge->details = $request->details;
        $Recharge->save();
        return redirect()->back()->with('status', __('cp.create'));
    }
    public function update(Request $request, $id)
    {
        $roles = [
            'title' => 'required',
            'details' => 'required',
        ];
        $this->validate($request, $roles);
        $Recharge = Recharge_info::query()->findOrFail($id);
        $Recharge->title = $request->get('title');
        $Recharge->details = $request->get('details');
        $Recharge->save();
        return redirect()->back()->with('status', __('cp.update'));
    }
}
