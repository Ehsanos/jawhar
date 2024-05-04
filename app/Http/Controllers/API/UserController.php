<?php

namespace App\Http\Controllers\API;
use App\Admin;

use App\Models\MobileNetworkPackages;
use App\Models\Setting;
use App\Models\Wellet_profit;
use App\Notifications\ResetPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Token;
use App\Models\Store;
use App\Models\Notify;
use App\Models\Wifi;
use App\Models\Networks;
use App\Models\Notifiy;
use App\Models\UserWallet;
use App\Models\RequestMobileBalance;
use App\Models\Order;
use App\Models\Payment;
use App\Models\BalanceCard;
use App\Models\PromoCodeUser;
use App\Models\PromoCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Image;
use DB;
use Illuminate\Support\Facades\Date;


class UserController extends Controller
{
    use SendsPasswordResetEmails;


    public function broker()
    {
        return Password::broker('users');
    }
    public function image_extensions()
    {
        return array('jpg', 'png', 'jpeg', 'gif', 'bmp');
    }
    
    public function myPromoCodes(){
        $items = PromoCodeUser::where('user_id' , auth('api')->id())->with('promo_code')->get();
        return response()->json(['status' => true, 'code' => 200 , 'items' => $items ]);
    }
    
    public function addNewPromoCode(Request $request){
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 400,
                'message' =>implode("\n",$validator-> messages()-> all()) ]);
        }
        
        $check = PromoCode::where('code' , $request->code)->where('end_date', '>=', Date::now())->whereColumn('count_usage' , '<' , 'max_usage')->first();
        $items = PromoCodeUser::where('user_id' , auth('api')->id())->with('promo_code')->get();

        if($check){
            $is_added = PromoCodeUser::where('promo_code_id' , $check->id)->where('user_id' , auth('api')->id())->first();
            if($is_added){
                return response()->json(['status' => false, 'code' => 201 , 'items'=>$items , 'message'=>__('api.added_before')]);
            }
            $item = new PromoCodeUser();
            $item->promo_code_id = $check->id;
            $item->user_id = auth('api')->id();
            $item->save();
            $items = PromoCodeUser::where('user_id' , auth('api')->id())->with('promo_code')->get();
            $check->increment('count_usage');
            $check->save();
            return response()->json(['status' => true, 'code' => 200 , 'items'=>$items, 'message'=>__('api.ok')]);
        }else{
            return response()->json(['status' => false, 'code' => 201 , 'items'=>$items , 'message'=>__('api.wrong_code')]);
        }
    }
    
    public function deletePromoCode($id){
        PromoCodeUser::where('id' , $id)->delete();
        return response()->json(['status' => true, 'code' => 200]);
    }
    
    
    
    
    
    public function signUp(Request $request)
    {

        $name = $request->get('name');
        $email = $request->get('email');
        $mobile =convertAr2En($request->get('mobile')) ;
        $password = bcrypt($request->get('password'));
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
       //     'mobile' => 'required|digits_between:8,12|unique:users',
            'password' => 'required|min:6',

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 400,
                'message' =>implode("\n",$validator-> messages()-> all()) ]);
        }
        $newUser = new User();
        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->mobile = $mobile;
        $newUser->status = 'active';
        $newUser->have_pass = 1;
        $newUser->password = $password;
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
        $newUser->user_code = "10$newUser->id";
        $newUser->save();

        if ($newUser) {
            if ($request->has('fcm_token')) {
                Token::updateOrCreate(['device_type' => $request->get('device_type'),'fcm_token' => $request->get('fcm_token')],['user_id' => $newUser->id]);
            }

            $user = User::findOrFail($newUser->id);
            $user['access_token'] = $newUser->createToken('mobile')->accessToken;



            $massege =__('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'user' =>$user , 'message' => $massege ]);
        }
        $massege =__('api.whoops');
        return response()->json(['status' => false, 'code' => 400, 'message' => $massege ]);
    }
    public function socialSignUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 400,
                'message' =>implode("\n",$validator-> messages()-> all()) ]);
        }
        $check = User::where('social_token',$request->token)->where('status','active')->first();
        
        if($check){

            if ($request->has('fcm_token')) {
                Token::updateOrCreate(['device_type' => $request->get('device_type'),'fcm_token' => $request->get('fcm_token')],['user_id' => $check->id]);
            }

            $user = User::findOrFail($check->id);
            $user['access_token'] = $user->createToken('mobile')->accessToken;

            $massege =__('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'user' =>$user , 'message' => $massege ]);
        }
        if($request->has('email')){
            $check2 = User::where('email',$request->email)->first();
            if($check2){
                $check2->email = ($request->has('email'))? $request->email:"";
                $check2->mobile = ($request->has('mobile'))? $request->mobile:"";
                $check2->save();
                if ($request->has('fcm_token')) {
                    Token::updateOrCreate(['device_type' => $request->get('device_type'),'fcm_token' => $request->get('fcm_token')],['user_id' => $check2->id]);
                }
    
                $user = User::findOrFail($check2->id);
                $user['access_token'] = $user->createToken('mobile')->accessToken;
    
                $massege =__('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'user' =>$user , 'message' => $massege ]);
            }
        }
        if($request->email == "" && $request->has('mobile')){
            $check2 = User::where('email',$request->email)->first();
            if($check2){
                $check2->email = ($request->has('email'))? $request->email:"";
                $check2->mobile = ($request->has('mobile'))? $request->mobile:"";
                $check2->save();
                if ($request->has('fcm_token')) {
                    Token::updateOrCreate(['device_type' => $request->get('device_type'),'fcm_token' => $request->get('fcm_token')],['user_id' => $check2->id]);
                }
    
                $user = User::findOrFail($check2->id);
                $user['access_token'] = $user->createToken('mobile')->accessToken;
    
                $massege =__('api.ok');
                return response()->json(['status' => true, 'code' => 200, 'user' =>$user , 'message' => $massege ]);
            }
        }

        $newUser = new User();
        $newUser->name = $request->name;
        $newUser->email = ($request->has('email'))? $request->email:"";
        $newUser->mobile = ($request->has('mobile'))? $request->mobile:"";
        $newUser->status = 'active';
        $newUser->social_type = $request->type;
        $newUser->social_token = $request->token;
        $newUser->image_profile = $request->avatar;

        $newUser->save();
        $newUser->user_code = "10$newUser->id";
        $newUser->save();
        if ($newUser) {
            if ($request->has('fcm_token')) {
                Token::updateOrCreate(['device_type' => $request->get('device_type'),'fcm_token' => $request->get('fcm_token')],['user_id' => $newUser->id]);
            }

            $user = User::findOrFail($newUser->id);
            $user['access_token'] = $newUser->createToken('mobile')->accessToken;



            $massege =__('api.ok');
            return response()->json(['status' => true, 'code' => 200, 'user' =>$user , 'message' => $massege ]);
        }
        $massege =__('api.whoops');
        return response()->json(['status' => false, 'code' => 400, 'message' => $massege ]);
    }


    public function getCurrency()
    {
        $user_id = auth('api')->id();

        $user = User::where("id",$user_id)->first();

        return response()->json(['status' => true, 'code' => 200, 'currency' => $user->currency ]);
    }


    public function changeCurrency(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 400,
                'message' => implode("\n", $validator->messages()->all())]);
        }

        $user = auth('api')->user();

