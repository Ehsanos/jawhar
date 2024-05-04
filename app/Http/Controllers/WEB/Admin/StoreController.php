<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Exports\StoreExport;

use App\Models\UserAddress;
use App\Models\City;
use App\Models\Country;
use App\Models\Setting;
use App\Models\StoreCategory;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Order;
use App\Models\News;
use App\Models\Store;
use App\Models\UserPermission;
use App\Models\Wifi;
use App\Models\User;
use App\Models\Token;
use App\Models\UserWallet;
use App\Models\Notifiy;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Language;

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

class StoreController extends Controller
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
        $items = Store::query();
        $admin = auth('admin')->user();

        $prem = UserPermission::where("user_id",$admin->id)->first();

        $boo = false;
        $boo1 = false;

        if($prem != null)
        {

            $userRoleItem=explode(',',$prem->permission);
            if($userRoleItem != null)
            {
                foreach ($userRoleItem as $permission) {
                    if($permission == "stores")
                    {

                        $boo = true;
                    }
                }
            }

            if($boo)
            {
                if($prem->store_id != "0")
                {
                    $items->where('id',$prem->store_id);
                }
                else
                {
                    $boo1 = true;
                }
            }

        }


        if ($admin->city_id > 0){
            $items->where('city_id',$admin->city_id);
        }
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
        $items = $items->paginate(30);
        
        

        return view('admin.stores.home', [
            'items' => $items,'boo' => $boo,'boo1' => $boo1
        ]);


    }
 

    public function create()
    {
        $admin = auth('admin')->user();

        $admin_city_id = -1;

        if($admin->id != 1)
        {
            $admin_city_id = $admin->city_id;
        }

        $cities = City::where('status','active')->get();
        $categories = StoreCategory::where('status','active')->get();
        return view('admin.stores.create',['cities' => $cities, 'categories'=>$categories , 'admin_city_id' => $admin_city_id]);
    }


    public function store(Request $request)
    {

        $name = $request->get('name');
        $store_name = $request->get('store_name');
        $email = $request->get('email');
        $mobile =convertAr2En($request->get('mobile')) ;
        $store_mobile =convertAr2En($request->get('store_mobile')) ;
        $password = bcrypt($request->get('password'));
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
       //     'mobile' => 'required|digits_between:8,12|unique:users',
            'password' => 'required|min:6',
            'store_name' => 'required',
            'store_mobile' => 'required',
            'logo' => 'required',
            'address' => 'required',
            'city_id' => 'required',
            'details' => 'required',
            // 'latitude' => 'required',
            // 'longitude' => 'required',
            'is_cash' => 'required',
            'is_wallet' => 'required',
            'is_online' => 'required',
            'is_delivery' => 'required',
            'is_pickup' => 'required',
            'type' => 'required',
            'app_percent' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $newUser = new User();
        $newUser->name = $name;
        $newUser->email = $email;
        $newUser->mobile = $mobile;
        $newUser->status = 'not_active';
        $newUser->type = $request->type; //store_owner
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
            $newStore = new Store();

            if ($request->all_cities == "on") {
                $newStore->all_cities = 1;
            }else{
                $newStore->all_cities =0;
            }
            $newStore->user_id = $newUser->id;
            $newStore->type = $request->type; //store_owner
            $newStore->store_name = $store_name;
            $newStore->mobile = $store_mobile;
            $newStore->address = $request->address;
            $newStore->city_id = $request->city_id;
            $newStore->store_category_id = $request->store_category_id;
            $newStore->details = $request->details;
            $newStore->latitude = $request->lat;
            $newStore->longitude = $request->lng;
            $newStore->is_cash = $request->is_cash;
            $newStore->app_percent = $request->app_percent;
            $newStore->reNewNetwork_percent = $request->reNewNetwork_percent;
            $newStore->is_wallet = $request->is_wallet;
            $newStore->is_online = $request->is_online;
            $newStore->is_delivery = $request->is_delivery;
            $newStore->is_pickup = $request->is_pickup;

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $extention = $logo->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($logo)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/stores/$file_name");
            $newStore->logo = $file_name;
        }
            $newStore->save();
            if($request->type == 2){
                //Full texts	id	name	city_id	store_id	image	Status	created_at
                $newWifi = new Wifi();
        $newWifi->name = $store_name;  
        $newWifi->city_id = $request->city_id;  
        $newWifi->store_id = $newStore->id;  
        $newWifi->status = 'active';  
        $newWifi->save();
            }

        return redirect()->back()->with('status', __('cp.create'));

    }
    }


    public function chat($id)
    {

        $item = Store::findOrFail($id);

        return view('admin.stores.chat',[
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
    
        public function storeWallet($id)
    {

        $user = Store::findOrFail($id);
        $items = UserWallet::where('user_id',$user->user_id)->get();
 // return $user;
        return view('admin.stores.storeWallet',[
            'items'=>$items , 'user'=>$user
        ]);
    }
    
        public function storeOrders($id )
    {

        $user = Store::findOrFail($id);
        $items = Order::where('store_id',$id)->get();
 // return $user;
        return view('admin.stores.storeOrders',[
            'items'=>$items , 'user'=>$user
        ]);
    }
        public function storeCategories($id )
    {

        $user = Store::findOrFail($id);
        $items = Category::where('store_id',$id)->get();
 // return $user;
        return view('admin.stores.storeCategories',[
            'items'=>$items , 'user'=>$user
        ]);
    }
        public function storeProducts(Request $request, $id)
    {

        $user = Store::findOrFail($id);
  $products = Product::query();
        
        if ($request->has('categoryId') ) {
            if ($request->get('categoryId') != null)
            {
                $products->where('category_id', $request->get('categoryId'));
            }

        }
        
        if ($request->has('available') ) {
            if ($request->get('available') != null)
            {
                $products->where('available', $request->get('available'));
            }

        }
        $products = $products->where('store_id', $id)->orderByDesc('id')->paginate(20);//get(); 
        return view('admin.stores.storeProducts',[
            'products'=>$products , 'user'=>$user
        ]);
    }
    
        public function createStoreProduct($id)
    {
        //
        $categories = Category::where('status','active')->where('store_id',$id)->get();
        return view('admin.stores.createStoreProduct', [
            'categories' => $categories ,
            'subCategory' =>$subcategory,
            'id'=>$id
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeStoreProduct(Request $request)
    {
//        return $request;
        //
        $roles = [

            'price'  => 'required |numeric',
            'sub_category_id'   => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif',
            // 'offer_from' => 'required_unless:discount,0',
            // 'offer_to' => 'required_unless:discount,0' ,

        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

//return $request;
        $product= new Product();
        $product->store_id = $request->store_id;
        $product->price = $request->price;
        $product->category_id =$request->category ;
        $product->discount =$request->discount ;
        $product->offer_from = $request->offer_from;
        $product->offer_to = $request->offer_to;
        $product->subCategory_id = $request->sub_category_id;
        $product->is_dollar = $request->is_dollar;
        $product->count = $request->product_count;
       if ($request->has('top_selling')){
        $product->most_selling = $request->top_selling;
       }
       if ($request->has('newest')){
            $product->newest = $request->newest;
       }

        foreach ($locales as $locale)
        {
            $product->translateOrNew($locale)->name = $request->get('name_' . $locale);
            $product->translateOrNew($locale)->description = $request->get('description_' . $locale);

        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/products/$file_name");
            $product->image = $file_name;
        }
        $product->save();

 if($request->has('filename')  && !empty($request->filename))
        {
           foreach($request->filename as $one)
           {
               if (isset(explode('/', explode(';', explode(',', $one)[0])[0])[1])) {
                    $fileType = strtolower(explode('/', explode(';', explode(',', $one)[0])[0])[1]);
                    $name = auth()->guard('admin')->user()->id. "_" .str_random(8) . "_" .  "_" . time() . "_" . rand(1000000, 9999999);
                    $attachType = 0;
                    if (in_array($fileType, ['jpg','jpeg','png','pmb'])){
                        $newName = $name. ".jpg";
                        $attachType = 1;
                        Image::make($one)->resize(800, null, function ($constraint) {$constraint->aspectRatio();})->save("uploads/images/products/$newName");
                    }
                    $product_image=new ProductImage();
                    $product_image->product_id = $product->id;
                    $product_image->product_img = $newName;
                    $product_image->save();
                }
           }
        }

        return redirect()->back()->with('status', __('cp.create'));
    }
    
        public function storeNews($id)
    {

        $user = Store::findOrFail($id);
        $items = News::where('store_id',$id)->get();
 // return $user;
        return view('admin.stores.storeNews',[
            'items'=>$items , 'user'=>$user
        ]);
    }
        public function storeSliders($id)
    {

        $user = Store::findOrFail($id);
        $items = News::where('store_id',$id)->get();
 // return $user;
        return view('admin.stores.storeSliders',[
            'sliders'=>$items , 'user'=>$user
        ]);
    }
    
    

    public function show($id)
    {
        $store = Store::findOrFail($id);
        $user = User::where('id',$store->user_id)->first();

        return view('admin.stores.show',[
            'store'=>$store ,
            'user'=>$user ,
        ]);
    }

    public function edit($id)
    {
        $admin = auth('admin')->user();

        $admin_city_id = -1;

        if($admin->id != 1)
        {
            $admin_city_id = $admin->city_id;
        }

        $cities = City::where('status','active')->get();
        $categories = StoreCategory::where('status','active')->get();

        $store = Store::findOrFail($id);
        $user = User::where('id',$store->user_id)->first();

        return view('admin.stores.edit',[
            'store'=>$store ,
            'user'=>$user ,
            'cities' => $cities, 
            'categories'=>$categories,
            'admin_city_id'=>$admin_city_id
        ]);
    }


    public function update(Request $request, $id)
    {
        $store= Store::findOrFail($id);
        $user= User::where('id',$store->user_id)->first();
       $name = $request->get('name');
        $store_name = $request->get('store_name');
        $email = $request->get('email');
        $mobile =convertAr2En($request->get('mobile')) ;
        $store_mobile =convertAr2En($request->get('store_mobile')) ;
        $password = bcrypt($request->get('password'));
        $validator = Validator::make($request->all(), [
         //   'name' => 'required',
        //    'email' => 'required|email|unique:users',
       //     'mobile' => 'required|digits_between:8,12|unique:users',
        //    'password' => 'required|min:6',
            'store_name' => 'required',
            'store_mobile' => 'required',
         //   'logo' => 'required',
            'address' => 'required',
            'city_id' => 'required',
            'details' => 'required',
            // 'latitude' => 'required',
            // 'longitude' => 'required',
            'is_cash' => 'required',
            'is_wallet' => 'required',
            'is_online' => 'required',
            'is_delivery' => 'required',
            'is_pickup' => 'required',
            'type' => 'required',
            'app_percent' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
      
        $user->save();
          if ($user) {
              if ($request->all_cities == "on") {
                  $store->all_cities = 1;
              }else{
                  $store->all_cities =0;
              }
            $store->type = $request->type; //store_owner
            $store->store_name = $store_name;
            $store->mobile = $store_mobile;
            $store->address = $request->address;
            $store->city_id = $request->city_id;
            $store->store_category_id = $request->store_category_id;
            $store->details = $request->details;
            $store->latitude = $request->lat;
            $store->longitude = $request->lng;
            $store->is_cash = $request->is_cash;
            $store->app_percent = $request->app_percent;
            $store->reNewNetwork_percent = $request->reNewNetwork_percent;
            $store->is_wallet = $request->is_wallet;
            $store->is_online = $request->is_online;
            $store->is_delivery = $request->is_delivery;
            $store->is_pickup = $request->is_pickup;

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $extention = $logo->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($logo)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/stores/$file_name");
            $store->logo = $file_name;
        }
            $store->save();
        }
        return redirect()->back()->with('status', __('cp.update'));
    }



    public function edit_password(Request $request, $id)
    {
        $item = Store::findOrFail($id);
        return view('admin.stores.edit_password',['item'=>$item]);
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
        $user = Store::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back()->with('status', __('cp.update'));
    }

    public function export() 
    {
    
        return Excel::download(new StoresExport, 'allStores.xlsx');
    }

    public function update_status_cart(Request $request, $id)
    {
        $store= Store::findOrFail($id);
        if ($store->status_cart == 1)
        {
            $store->status_cart = 0;
        }
        else
        {
            $store->status_cart = 1;
        }

        $store->save();
        return redirect()->back()->with('status', __('cp.update'));
    }




}
