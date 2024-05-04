<?php

namespace App\Http\Controllers\WEB\Admin;


use App\Models\Admin;
use App\Models\City;
use App\Models\Institute;
use App\Models\Permission;
use App\Models\ProductService;
use App\Models\PublicService;
use App\Models\Setting;
use App\Models\Store;
use App\Models\UserPermission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NewPostNotification;

use Illuminate\Validation\Rule;
use Mockery\Exception;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Image;

class AdminController extends Controller
{

    public function image_extensions(){
        return array('jpg','png','jpeg','gif','bmp','pdf');
    }


    public function __construct()
    {
        $this->settings = Setting::query()->first();
        view()->share([
            'settings' => $this->settings,
        ]);
    }

    public function index(Request $request)
    {
        if (can('admins')) 
    {
        $items = Admin::query();
        if ($request->has('email')) {
            if ($request->get('email') != null)
                $items->where('email', 'like', '%' . $request->get('email') . '%');
        }

        if ($request->has('mobile')) {
            if ($request->get('mobile') != null)
                $items->where('mobile', 'like', '%' . $request->get('mobile') . '%');
        }

        $items = $items->where('id','>',1)->orderBy('id', 'desc')->get();
        return view('admin.admin.home', [
            'items' => $items,
        ]);

        
    }
            return redirect()->back()->with('error', __('cp.noPrimitions'));
    }

    public function get_cities($id)
    {
        $all_stores= Store::where(function($query) use ($id) {
            $query->where("city_id",$id)
                ->orWhere('all_cities', '1');
        })
            ->pluck("id","store_name")
            ->toArray();
        return response()->json($all_stores);
    }

    public function get_cities_institutes($id)
    {
        $all_institutes= Institute::join('institute_translations','institutes.id','=' , 'institute_translations.institute_id' )
            ->select('institute_translations.*','institutes.*')
            ->where("institutes.city_id",$id)
            ->where("institute_translations.locale","ar")
            ->pluck("institutes.id","institute_translations.name")
            ->toArray();
        return response()->json($all_institutes);
    }

    public function get_cities_public_services($id)
    {
        $all_public_services= PublicService::where("city_id",$id)
            ->pluck("id","name")
            ->toArray();
        return response()->json($all_public_services);
    }

    public function create()
    {
                if (can('admins')) 
    {
                    $cities=City::all();
        $all_stores = Store::all();
        $role = Permission::where('id', '>', 0)->get();
        return view('admin.admin.create', [ 'role' => $role,  'cities' => $cities ,   'all_stores' =>   $all_stores
]);
    }

            return redirect()->back()->with('error', __('cp.noPrimitions'));  

    }
    
