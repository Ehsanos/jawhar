<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Person_mac;
use App\Models\Setting;
use Illuminate\Http\Request;

class person_mac_Controller extends Controller
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
        $Person_mac = Person_mac::all();
        return view('admin.Person_mac.home', [
            'Person_mac' => $Person_mac,
        ]);
    }

    public function create()
    {
        return view('admin.Person_mac.create');
    }

    public function edit($id)
    {

        $Person_mac = Person_mac::findOrFail($id);

        return view('admin.Person_mac.edit', [
            'Person_mac' => $Person_mac,
        ]);

    }

    public function store(Request $request)
    {
        $roles = [
            'person_name' => 'required',
            'mobile' => 'required|unique:person_mac',
            ];
        $this->validate($request, $roles);
        $Person_mac = new Person_mac();
        $Person_mac->person_name = $request->person_name;
        $Person_mac->mobile = $request->mobile;
        $Person_mac->save();
        return redirect()->back()->with('status', __('cp.create'));
    }

    public function update(Request $request, $id)
    {
        $roles = [
            'person_name' => 'required',
            'mobile' => 'required|unique:person_mac,mobile,' . $id,
            ];
        $this->validate($request, $roles);
        $Person_mac = Person_mac::query()->findOrFail($id);
        $Person_mac->person_name = $request->person_name;
        $Person_mac->mobile = $request->mobile;

        $Person_mac->save();
        return redirect()->back()->with('status', __('cp.create'));
    }


}
