<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\sbPolygonEngine;
use App\Models\Language;
use App\Models\Networks;
use App\Models\Setting;
use App\Models\Store;
use App\Models\UserPermission;
use App\Models\Wifi;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\NetworkSections;

class NetworkSectionsController extends Controller
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


    public function index($my_store_id = null)
    {
//        $data1 = "51.50810140697543";
//        $data2 = "-0.02729415893554688";
//
//        $selected_user_id = -1;
//
//        try{
//
//            if(isset($data1) && $data1 != "" && isset($data2) && $data2 != "")
//            {
//                $all_network = NetworkSections::where("store_id","80")->get();
//
//                foreach($all_network as $one_net)
//                {
//                    $a1 = explode (",", $one_net->top_point);
//                    $a2 = explode (",", $one_net->bottom_point);
//                    $b1 = explode (",", $one_net->right_point);
//                    $b2 = explode (",", $one_net->left_point);
//
//                    $polygonBox = [
//                        [(double)trim($a1[0]),(double)trim($a1[1])],
//                        [(double)trim($a2[0]),(double)trim($a2[1])],
//                        [(double)trim($b1[0]),(double)trim($b1[1])],
//                        [(double)trim($b2[0]),(double)trim($b2[1])],
//                    ];
//
//                    //dd($one_net->top_point." - ".(double)trim($str_arr[0])." - ".(double)trim($str_arr[1]));
//                    $sbPolygonEngine = new sbPolygonEngine($polygonBox);
//
//                    $isCrosses = $sbPolygonEngine->isCrossesWith($data1, $data2);
//
//                    if($isCrosses)
//                    {
//                        $selected_user_id = $one_net->user_id;
//                        break;
//                    }
//
//                }
//            }
//
//        } catch (\Exception $e) {
//
//        }
        //dd($selected_user_id);

        $networksections = NetworkSections::query();
        if($my_store_id != null)
        {
            $networksections = $networksections->where("store_id",$my_store_id);
        }
        else
        {
            $admin = auth('admin')->user();
            if ($admin->id != 1) {
                $admin_perm = UserPermission::where('user_id', $admin->id)->first();
                $networksections = $networksections->where("store_id", $admin_perm->store_id);
            }
        }
        $networksections = $networksections->orderBy('id', 'asc')->get();

        return view('admin.NetworkSections.home', [
            'networksections' => $networksections,
        ]);
    }

    public function create(Request $request ,$my_store_id = "")
    {
        if(isset($request->my_store_id))
        {
            $store = Store::where("id",$request->my_store_id)->orderBy('id', 'desc')->get();
        }
        else {
            $admin = auth('admin')->user();

            if ($admin->id != 1) {
                $admin_perm = UserPermission::where('user_id', $admin->id)->first();
                if (isset($admin_perm->store_id) && $admin_perm->store_id > 0) {
                    $store = Store::where('id', $admin_perm->store_id)->orderBy('id', 'desc')->get();
                }
            } else {
                $store = Store::where('type', 2)->orderBy('id', 'desc')->get();
            }
        }
            $user = User::orderBy('id', 'desc')->get();
            return view('admin.NetworkSections.create',
                [
                    'store' => $store,
                    'user' => $user,
                    'my_store_id' => $my_store_id,
                ]);
        }

    public function edit($id,Request $request )
    {
        if(isset($request->my_store_id))
        {
            $store = Store::where("id",$request->my_store_id)->orderBy('id', 'desc')->get();
        }
//        else {
//            $admin = auth('admin')->user();
//
//            if ($admin->id != 1) {
//                $admin_perm = UserPermission::where('user_id', $admin->id)->first();
//                if (isset($admin_perm->store_id) && $admin_perm->store_id > 0) {
//                    $store = Store::where('id', $admin_perm->store_id)->orderBy('id', 'desc')->get();
//                }
//            } else {
//                $store = Store::where('type', 2)->orderBy('id', 'desc')->get();
//            }
//        }

        $user = User::orderBy('id', 'desc')->get();
        $networksections = NetworkSections::findOrFail($id);
        return view('admin.NetworkSections.edit',
            [
                'store'=>$store ,
                'user'=>$user,
                'networksections' => $networksections,
            ]);
    }
    public function store(Request $request)
    {
        $roles = [
            'store_id' => 'required',
            'user_id' => 'required',
            'top_point' => 'required',
            'bottom_point' => 'required',
            'right_point' => 'required',
            'left_point' => 'required',
            'app_percent' =>'required',
            'reNewNetwork_percent' => 'required',
        ];
        $this->validate($request, $roles);

        $NetworkSections = new NetworkSections();
        $NetworkSections->store_id = $request->store_id;
        $NetworkSections->user_id = $request->user_id;
        $NetworkSections->top_point = $request->top_point;
        $NetworkSections->bottom_point = $request->bottom_point;
        $NetworkSections->right_point = $request->right_point;
        $NetworkSections->left_point = $request->left_point;
        $NetworkSections->app_percent = $request->app_percent;
        $NetworkSections->reNewNetwork_percent = $request->reNewNetwork_percent;
        $NetworkSections->save();
        return redirect()->back()->with('status', __('cp.create'));
    }

    public function update(Request $request, $id)
    {
        $roles = [
            'store_id' => 'required',
            'user_id' => 'required',
            'top_point' => 'required',
            'bottom_point' => 'required',
            'right_point' => 'required',
            'left_point' => 'required',
            'app_percent' =>'required',
            'reNewNetwork_percent' => 'required',
        ];
        $this->validate($request, $roles);
        $NetworkSections = NetworkSections::query()->findOrFail($id);
        $NetworkSections->store_id = $request->store_id;
        $NetworkSections->user_id = $request->user_id;
        $NetworkSections->top_point = $request->top_point;
        $NetworkSections->bottom_point = $request->bottom_point;
        $NetworkSections->right_point = $request->right_point;
        $NetworkSections->left_point = $request->left_point;
        $NetworkSections->app_percent = $request->app_percent;
        $NetworkSections->reNewNetwork_percent = $request->reNewNetwork_percent;
        $NetworkSections->save();
        return redirect()->back()->with('status', __('cp.create'));
    }


    public function update_status(Request $request, $id)
    {
        $store= Store::findOrFail($id);
        if ($store->status_network == 1)
        {
            $store->status_network = 0;
        }
       else
        {
            $store->status_network = 1;
        }

        $store->save();
        return redirect()->back()->with('status', __('cp.update'));
    }






}
