<?php
namespace App\Http\Controllers\WEB\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Language;
use App\Models\Category;
use App\Models\ProductImage;

use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Page;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
class AuthController extends Controller
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
        
        $this->departments = Department::where('status', 'active')->with(['categories'=>function($query) {
            $query->where('status', 'active')->with(['subcategories'=>function($query) {
                $query->where('status', 'active')->with(['banners'=>function($query) {
                    $query->where('status', 'active'); }]); }]); }])->get();
        
        view()->share([
            'locales' => $this->locales,
            'settings' => $this->settings,
            'departments' => $this->departments,

        ]);
    }

    public function login()
    {
       
        if(auth()->check())   {

            return redirect('/');
         }
         else{
           
        $setting=Setting::all()->where('status','active');
        $categories=Category::all();
        return view('website.login', [
            'setting' =>$setting,
            'categories' =>$categories,
        ]);
            }
    }


    
    public function loginPost(Request $request)
    {
        
        $rules = [
            'emailOrMobile' => 'required',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'validator' =>implode("\n",$validator-> messages()-> all()) ]);
        }

        if (filter_var($request->emailOrMobile, FILTER_VALIDATE_EMAIL)) {
            $conditions = ['email' => $request->emailOrMobile, 'password' => $request->password];
        }
        else {
            $conditions = ['mobile' => $request->emailOrMobile, 'password' => $request->password];
        }
        if (Auth::attempt($conditions)) {
                if (Auth::user()->status == 'active') {
                    $user = Auth::user();
                    $user->last_login_at=Carbon::now()->toDateTimeString();
                    $user->save();
    
                   return response()->json(['status' => true, 'code' => 200]);
                } else {
                    auth()->logout();
                    
                    return response()->json(['message' => __('site.AccountNotActive')]);
                }
        }
        $message = __('website.emailOrpassword_incorrect');
        return response()->json(['status' => false, 'code' => 200 ,'message'=>$message]);
    }

    public function logout(Request $request)
    {
        auth()->logout();

        return redirect('/home');
    }

    public function signupPost(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|min:7|max:10|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'name' => 'required',
        
            
        ]);
        if ($validator->fails()) {
            return response()->json([
                'validator' =>implode("\n",$validator-> messages()-> all()) ]);
        }
        $newUser = new User();
        $newUser->name = $request->name;
        $newUser->password = bcrypt($request->password);
        $newUser->mobile = $request->mobile;
        $newUser->email = $request->email;
        $newUser->registration_language = app()->getLocale();
        $newUser->last_login_at=Carbon::now()->toDateTimeString();
        $newUser->status = 'active';
        
        $done = $newUser->save();
        if ($done) {

            $conditions = ['email'=>$request->email,'password' => $request->password];
            if (Auth::attempt($conditions)) {
                $user = Auth::user();
                return response()->json(['status' => true, 'code' => 200]);
            }
            return redirect()->back()->withErrors([ __('site.Whoops')])->withInput();
        } else {
            return redirect()->back()->withErrors([ __('site.Whoops')])->withInput();
        }
    }
}
