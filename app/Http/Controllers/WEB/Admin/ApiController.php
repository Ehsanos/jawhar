<?php
namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Image;
use App\Models\Language;
use App\Models\Setting;
use App\Models\Api;


class ApiController extends Controller
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
    public function index()
    {
        $apis = Api::orderBy('id', 'desc')->get();
        return view('admin.apis.home', [
            'apis' =>$apis,
        ]);
    }


    public function edit($id)
    {
        //
        $item = Api::findOrFail($id);
        return view('admin.apis.edit', [
            'item' => $item,
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
        //
        $roles = [
            'token' => 'required',
        ];

        $this->validate($request, $roles);

        $ad = Api::query()->findOrFail($id);
        $ad->token = $request->get('token');

        $ad->save();
        return redirect()->back()->with('status', __('cp.update'));
    }

 
}
