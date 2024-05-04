<?php
namespace App\Http\Controllers\WEB\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Language;
use App\Models\Category;

use App\Models\Product;
use App\Models\Setting;
use App\Models\Order;
use App\Models\Country;
use App\Models\City;
use App\Models\Fevorite;
use App\Models\UserAddresse;
use App\Models\Page;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Support\Facades\Auth;


class UserProfileController extends Controller
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
    public function index()
    {
       
        $categories=Category::all()->where('status','active');
        $produts=Product::all()->where('id',1);
        $setting=Setting::all();
        $user=auth()->user();
        $slider1=Product::orderBy('created_at','desc')->first();;
        $slider2=Product::orderBy('created_at','desc')->skip(1)->take(1)->get()->first();

        return view('website.profile', [
            'categories' =>$categories,
            'produts' =>$produts,
            'setting' =>$setting,
            'user' =>$user,
            'slider2' =>$slider2,
        ]);
    }

    public function accountInfo()
    {
        $user=User::where('id',Auth::user()->id)->first();
        $countries=Country::where('status','active')->get();
        $cities=City::where('status','active')->get();
        return view('website.user.accountInfo', [
            'user' =>$user,
            'countries' =>$countries,
            'cities' =>$cities,
        ]);
    }


    public function myOrders()
    {

        $orders=Order::where('user_id',Auth::user()->id)->with('products')->orderBy('id','desc')->get();
       
        return view('website.user.myOdrers', [
            'orders' =>$orders,

         
        ]);
    }
    public function myOrdersByStatus($id)
    {
          if($id==0){
             $orders=Order::where('user_id',Auth::user()->id)->with('products')->orderBy('id','desc')->get();
          }else if($id==1){
              $orders=Order::where('user_id',Auth::user()->id)->where('status','<',2)->with('products')->orderBy('id','desc')->get();
          }else{
              $orders=Order::where('user_id',Auth::user()->id)->where('status','=',2)->with('products')->orderBy('id','desc')->get();
          }
            $view = view('website.includs.orderByStatus')->with(['orders'=>$orders])->render();
            return response()->json(['html' => $view]);
    }
    public function orderDetails($id)
    {

        $order=Order::with('products.product')->findOrFail($id);
   
        return view('website.user.orderDetails', [
            'order' =>$order,

         
        ]);
    }
    
        public function myPurchases()
    {
        $setting=Setting::all();
        $categories=Category::all(); 

        $orders=Order::with('products')->where('user_id',Auth::user()->id)->get();
       
     

        return view('website.user.myPurchases', [
            'categories' =>$categories,
            'orders' =>$orders,
            'setting' =>$setting,
         
        ]);
    }

    public function wishlist()
    {
  
        $fevorites=Fevorite::with('product')->where('user_id',Auth::user()->id)->get();
        $recom_products=Product::orderBy('created_at', 'desc')->where('status','active')->inRandomOrder(7)->limit(7)->get();
        return view('website.user.myWishlist', [
            'fevorites' =>$fevorites, 
            'recom_products' =>$recom_products, 

        ]);
    }
    public function myAddreses()
    {
        $cities=City::where('status','active')->get();
        $myAddreses=UserAddresse::where('user_id',Auth::user()->id)->with('user','city')->get();

      //  return $myAddreses;
        return view('website.user.myAddresses', [
            'myAddreses' =>$myAddreses, 
            'cities' =>$cities, 


        ]);
    }
    public function addAddreses(Request $request)
    {
  
        $rules = [
            'city' => 'required',
            'area' => 'required',
            'address' => 'required',
            'mobile' => 'required|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['code' => 300,
                'validator' =>implode("\n",$validator-> messages()-> all()) ]);
        }
        $address=new UserAddresse();
        $address->city_id=$request->city;
        $address->user_id=Auth::user()->id;
        $address->area=$request->area;
        $address->mobile=$request->mobile;
        $address->addres=$request->address ;
        $address->save();
        $myAddreses=UserAddresse::where('user_id',Auth::user()->id)->with('user')->get();
           $html=view('website.more.newAddress')->with(['myAddreses'=>$myAddreses])->render();
       
        return response()->json(['status' => true, 'code' => 200 ,'html'=>$html,'address'=>$address]);
    }
    
    public function editAdress(Request $request ,$id)
    {
  
        $rules = [
            'city' => 'required',
            'area' => 'required',
            'address' => 'required',
            'mobile' => 'required|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['code' => 300,
                'validator' =>implode("\n",$validator-> messages()-> all()) ]);
        }
        $address=UserAddresse::findOrFail($id);
        $address->city_id=$request->city;

        $address->area=$request->area;
        $address->mobile=$request->mobile;
        $address->addres=$request->address ;
        $address->save();
        $myAddreses=UserAddresse::where('user_id',Auth::user()->id)->with('user')->get();
           $html=view('website.more.newAddress')->with(['myAddreses'=>$myAddreses])->render();
       
        return response()->json(['status' => true, 'code' => 200 ,'html'=>$html]);
    }


   public function deletAdress($id)
    {
  
        $address=UserAddresse::where('id',$id)->where('user_id',Auth::user()->id)->delete();
      //  return $myAddreses;
             return ;
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required',
            'mobile' => 'required|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['code' => 200,
                'validator' =>implode("\n",$validator-> messages()-> all()) ]);
        }
          $user=User::findOrFail(auth()->user()->id);
          
          
          if($request->has('avatar')){
                if(isset(explode('/',explode(';', explode(',', $request->avatar)[0])[0])[1])){
                    $extension = strtolower(explode('/',explode(';', explode(',', $request->avatar)[0])[0])[1]);
                    if (in_array($extension,['jpg','png','jpeg'])) {
                        $avatar = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . ".jpg";
                        Image::make($request->avatar)->resize(800, null, function ($constraint) {$constraint->aspectRatio();})->save("uploads/images/users/$avatar");
                        $user->avatar = $avatar;
                    }
                }
            }
            
        
          $user->name=$request->input('name');
          $user->mobile=$request->input('mobile');
          $user->gender=$request->input('gender');
          $user->country_id=$request->input('country_id');
          $user->city_id=$request->input('city_id');
          $user->birthdate=$request->birthdate;

          if($request->oldPassword != '' &&$request->newPassword != '' && $request->confirmPassword != ''){
            $rule = [
                'oldPassword' => 'required|min:6',
                'newPassword' => 'required|min:6',
                'confirmPassword' => 'required|min:6|same:password',
            ];
            $validat = Validator::make($request->all(), $rule);
            if ($validat->fails()) {
                return response()->json(['code' => 300,
                    'validat' =>implode("\n",$validat-> messages()-> all()) ]);
            }

            $user->password =  Hash::make($request->newPassword);
        }

          $user->save();
           
          return response()->json(['status' => true, 'code' => 400]);
    } 


    public function addFavoriteOffer(Request $request , $id)
    {
        if(auth()->check()){
      if(Fevorite::where('user_id',Auth::user()->id)->where('product_id',$id)->exists())
        {
            
          return ['msg'=>__('website.already-in-fevorite') , 'status'=>0];

        }else{  
            $fevorite= new Fevorite();
            $fevorite->user_id=Auth::user()->id;
            $fevorite->product_id=$id;
            $fevorite->save();
          }
          
           if ($fevorite) {
            return ['status'=> __('website.ok'),'code'=>200];

           }
    }
    }

    public function deleteFromFavorit($id)
    {
       
        $item = Fevorite::where('user_id',Auth::user()->id)->where('product_id',$id)->first();
        if ($item) {
            Fevorite::where('user_id',Auth::user()->id)->where('product_id', $id)->delete();

            return ['status'=> __('website.ok')];
        }
        
        return "not Found";
    }

    
   
}