    public function store(Request $request)
    {        if (can('admins')) 
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|string',
            'email'=>'required|email|unique:admins',
            'password'=>'required|min:6',
            'confirm_password'=>'required|same:password|min:6',
            'mobile'=>'required|digits_between:8,15',
            'city_id'=>'required',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $newAdmin = new Admin();
        $newAdmin->name=$request->name;
        $newAdmin->email=$request->email;
        $newAdmin->password=bcrypt($request->password);
        $newAdmin->mobile=$request->mobile;
        $newAdmin->city_id=$request->city_id;

        $newAdmin->save();

        $boo = false;
        $boo1 = false;
        $boo2 = false;
        $boo3 = false;
        $roles = '';
        if ($request->permissions) {
            $arr = [];
            foreach ($request->permissions as $permission) {
                $roles .= $permission . ',';
                if($permission == "stores")
                {
                    $boo = true;
                }
                if($permission == "institutes")
                {
                    $boo1 = true;
                }
                if($permission == "publicServices")
                {
                    $boo2 = true;
                }
                if($permission == "productServices")
                {
                    $boo3 = true;
                }

            }
            UserPermission::create([
                'user_id' => $newAdmin->id,
                'permission' => substr($roles, 0, -1),
                'store_id' => $boo && $request->store_id != "" ? $request->store_id : "0" ,
                'institute_id' => $boo1 && $request->institute_id != "" ? $request->institute_id : "0" ,
                'public_services_id' => $boo2 && $request->public_services_id != "" ? $request->public_services_id : "0" ,
                'product_services_id' => $boo3 ? $request->product_services_id : "" ,
            ]);
        }

        return redirect()->route('admin.admins.all')->with('status', __('cp.create'));

    }
            return redirect()->back()->with('error', __('cp.noPrimitions'));  

    }


    public function edit($id)
    {

            if(auth('admin')->id() == $id or can('admins') ){
                 $item = Admin::findOrFail($id);
                    $role=Permission::where('id','>',0)->get();
                    $userRole=UserPermission::where('user_id',$item->id)->first();
                    $cities=City::all();
                    $userRoleItem=[];
                    if($userRole)
                    {
                        $userRoleItem=explode(',',$userRole->permission);
                    }
                $all_stores = Store::where("city_id",$item->city_id)->get();
                $all_institutes= Institute::join('institute_translations','institutes.id','=' , 'institute_translations.institute_id' )
                    ->select('institute_translations.*','institutes.*')
                    ->where("institutes.city_id",$item->city_id)
                    ->where("institute_translations.locale","ar")
                    ->pluck("institutes.id","institute_translations.name")
                    ->toArray();
                $all_public_services= PublicService::where("city_id",$item->city_id)
                    ->pluck("id","name")
                    ->toArray();
                    return view('admin.admin.edit', ['item' => $item, 'cities' =>$cities,'role' =>$role, 'userRoleItem' => $userRoleItem, 'userRole' => $userRole , 'all_stores' =>   $all_stores, 'all_institutes' =>   $all_institutes, 'all_public_services' => $all_public_services]);

            }

            return redirect()->back()->with('error', __('cp.noPrimitions'));      

    }


    public function update(Request $request, $id)
    {
if(auth('admin')->id() == $id or can('admins') ){

        $newAdmin= Admin::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'=>'required|string',
            'city_id'=>'required',
            'mobile'=>'required|digits_between:8,15|unique:admins,mobile,' . $id,
            'email'=>'required|email|unique:admins,email,' . $id,

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $newAdmin->name=$request->name;
        $newAdmin->email=$request->email;
        $newAdmin->mobile=$request->mobile;
        $newAdmin->city_id=$request->city_id;

        $newAdmin->save();

        $boo = false;
        $boo1 = false;
        $boo2 = false;
        $boo3 = false;
        $roles = '';
        if ($request->permissions) {
            $arr = [];
            foreach ($request->permissions as $permission) {
                $roles .= $permission . ',';
                if($permission == "stores")
                {
                    $boo = true;
                }
                if($permission == "institutes")
                {
                    $boo1 = true;
                }
                if($permission == "publicServices")
                {
                    $boo2 = true;
                }
                if($permission == "productServices")
                {
                    $boo3 = true;
                }
            }


            $AdminPermission = UserPermission::where('user_id', $id)->first();

            if ($AdminPermission)
                $AdminPermission->delete();

            UserPermission::create([
                'user_id' => $id,
                'permission' => substr($roles, 0, -1),
                'store_id' => $boo && $request->store_id != "" ? $request->store_id : "0" ,
                'institute_id' => $boo1 && $request->institute_id != "" ? $request->institute_id : "0" ,
                'public_services_id' => $boo2 && $request->public_services_id != "" ? $request->public_services_id : "0" ,
                'product_services_id' => $boo3 ? $request->product_services_id : "" ,
            ]);

        }

        return redirect()->route('admin.admins.all')->with('status', __('cp.updated_successfully'));
}

            return redirect()->back()->with('error', __('cp.noPrimitions'));  

    }



    public function edit_password(Request $request, $id)
    {
if(auth('admin')->id() == $id or can('admins') ){
        $item = Admin::findOrFail($id);
        return view('admin.admin.edit_password',['item'=>$item]);
}

            return redirect()->back()->with('error', __('cp.noPrimitions'));  
    }


    public function update_password (Request $request, $id)
    {
       // return auth('admin')->id() .'' .$id;
if(auth('admin')->id() == $id or can('admins') ){
        $users_rules=array(
            'password'=>'required|min:6',
            'confirm_password'=>'required|same:password|min:6',
        );
        $users_validation=Validator::make($request->all(), $users_rules);

        if($users_validation->fails())
        {
            return redirect()->back()->withErrors($users_validation)->withInput();
        }
        $user = Admin::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();
        return redirect()->back()->with('status', __('cp.updated_successfully'));
}

            return redirect()->back()->with('error', __('cp.noPrimitions'));  
    }



}
