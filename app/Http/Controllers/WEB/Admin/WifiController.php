<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\UserPermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WEB\Admin;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Wifi;
use App\Models\City;
use App\Models\Token;
use App\Models\Notify;
use App\Models\EnableNotificationNetwork;

class WifiController extends Controller
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



    public function index($my_store_id= null)
    {
        $wifi = Wifi::query();
        $cities = City::all();
        $admin = auth('admin')->user();
        $admin_perm = UserPermission::where('user_id',$admin->id)->first();
        if($my_store_id != null)
        {
            $wifi->where('store_id',$my_store_id);
        }
        if ($admin->city_id > 0){
            $wifi->where('city_id',$admin->city_id);

            if (isset($admin_perm->store_id) && $admin_perm->store_id > 0)
            {
                $lolo = Wifi::where("store_id",$admin_perm->store_id)->first();
                if(isset($lolo->id)){
                    $wifi->where('id',$lolo->id);
                }

            }

        }

        $wifi = $wifi->get();
        return view('admin.wifi.home', [
            'wifi' => $wifi,
            'city' => $cities,


        ]);
    }

    public function create()
    {
//        $cities = City::where('status','active')->get();
//        return view('admin.wifi.create',[
//            'cities' =>$cities,
//        ]);
    }

    public function store(Request $request)
    {
//         $roles = [
//
//            'name'   => 'required',
//            'city_id'   => 'required',
//        ];
//
//        $this->validate($request, $roles);
//
//        $wifi= new Wifi();
//        if ($request->hasFile('image')) {
//            $image = $request->file('image');
//            $extention = $image->getClientOriginalExtension();
//            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
//            Image::make($image)->resize(800, null, function ($constraint) {
//                $constraint->aspectRatio();
//            })->save("uploads/images/networks/$file_name");
//            $wifi->image = $file_name;
//        }
// $admin = auth('admin')->user();
//        if ($admin->city_id > 0){
//            $wifi->city_id = $admin->city_id;
//        }
//        else{
//                    $wifi->city_id = $request->city_id;
//
//        }
//        $wifi->name = $request->name;
//        $wifi->save();
//        return redirect()->back()->with('status', __('cp.create'));
    }


    public function show($id)
    {

    }


    public function edit($id)
    {
        $cities = City::where('status','active')->get();
        $wifi = Wifi::findOrFail($id);

        return view('admin.wifi.edit', [
            'wifi' => $wifi ,
            'cities' => $cities,

        ]);


    }

    public function update(Request $request, $id)
    {
        //
        $roles = [

            'name'   => 'required',
            'city_id'   => 'required',
        ];

        $this->validate($request, $roles);


        $wifi = Wifi::query()->findOrFail($id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/networks/$file_name");
            $wifi->image = $file_name;
        }


        $admin = auth('admin')->user();
        if ($admin->city_id > 0){
            $wifi->city_id = $admin->city_id;
        }
        else{
            $wifi->city_id = $request->city_id;

        }
        $wifi->name = $request->name;
        $wifi->save();
        return redirect()->back()->with('status', __('cp.update'));
    }


    public function chat($id)
    {
        $one = Wifi::findOrFail($id);

        return view('admin.wifi.chat',[
            'one'=>$one ,
        ]);
    }

    public function sendNotification(Request $request)
    {
        $users = EnableNotificationNetwork::where('network_id',$request->wifi_id)->pluck('user_id')->toArray();

        $tokens= Token::whereIn('user_id',$users)->pluck('fcm_token')->toArray();
        sendNotificationToUsers($tokens, $request->message,'0','0' );
        foreach($users as $one){
            $notification = new Notify();
            $notification->user_id = $one;
            $notification->messag_type = 1;
            $notification->message = $request->message;
            $notification->save();
        }


        return redirect()->back()->with('status', __('cp.create'));
    }

    public function destroy($id)
    {
        $wifi = Wifi::query()->findOrFail($id);
        if ($wifi) {
            Wifi::query()->where('id', $id)->delete();
            return "success";
        }
        return "fail";
    }
}
