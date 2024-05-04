<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\MobileCompany;
use App\Models\MobileNetworkPackages;
use App\Models\Setting;
use Illuminate\Http\Request;

class MobileNetworkPackagesController extends Controller
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
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
       $mobilenetworkpackages = MobileNetworkPackages::orderBy('id', 'desc')->get();
        return view('admin.MobileNetworkPackages.home', [
            'mobilenetworkpackages' => $mobilenetworkpackages ,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $cobilecompany = MobileCompany::orderBy('id', 'desc')->get();
        return view('admin.MobileNetworkPackages.create',['cobilecompany'=>$cobilecompany]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $roles = [
            'name' => 'required',
            'mobile_companies_id' => 'required',
            'price' => 'required',
            'purchasing_price' => 'required',
            'is_dollar' => 'required',
        ];
        $this->validate($request, $roles);

        $mobilenetworkpackages= new MobileNetworkPackages();
        $mobilenetworkpackages->name = $request->name;
        $mobilenetworkpackages->mobile_companies_id = $request->mobile_companies_id;
        $mobilenetworkpackages->price = $request->price;
        $mobilenetworkpackages->purchasing_price = $request->purchasing_price;
        $mobilenetworkpackages->is_dollar =$request->is_dollar;
        $mobilenetworkpackages->save();
        return redirect()->back()->with('status', __('cp.create'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show($id)
    {
       //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {
        $cobilecompany = MobileCompany::orderBy('id', 'desc')->get();

        $item = MobileNetworkPackages::findOrFail($id);
        return view('admin.MobileNetworkPackages.edit', [
            'item' => $item,
            'cobilecompany'=>$cobilecompany,
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
        $roles = [
            'name' => 'required',
            'mobile_companies_id' => 'required',
            'price' => 'required',
            'purchasing_price' => 'required',
            'is_dollar' => 'required',
        ];
        $locales = Language::all()->pluck('lang');

        $this->validate($request, $roles);

        $mobilenetworkpackages = MobileNetworkPackages::query()->findOrFail($id);
        $mobilenetworkpackages->name = $request->name;
        $mobilenetworkpackages->mobile_companies_id = $request->mobile_companies_id;
        $mobilenetworkpackages->price = $request->price;
        $mobilenetworkpackages->purchasing_price = $request->purchasing_price;
        $mobilenetworkpackages->is_dollar =$request->is_dollar;
        $mobilenetworkpackages->save();
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