//        $msg = check_version_in_post($request);
//        if ($msg != "") {
//            return response()->json(['status' => false, 'code' => 200, 'message' => $msg]);
//        }

        $user->currency = $request->get('currency');
        $user->save();

        return response()->json(['status' => true, 'code' => 200, 'message' => 'done']);

    }


    public function login(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 400,
                'message' => implode("\n",$validator-> messages()-> all()) ]);
        }


        if (Auth::once(['email' => request('email'), 'password' => request('password')])) {

            $user = Auth::user();
            $user['access_token'] = $user->createToken('mobile')->accessToken;
            if ($user->status != 'active') {
                $message = (app()->getLocale() == "ar") ? 'الحساب غير مفعل' : 'The account is not active';
                 return response()->json(['status' => false, 'code' => 400,'message'=>$message ]);
            }
            else {
                if ($request->has('fcm_token')) {
                    Token::updateOrCreate(['device_type' => $request->get('device_type'),'fcm_token' => $request->get('fcm_token')],['user_id' => $user->id]);
                }

 if($user->type == 1){
       $store = Store::where('user_id',$user->id)->first();
       if($store->status == 'active'){
                    return response()->json(['status' => true, 'code' => 200 ,'user' => $user,'store' => $store]);
       }else{
            $message = (app()->getLocale() == "ar") ? 'الحساب غير مفعل' : 'The account is not active';
                 return response()->json(['status' => false, 'code' => 400,'message'=>$message ]);
       }

}
elseif($user->type == 2){
     $store = Store::where('user_id',$user->id)->first();
    $wifi = Wifi::where(['store_id'=>$store->id])->first();
    if($wifi->Status == 'active')
    {
        return response()->json(['status' => true, 'code' => 200 ,'user' => $user,'wifi'=>$wifi,'store' => $store]);
    }
    else
    {
        $message = (app()->getLocale() == "ar") ? 'شبكة هذا الحساب غير فعالة' : 'The Wifi is not Active';
        return response()->json(['status' => false, 'code' => 400,'message'=>$message ]);
    }
      return response()->json(['status' => true, 'code' => 200 ,'user' => $user,'wifi'=>$wifi,'store' => $store]);
}else{
    return response()->json(['status' => true, 'code' => 200 ,'user' => $user]);


}

            }
        } else {

            $EmailData = User::query()->where(['email' => $email])->first();
            if ($EmailData) {
                $message = __('api.wrong_password');

                return response()->json(['status' => false, 'code' => 400 , 'message'=>$message ]);

            } else {
                $message = __('api.wrong_email2');

                return response()->json(['status' => false, 'code' => 400 , 'message'=>$message ]);
            }
        }
    }
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 400,'message' =>implode("\n",$validator-> messages()-> all()) ]);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $message = (app()->getLocale() == 'ar') ? ' البريد الإلكتروني المدخل غير مسجل  ' : 'We cant find a user with that e-mail address';
            return response()->json(['status' => false, 'code' => 400,'message' => $message ]);
        }
        $token = $this->broker()->createToken($user);
        $user->notify(new ResetPassword($token));
        $message=__('api.resetPassword');
        return response()->json(['status' => true, 'code' => 200,'message' => $message ]);
    }
    public function editProfile(Request $request)
    {
        $user_id = auth('api')->id();
        
        $user = User::query()->findOrFail($user_id);
       if ($user->status == 'not_active'){
                Token::where('user_id', $user_id)->delete();
                auth('api')->user()->token()->revoke();
                $message = 'logged out successfully';
            return response()->json(['status' => false, 'code' => 200,
                'message' => $message ]);  
        }
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'mobile' => 'required',
        ]);

        $name = ($request->has('name')) ? $request->get('name') : $user->name;
        $mobile = (convertAr2En($request->get('mobile'))) ? $request->get('mobile') : $user->mobile;

        $user->name = $name;
        $user->mobile = $mobile;

        if ($request->hasFile('image_profile')) {
            $imageProfile = $request->file('image_profile');
            $extention = $imageProfile->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($imageProfile)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/users/$file_name");
            $user->image_profile = $file_name;
        }
        $user->save();

        if ($user) {

            if ($request->has('fcm_token')) {
                Token::updateOrCreate(['device_type' => $request->get('device_type'),'fcm_token' => $request->get('fcm_token')],['user_id' => $user->id]);
            }

            $user['access_token'] = $user->createToken('mobile')->accessToken;

            $message = __('api.edit');
            return response()->json(['status' => true, 'code' => 200, 'user' =>$user ,
                'message' => implode("\n",$validator-> messages()-> all()) ]);
        } else {
            $message = __('api.not_edit');
            return response()->json(['status' => false, 'code' => 200,
                 'message' => $validator ]);
        }
    }
    public function addBalance(Request $request)
    {
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id);
        if ($user->status == 'not_active'){
                Token::where('user_id', $user_id)->delete();
                auth('api')->user()->token()->revoke();
                $message = 'logged out successfully';
            return response()->json(['status' => false, 'code' => 200,
                'message' => $message ]);  
        }
        $validator = Validator::make($request->all(),[
            'serial_number' => 'required',
            'password' => 'required',
        ]);

        $BalanceCard = BalanceCard::where('serial_number', $request->serial_number)->where('status', 'active')->where('password', $request->password)->where('is_used', 0)->where("currency",get_user_carrency_from_api())->first();
        
        if($BalanceCard){
                $wallet=new UserWallet();
                 $wallet->user_id=$user_id;
                 $wallet->total_price= $BalanceCard->price;
                 $wallet->title= 'اضافة رصيد' ;
                 $wallet->details=' رقم البطاقة:'. $request->serial_number ;
                 $wallet->type =0;
                 $wallet->save();
                 $BalanceCard->is_used=1;
                 $BalanceCard->save();
                    $balanceIn=UserWallet::where('user_id',$user_id)->where('type',0)->sum('total_price');
                    $balanceOut=UserWallet::where('user_id',$user_id)->where('type',1)->sum('total_price');
             $wallet['balance']= (double)$balanceIn - $balanceOut;
                    $message = __('api.addBalanceCard');
                    return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'wallet' => $wallet]);
        }
                
        else{
                        $message = __('api.not_found');
                    return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
        }


    }
