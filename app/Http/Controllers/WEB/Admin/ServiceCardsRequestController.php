<?php

namespace App\Http\Controllers\WEB\Admin;


use App\Models\GameRequest;
use App\Models\Language;
use App\Models\ServiceCardsRequest;
use App\Models\MobileCompany;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use Auth;
class ServiceCardsRequestController extends Controller
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
    public function index(Request $request)
    {
        $items1 = GameRequest::query()->select("id","user_id","city_id","created_at")->where('status', '2')
            ->selectSub(function ($query) {
                $query->selectRaw('1');
            }, 'type');

        $admin = auth('admin')->user();
        if ($admin->city_id > 0){
            $items1->where('city_id',$admin->city_id);
        }
        //-------------------------

        $items = ServiceCardsRequest::query()->select("id","user_id","city_id","created_at")
            ->selectSub(function ($query) {
                $query->selectRaw('2');
            }, 'type');

        $admin = auth('admin')->user();
        if ($admin->city_id > 0){
            $items->where('city_id',$admin->city_id);
        }
        //---------------------------

        $items11 = $items->unionAll($items1)->get();

        return view('admin.soldServices.home', [
            'items' => $items11,
        ]);
    }

    public function edit($id)
    {
           $admin = auth('admin')->user();
                   if ($admin->city_id > 0){
           $item=ServiceCardsRequest::where('city_id',$admin->city_id)->findOrFail($id);
        }
        else{
            $item=ServiceCardsRequest::findOrFail($id);
        }
        return view('admin.ServiceCardsRequest.edit', [
            'item' => $item ,
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


          //  'type'   => 'required',
       //     'image' => 'image|mimes:jpeg,jpg,png',

        ];

        $this->validate($request, $roles);


        $item = ServiceCardsRequest::query()->findOrFail($id);

        $item->action=$request->action;
        $item->save();


        return redirect()->back()->with('status', __('cp.update'));
    }


    public function destroy($id)
    {
        //
        $item = Azkar::query()->findOrFail($id);
        if ($item) {
            Azkar::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }
    
    

}
