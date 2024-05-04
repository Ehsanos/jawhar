<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Exports\UsersExport;

use App\Models\NagmaAshab;
use App\Models\UserAddress;
use App\Models\City;
use App\Models\Country;
use App\Models\Setting;
use App\Models\User;
use App\Models\Token;
use App\Models\UserWallet;
use App\Models\Notifiy;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

use Dotenv\Exception\ValidationException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NewPostNotification;
use Image;
use Illuminate\Validation\Rule;
use Mockery\Exception;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->settings = Setting::query()->first();
        view()->share([
            'settings' => $this->settings,
        ]);
    }

    public function index(Request $request)
    {
        $items = User::query();
        if ($request->has('email')) {
            if ($request->get('email') != null)
                $items->where('email', 'like', '%' . $request->get('email') . '%');
        }

        if ($request->has('name')) {
            if ($request->get('name') != null)
                $items->where('name', 'like', '%' . $request->get('name') . '%');
        }
        if ($request->has('mobile')) {
            if ($request->get('mobile') != null)
                $items->where('mobile', 'like', '%' . $request->get('mobile') . '%');
        }
        if ($request->has('statusUser')) {
            if ($request->get('statusUser') != null)
                $items->where('status',  $request->get('statusUser'));
        }

        if ($request->has('sortBy')) {
            if ($request->get('sortBy') != null)
            if($request->arrangBy == 'desc'){
                $items->orderBy($request->get('sortBy'), 'desc');
            }
            else{
             $items->orderBy($request->get('sortBy'), 'asc');

            }
        }
        $items = $items->with('wallet')->paginate(30);
        
        

        return view('admin.users.home', [
            'items' => $items,
        ]);


    }
 
 
    public function indexNotActive(Request $request)
    {
        $items = User::query();
        if ($request->has('email')) {
            if ($request->get('email') != null)
                $items->where('email', 'like', '%' . $request->get('email') . '%');
        }

        if ($request->has('mobile')) {
            if ($request->get('mobile') != null)
                $items->where('mobile', 'like', '%' . $request->get('mobile') . '%');
        }
        if ($request->has('statusUser')) {
            if ($request->get('statusUser') != null)
                $items->where('status',  $request->get('statusUser'));
        }

        $items = $items->where('status', 'not_active')->orderBy('id', 'desc')->get();

        return view('admin.users.homeNotActive', [
            'items' => $items,
        ]);


    }


    public function create()
    {
        return view('admin.users.create');
    }


    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|unique:users|digits_between:8,12',
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',

        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $newUser = new User();
        $newUser->email = $request->email;
        $newUser->name = $request->name;
        $newUser->password = bcrypt($request->password);
        $newUser->mobile = $request->mobile;
        $newUser->type = 0;


        if ($request->hasFile('image_profile')) {
            $image = $request->file('image_profile');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/users/$file_name");
            $newUser->image_profile = $file_name;
        }

        $newUser->save();

        return redirect()->back()->with('status', __('cp.create'));

    }


    public function chat($id)
    {

        $item = User::findOrFail($id);

        return view('admin.users.chat',[
            'item'=>$item ,
        ]);
    }
    
     public function sendNotification(Request $request)
    {
            $tokens = Token::where('user_id',$request->user_id)->pluck('fcm_token')->toArray();
            sendNotificationToUsers($tokens, $request->message,'0','0' );
            

        $notifications= New Notifiy;
        $notifications->user_id = $request->user_id;
        $notifications->message = $request->message;
        $notifications->save();

        return redirect()->back()->with('status', __('cp.create'));
    }
    
        public function wallet(Request $request , $id)
    {
        $currency = isset($request->currency) && $request->currency == "dollar" ? "dollar" : "turkey";

        set_currency_now($currency);

        $user = User::findOrFail($id);
                    $balanceIn=UserWallet::where('user_id',$id)->where('type',0)->sum('total_price');
            $balanceOut=UserWallet::where('user_id',$id)->where('type',1)->sum('total_price');
            $balance= $balanceIn - $balanceOut;
        $items = UserWallet::where('user_id',$user->id)->orderBy('id', 'desc')->get();
 // return $user;
        return view('admin.users.wallet',[
            'items'=>$items , 'user'=>$user ,'balance' =>$balance,'id_lolo'=>$id
        ]);
    }
    
    

    public function edit($id)
    {

        $item = User::findOrFail($id);

        $nagma_ashab = NagmaAshab::all();

        return view('admin.users.edit',[
            'item'=>$item ,
            'nagma_ashab'=>$nagma_ashab,
        ]);
    }


    public function update(Request $request, $id)
    {  
        $user= User::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
           // 'mobile' => 'digits_between:8,12|unique:users,mobile,'.$user->id,
            'user_code' => 'unique:users,user_code,'.$user->id,
            ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
 
        if ($request->hasFile('image_profile')) {
            $image = $request->file('image_profile');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/users/$file_name");
            $user->image_profile = $file_name;
        }
        $user->name = $request->name;
        $user->user_code = $request->user_code;
        $user->mobile = $request->mobile;
        $user->nagma_ashab_id = $request->nagma_ashab_id;
        $user->save();

        return redirect()->back()->with('status', __('cp.update'));
    }



    public function edit_password(Request $request, $id)
    {
        $item = User::findOrFail($id);
        return view('admin.users.edit_password',['item'=>$item]);
    }


    public function update_password(Request $request, $id)
    {
        $users_rules=array(
            'password'=>'required|min:6',
            'confirm_password'=>'required|same:password|min:6',
        );
        $users_validation=Validator::make($request->all(), $users_rules);

        if($users_validation->fails())
        {
            return redirect()->back()->withErrors($users_validation)->withInput();
        }
        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back()->with('status', __('cp.update'));
    }

    public function export() 
    {
    
        return Excel::download(new UsersExport, 'allUsers.xlsx');
    }



}