//    public function requestMobileBalance(Request $request)
//    {
//        $user_id = auth('api')->id();
//
//        $user = User::query()->findOrFail($user_id);
//       if ($user->status == 'not_active'){
//                Token::where('user_id', $user_id)->delete();
//                auth('api')->user()->token()->revoke();
//                $message = 'logged out successfully';
//            return response()->json(['status' => false, 'code' => 200,
//                'message' => $message ]);
//        }
//        $validator = Validator::make($request->all(),[
//            'mobile' => 'required|digits_between:8,12',
//            'balance' => 'required',
//            'network_id' => 'required',
//            'network_packages_id' => 'required',
//        ]);
//            $balanceIn=UserWallet::where('user_id',$user_id)->where('type',0)->sum('total_price');
//            $balanceOut=UserWallet::where('user_id',$user_id)->where('type',1)->sum('total_price');
//            $balance= $balanceIn - $balanceOut;
//
//           $network_packages = MobileNetworkPackages::where('id',$request->network_packages_id);
//            $purchasing=$network_packages->purchasing_price;
//
//           $profil= $purchasing - $request->balance;
//
//            if($request->balance > $balance )
//            {
//                $message = __('api.noBalance');
//                return response()->json(['status' => false, 'code' => 200, 'message' => $message]);
//            }else{
//                $requestMobileBalance=new RequestMobileBalance();
//                $requestMobileBalance->user_id=$user_id;
//                $requestMobileBalance->city_id=$request->city_id;
//                $requestMobileBalance->mobile= $request->mobile;
//                $requestMobileBalance->balance= $request->balance;
//                $requestMobileBalance->network_id= $request->network_id;
//                $requestMobileBalance->network_packages_id= $request->network_packages_id;
//                $requestMobileBalance->save();
//
//                $Wellet_profit=new Wellet_profit();
//                $Wellet_profit->user_id=$user_id;
//                $Wellet_profit->profit =$profil;
//                $Wellet_profit->purchasing_price= $purchasing ;
//                $Wellet_profit->details=' رقم الموبايل ' .$request->mobile . ' الرصيد ' . $request->balance;
//                $Wellet_profit->type =0;
//                $Wellet_profit->save();
//
//                $requestMobileBalance->wellet_profit_id = $Wellet_profit->id;
//                $requestMobileBalance->save();
//
//                $wallet=new UserWallet();
//                $wallet->user_id=$user_id;
//                $wallet->total_price =$request->balance;
//                $wallet->title= 'طلب رصيد موبايل' ;
//                $wallet->details=' رقم الموبايل ' .$request->mobile . ' الرصيد ' . $request->balance;
//                $wallet->type =1;
//                $wallet->save();
//                $message = __('api.requestMobileBalance');
//                return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
//            }
//
//
//    }
    public function addPassword(Request $request)
    {
        $rules = [
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 400,
                'message' =>implode("\n",$validator-> messages()-> all())]);
        }
        $user = auth('api')->user();

        $user->password = bcrypt($request->get('password'));
        $user->have_pass = 1;

        if ($user->save()) {
            
            $user->refresh();
                    $user['access_token'] = $user->createToken('mobile')->accessToken;

            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200,'message' => $message , 'user' =>$user ]);
        }
        $message = __('api.whoops');
        return response()->json(['status' => false, 'code' => 400,'message' => $message ]);
    }
    public function changePassword(Request $request)
    {
        $rules = [
            'old_password' => 'required|min:6',
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 400,
                'message' =>implode("\n",$validator-> messages()-> all())]);
        }
        $user = auth('api')->user();

        if (!Hash::check($request->get('old_password'), $user->password)) {
            $message = __('api.old_password'); //wrong old
            return response()->json(['status' => false, 'code' => 400,'message' => $message,
                'validator' => $validator ]);
        }

        $user->password = bcrypt($request->get('password'));

        if ($user->save()) {
            $user->refresh();
            $message = __('api.ok');
            return response()->json(['status' => true, 'code' => 200,'message' => $message ]);
        }
        $message = __('api.whoops');
        return response()->json(['status' => false, 'code' => 400,'message' => $message ]);
    }
    public function check_password(Request $request)
    {
        $rules = [
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 400,
                'message' =>implode("\n",$validator-> messages()-> all())]);
        }
        $user = auth('api')->user();

        if (!Hash::check($request->get('password'), $user->password)) {
            $message = __('api.wrong_password'); //wrong old
            return response()->json(['status' => false, 'code' => 400,'message' => $message ]);
        }


        return response()->json(['status' => true, 'code' => 200,'message' => 'done' ]);
        
        
    }
    public function logout()
    {
        $user_id = auth('api')->id();
        Token::where('user_id', $user_id)->forceDelete();
        if (auth('api')->user()->token()->revoke()) {
            $message = 'logged out successfully';
        Token::where('user_id', $user_id)->forceDelete();
            return response()->json(['status' => true, 'code' => 200,
                'message' => $message ]);
        } else {
            $message = 'logged out successfully';
            return response()->json(['status' => true, 'code' => 200,
                'message' => $message ]);
        }
    }
    public function paymentRequest(Request $request)
{
    $user_id = auth('api')->id();
    $user = User::query()->findOrFail($user_id);

    $validator = Validator::make($request->all(), [
        'amount' => 'required',
        'payment_method' => 'required',
        'email' => 'required_if:payment_method,1,2',
        'full_name' => 'required_if:payment_method,3',
        'mobile' => 'required_if:payment_method,3',
        'country' => 'required_if:payment_method,3',
        'city' => 'required_if:payment_method,3',

    ]);
    if ($validator->fails()) {
        return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->messages()->all())]);
    }

    $w1 = Wallet::where(['user_id'=>$user_id,'type'=>1])->sum('amount');//income
    $w2 = Wallet::where(['user_id'=>$user_id,'type'=>2])->sum('amount');//outcome
    $wallet = $w1 - $w2;

    if ($wallet < $request->get('amount') ){
     $message = __('api.noAmount');
    return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
    }
    $chek =Payment::where('user_id', $user_id)->whereIn('status', [0,1])->first();
    if ($chek ){
     $message = __('api.paymentPending');
    return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
    }
    $payment = new Payment();
    $payment->user_id = $user_id;
    $payment->amount = $request->get('amount');
    $payment->payment_method = $request->get('payment_method');
    $payment->email = $request->get('email');
    $payment->full_name = $request->get('full_name');
    $payment->mobile = $request->get('mobile');
    $payment->country = $request->get('country');
    $payment->city = $request->get('city');
    $payment->save();
    $message = __('api.paymentOk');

    return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
}
    public function wallet()
{
    $user_id = auth('api')->id();
    $user = User::query()->findOrFail($user_id);
        $balanceIn=UserWallet::where('user_id',$user_id)->where('type',0)->sum('total_price');
        $balanceOut=UserWallet::where('user_id',$user_id)->where('type',1)->sum('total_price');
        $balance= $balanceIn - $balanceOut;
    $wallet = UserWallet::where('user_id',$user_id)->orderByDesc('id')->paginate(10);
    if ($user) {
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'balance' => $balance, 'wallet' => $wallet]);

    } else {
        $message = __('api.not_found');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message]);

    }
}
    public function myOrders()
{
    $user_id = auth('api')->id();
    $user = User::query()->findOrFail($user_id);
    $orders = Order::where('user_id',$user_id )->with('products')->get();
    if (count($orders) > 0) {
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'orders' => $orders]);

    } else {
        $message = __('api.not_found');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message,]);

    }
}
    public function myAllOrders()
{
    $user_id = auth('api')->id();
    $user = User::query()->findOrFail($user_id);
    $orders = Order::where('user_id',$user_id )->with('order_products')->get();
    if (count($orders) > 0) {
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'orders' => $orders]);

    } else {
        $message = __('api.not_found');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message,]);

    }
}
    public function orderDetails(Request $request)
{
    $validator = Validator::make($request->all(), [
        'order_id' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->messages()->all())]);
    }
    $user_id = auth('api')->id();
    $user = User::query()->findOrFail($user_id);
    $storeId = Store::where('user_id',$user_id)->first();
    if($user->type != 1){
       $order = Order::where('user_id',$user_id )->where('id',$request->order_id)->with('products')->first();

    }
    if($user->type == "1"){
       $order = Order::where('store_id',$storeId->id )->where('id',$request->order_id)->with('products')->first();

    }
    if ($order) {
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'order' => $order]);

    } else {
        $message = __('api.not_found');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message,]);

    }
}
    public function notifications()
{
    $user_id = auth('api')->id();
    $user = User::query()->findOrFail($user_id);
    $data = Notify::where('user_id',$user_id)->orWhere('user_id',0)->orderByDesc('id')->get();
    if (count($data) > 0) {
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'notifications' => $data]);

    } else {
        $message = __('api.not_found');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'notifications' => $data]);

    }
}
    public function deletNotification(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'notification_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 400, 'message' => implode("\n", $validator->messages()->all())]);
        }
        $user_id = auth('api')->id();
        $user = User::query()->findOrFail($user_id); 
        $data = Notify::where('user_id',$user_id)->Where('id',$request->notification_id)->delete();
            $message = __('api.delete');
            return response()->json(['status' => true, 'code' => 200, 'message' => $message]);
    }
    public function getWifi(){

    
        $wifi = Wifi::query()->where('status', 'active')->get();
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'wifi' => $wifi]);
    }
    public function getNetwork(Request $request){

        $validator = Validator::make($request->all(), [
            'wifi_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }
        $network = Networks::query()->where('status', 'active')->where('wifi_id', $request->wifi_id)->get();
        $message = __('api.ok');
        return response()->json(['status' => true, 'code' => 200, 'message' => $message, 'network' => $network]);
    }

    public function saveCode(Request $request)
    {

        $msg = check_version_in_post($request);
        if($msg != "")
        {
            return response()->json(['status' => false, 'code' => 200, 'message' => $msg]);
        }

        $setting = Setting::query()->findOrFail(1);

        $validator = Validator::make($request->all(), [
            'user_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200, 'validator' => implode("\n", $validator->messages()->all())]);
        }

        $user_id = auth('api')->id();

        $user = User::where("id",$user_id)->first();

        $leader = User::where("user_code", $request->user_code)->first();

        if(isset($leader->id))
        {

            if($user->user_prof == "")
            {
                $last_turkey_profit = \Illuminate\Support\Facades\DB::table('wellet_profits')->latest()->first()->id;
                $last_dollar_profit = \Illuminate\Support\Facades\DB::table('wellet_profits1')->latest()->first()->id;

                $user->last_wellet_profit_turkey = $last_turkey_profit;
                $user->last_wellet_profit_dollar = $last_dollar_profit;
                $user->user_prof = $leader->user_code;
                $user->save();


                $message = " "."تم استخدام كودك من قبل"." ".$user->name;
                $notification = new Notify();
                $notification->user_id = $leader->id;
                $notification->messag_type = 0;//0=in 1=out
                $notification->message = $message;
                $notification->save();

                $tokens = Token::where('user_id', $leader->id)->pluck('fcm_token')->toArray();
                sendNotificationToUsers($tokens, $message, 1, 0);

                $message = "تم حفظ الكود";
                return response()->json(['status' => true, 'code' => 200, 'message' => $message,]);
            }
            else
            {
                $message = "لديك كود مشترك به سابقا";
                return response()->json(['status' => false, 'code' => 200, 'message' => $message, ]);
            }

        }
        else
        {
            $message = "هذا الكود غير صحيح";
            return response()->json(['status' => false, 'code' => 200, 'message' => $message,]);
        }
    }

    public function getUserCount(Request $request)
    {

        $msg = check_version_in_post($request);
        if($msg != "")
        {
            return response()->json(['status' => false, 'code' => 200, 'message' => $msg]);
        }

        $user_id = auth('api')->id();

        $user = User::where("id",$user_id)->first();

        $users = User::where("user_prof",$user->user_code)->get();

        $count = count($users);

        $message = "تم حفظ الكود";
        return response()->json(['status' => true, 'code' => 200, 'message' => $message,'count' => $count]);



    }
}
