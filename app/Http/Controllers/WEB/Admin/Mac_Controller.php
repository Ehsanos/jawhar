<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Mac;
use App\Models\Person_mac;
use App\Models\Setting;
use Illuminate\Http\Request;

class Mac_Controller extends Controller
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
        $mac =Mac::all();
        return view('admin.Macs.home', [
            'mac' => $mac,
        ]);
    }

    public function create()
    {
        $Person_mac = Person_mac::orderBy('id', 'desc')->get();
        return view('admin.Macs.create',['Person_mac'=>$Person_mac]);
    }




    public function edit($id)
    {
        $Person_mac = Person_mac::all();
        $mac = Mac::findOrFail($id);
        return view('admin.Macs.edit', [
            'mac' => $mac,
            'Person_mac' => $Person_mac,
        ]);

    }

    public function store(Request $request)
    {
        $roles = [
            'mac' => 'required|unique:macs',
            'person_id' => 'required',
            'password' => 'required',
        ];
        $this->validate($request, $roles);

        $mac = new Mac();
        $mac->mac = $request->mac;
        $mac->person_id = $request->person_id;
        $mac->password = $request->password;
        $mac->save();
        return redirect()->back()->with('status', __('cp.create'));
    }

    public function update(Request $request, $id)
    {
        $roles = [
            'mac' => 'required|unique:macs,mac,'.$id,
            'person_id' => 'required',
            'password' => 'required',
        ];
        $this->validate($request, $roles);
        $mac = Mac::query()->findOrFail($id);
        $mac->mac = $request->mac;
        $mac->person_id = $request->person_id;
        $mac->password = $request->password;
        $mac->save();
        return redirect()->back()->with('status', __('cp.create'));
    }



}
