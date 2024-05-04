<?php

namespace App\Http\Controllers\WEB\Admin;


use App\Models\Azkar;
use App\Models\ProductService;
use App\Models\ProductServiceRequest;
use App\Models\ServiceImage;
use App\Models\Category;
use App\Models\Language;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Subcategory;
use App\Models\ServiceCards;
use App\Models\UserPermission;
use App\Models\WhatsappUsers;
use App\Models\User;
use DemeterChain\C;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;

class ProductServiceController extends Controller
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
    
    public function getCards($id)
    {
              $adminId = auth()->guard('admin')->user()->id;
 if ($adminId != 1){        
                     $serviceCards = ServiceCards::where('product_id',$id)->where('admin_id',$adminId)->where('is_used',0)->get();
                    }
 else{
       $serviceCards = ServiceCards::where('product_id',$id)->where('is_used',0)->get();

 }
        $service = Service::all();
        $productService = ProductService::all();
      
        return view('admin.productServices.cards', [
            'serviceCards' => $serviceCards,
            'service' => $service,
            'productService' => $productService,
            'id' => $id,
        ]);
    }

    public function addCards(Request $request,$id)
    {
      $adminId = auth()->guard('admin')->user()->id;
        if ($adminId != 1){
        $productService = ProductService::where('admin_id',$adminId)->findOrFail($id);
        }
        else{
           $productService = ProductService::findOrFail($id); 
        }
        return view('admin.productServices.addCards',[
              'productService' => $productService,
        ]);
    }

    public function storeCards(Request $request,$id)
    {
      
        $productService = ProductService::findOrFail($id);
        $roles = [

  

        ];
        
        $this->validate($request, $roles);


        $serviceCards = new ServiceCards();

        $serviceCards->card_id =$request->card_id;
        $serviceCards->admin_id =auth()->guard('admin')->user()->id;
        $serviceCards->pin =$request->pin;
        $serviceCards->password =$request->password;
        $serviceCards->service_id =$productService->service_id;
        $serviceCards->product_id =$id;
        $serviceCards->save();

        return redirect()->back()->with('status', __('cp.create'));
    }    
    
    public function index(Request $request)
    {
        $adminId = auth()->guard('admin')->user()->id;
        if ($adminId != 1){
            $service = ProductService::query()->where('admin_id',$adminId)->withCount('serviceCards')->get();
        }  
        else{
            $AdminPermission = UserPermission::where('user_id', $adminId)->first();

            if(isset($AdminPermission->product_services_id) && $AdminPermission->product_services_id == "1")
            {
                $service = ProductService::query()->where('admin_id',$adminId)->withCount('serviceCards')->get();
            }
            else
            {
                $service = ProductService::query()->withCount('serviceCards')->get();
            }

        }

        return view('admin.productServices.home', [
            'service' => $service,
        ]);
    }

    public function create()
    {
        $items = Service::all();
        $whatsapp = WhatsappUsers::all();
        return view('admin.productServices.create',[
            'items'=>$items,
            'whatsapp'=>$whatsapp,
        ]);

    }

    public function store(Request $request)
    {
        $roles = [

            'image' => 'required|image|mimes:jpeg,jpg,png,gif',
            'price' => 'required',
            'purchasing_price' => 'required',
            'service' => 'required',

        ];

        if($request->wapp_status == "on")
        {
            $roles['wapp_id'] = 'required|integer';
        }
        else
        if($request->service_worker_status == "on")
        {
            $roles['wapp_id_service_worker'] = 'required|integer';
        }

        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';

        }
        $this->validate($request, $roles);

        $adminId = auth()->guard('admin')->user()->id;

        if ($adminId != 1){
            $AdminPermission = UserPermission::where('user_id', $adminId)->first();
        }

        $item = new ProductService();

        if ($request->wapp_status == "on" && $request->has('wapp_id')) {
            $item->wapp_id = $request->wapp_id;
            $item->wapp_status = 1;
            $item->wapp_id_service_worker = null;
            $item->service_worker_status = 0;
        }
        else
            if ($request->service_worker_status == "on" && $request->has('wapp_id_service_worker')) {
                $item->wapp_id = null;
                $item->wapp_status = 0;
                $item->wapp_id_service_worker = $request->wapp_id_service_worker;
                $item->service_worker_status = 1;
            }


        $item->name =$request->name;
        $item->admin_id =auth()->guard('admin')->user()->id;
        if(isset($AdminPermission->product_services_id) && $AdminPermission->product_services_id == "1")
        {
            $item->city_id =0;
        }
        else
        {
            $item->city_id =auth()->guard('admin')->user()->city_id;
        }
        $item->price =$request->price;
        $item->purchasing_price =$request->purchasing_price;
        $item->is_dollar = $request->is_dollar;
        
        $item->service_id =$request->service;

        foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->name = $request->get('name_' . $locale);
        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/productServices/$file_name");
            $item->image = $file_name;
        }

        $item->save();

        return redirect()->back()->with('status', __('cp.create'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {

        $items = ProductService::findOrFail($id);
        $whatsapp = WhatsappUsers::all();
        return view('admin.productServices.edit', [
            'items' => $items ,
            'whatsapp'=>$whatsapp,
        ]);

    }

    public function update(Request $request, $id)
    {
        $all_reqs = ProductServiceRequest::where("product_service_id",$id)->where("status","0")->get();

        if(count($all_reqs) > 0){
            return redirect()->back()->with('error', "هنالك عمليات معلقة لهذه الخدمة يجب انهائها قبل التعديل");
        }
        else {


            $roles = [

                //   'image' => 'required|image|mimes:jpeg,jpg,png,gif',
                'price' => 'required',
                'purchasing_price' => 'required',
            ];

            if ($request->wapp_status == "on") {
                $roles['wapp_id'] = 'required|integer';
            } else
                if ($request->service_worker_status == "on") {
                    $roles['wapp_id_service_worker'] = 'required|integer';
                }

            $locales = Language::all()->pluck('lang');
            foreach ($locales as $locale) {
                $roles['name_' . $locale] = 'required';
                //      $roles['details_' . $locale] = 'required';
            }
            $this->validate($request, $roles);

            $adminId = auth()->guard('admin')->user()->id;

            if ($adminId != 1) {
                $AdminPermission = UserPermission::where('user_id', $adminId)->first();
            }

            $productService = ProductService::query()->findOrFail($id);;

            if ($request->wapp_status == "on" && $request->has('wapp_id')) {
                $productService->wapp_id = $request->wapp_id;
                $productService->wapp_status = 1;
                $productService->wapp_id_service_worker = null;
                $productService->service_worker_status = 0;
            } else {
                $productService->wapp_id = null;
                $productService->wapp_status = 0;
            }

            if ($request->service_worker_status == "on" && $request->has('wapp_id_service_worker')) {
                $productService->wapp_id = null;
                $productService->wapp_status = 0;
                $productService->wapp_id_service_worker = $request->wapp_id_service_worker;
                $productService->service_worker_status = 1;
            } else {
                $productService->wapp_id_service_worker = null;
                $productService->service_worker_status = 0;
            }


            $productService->name = $request->name;
            //  $azkar->details =$request->details;


            foreach ($locales as $locale) {
                $productService->translateOrNew($locale)->name = $request->get('name_' . $locale);
                //     $productService->translateOrNew($locale)->details = $request->get('details_' . $locale);

            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $extention = $image->getClientOriginalExtension();
                $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
                Image::make($image)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save("uploads/images/productServices/$file_name");
                $productService->image = $file_name;
            }

            $productService->price = $request->price;
            $productService->purchasing_price = $request->purchasing_price;
            $productService->is_dollar = $request->is_dollar;

            if (isset($AdminPermission->product_services_id) && $AdminPermission->product_services_id == "1") {
                $productService->city_id = 0;
            } else {
                $productService->city_id = auth()->guard('admin')->user()->city_id;
            }

            $productService->save();

            return redirect()->back()->with('status', __('cp.update'));
        }
    }

    public function destroy($id)
    {
        //
        $item = ProductService::query()->findOrFail($id);
        if ($item) {
            ProductService::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }


}
