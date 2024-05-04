<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Models\Store;
use App\Models\UserPermission;
use App\Models\Wifi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Image;
use App\Models\Language;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Product;


class SliderController extends Controller
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

    public function index($my_store_id=-1)
    {
        $sliders = Slider::query();

        if($my_store_id != -1)
        {
            $sliders = $sliders->where("store_id",$my_store_id);
        }
        else {
            $admin = auth('admin')->user();
            if ($admin->id != 1) {
                $admin_perm = UserPermission::where('user_id', $admin->id)->first();
                $sliders = $sliders->where("store_id", $admin_perm->store_id);
            } else
                if ($admin->id == 1) {
                    $sliders = $sliders->where("store_id", 0);
                }
        }

        $sliders = $sliders->orderBy('ordering', 'asc')->get();

        return view('admin.sliders.home', [
            'sliders' => $sliders,
        ]);
    }

    public function create(Request $request)
    {
        $products = Product::where('status', 'active');

        if(isset($request->my_store_id) && $request->my_store_id != "")
        {
            $products = $products->where("store_id",$request->my_store_id);
        }
        else
        {
            $admin = auth('admin')->user();

            if($admin->id != 1)
            {
                $admin_perm = UserPermission::where('user_id',$admin->id)->first();

                if (isset($admin_perm->store_id) && $admin_perm->store_id > 0)
                {
                    $products = $products->where("store_id",$admin_perm->store_id);
                }
            }
            else
            {
                $products = $products->where("store_id",0);
            }

        }

        $products = $products->get();

        return view('admin.sliders.create', [
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $roles = [
            'image' => 'required|image|mimes:jpeg,jpg,png',
            //  'type' => 'required',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            //    $roles['details_' . $locale] = 'required';
            $roles['title_' . $locale] = 'required';
        }
        if($request->type == 1)
        {
            $roles['product_id'] = 'required';
        }
        if($request->type == 2)
        {
            $roles['link'] = 'required';
        }
        $this->validate($request, $roles);

        $sliders = new Slider();

        foreach ($locales as $locale) {
            //   $sliders->translateOrNew($locale)->details = $request->get('details_' . $locale);
            $sliders->translateOrNew($locale)->title = $request->get('title_' . $locale);
        }
        if ($request->hasFile('image')) {
            $logo = $request->file('image');
            $extention = $logo->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($logo)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/ads/" . $file_name);
            $sliders->image = $file_name;
        }
        if ($request->has('link')) {
            $sliders->link = $request->link;
        }
        if ($request->has('product_id')) {
            $sliders->product_id = $request->product_id;
        }
        if ($request->has('type')) {
            $sliders->type = $request->type;
        } else {
            $sliders->type = 0;
        }
        if($request->my_store_id != "")
        {
            $sliders->store_id = $request->my_store_id;
        }
        else {
            $admin = auth('admin')->user();
            if ($admin->id != 1) {
                $admin_perm = UserPermission::where('user_id', $admin->id)->first();
                $sliders->store_id = $admin_perm->store_id;
            }
        }

        $sliders->save();
        return redirect()->back()->with('status', __('cp.create'));
    }

    public function edit($id)
    {
        $item = Slider::findOrFail($id);

        $products = Product::where('status', 'active');

        if(isset($item->store_id) && $item->store_id != "")
        {
            $products = $products->where("store_id",$item->store_id);
        }

        $products = $products->get();

        return view('admin.sliders.edit', [
            'item' => $item,
            'products' => $products,
        ]);
    }

    public function update(Request $request, $id)
    {

        $roles = [
            //     'type' => 'required',
               'image' => 'image|mimes:jpeg,jpg,png',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            //     $roles['details_' . $locale] = 'required';
            $roles['title_' . $locale] = 'required';
        }
        if($request->type == 1)
        {
            $roles['product_id'] = 'required';
        }
        if($request->type == 2)
        {
            $roles['link'] = 'required';
        }
        $this->validate($request, $roles);

        $sliders = Slider::query()->findOrFail($id);
        $sliders->link = $request->get('link');
        foreach ($locales as $locale) {
            //   $sliders->translateOrNew($locale)->details = $request->get('details_' . $locale);
            $sliders->translateOrNew($locale)->title = $request->get('title_' . $locale);
        }

        if ($request->hasFile('image')) {
            $logo = $request->file('image');
            $extention = $logo->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($logo)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/ads/" . $file_name);
            $sliders->image = $file_name;
        }
        if ($request->has('link')) {
            $sliders->link = $request->link;
        }
        if ($request->has('product_id')) {
            $sliders->product_id = $request->product_id;
        }
        if ($request->has('type')) {
            $sliders->type = $request->type;
        }else {
            $sliders->type = 0;
        }

        $admin = auth('admin')->user();
        if ($admin->id != 1) {
            $admin_perm = UserPermission::where('user_id', $admin->id)->first();
            $sliders->store_id = $admin_perm->store_id;
        }

        $sliders->save();
        return redirect()->back()->with('status', __('cp.update'));
    }

    public function destroy($id)
    {
        //
        $sliders = Slider::query()->findOrFail($id);
        if ($sliders) {
            Slider::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }

    public function sorting($my_store_id="")
    {
        $items = Slider::query();

        if($my_store_id != "")
        {
            $items = $items->where("store_id",$my_store_id);
        }
        else {
            $admin = auth('admin')->user();
            if ($admin->id != 1) {
                $admin_perm = UserPermission::where('user_id', $admin->id)->first();
                $items = $items->where("store_id", $admin_perm->store_id);
            } else
                if ($admin->id == 1) {
                    $items = $items->where("store_id", 0);
                }
        }

        $items = $items->where('status', 'active')->orderBy('ordering', 'asc')->get();
        return view('admin.sliders.sorting', [
            'items' => $items
        ]);
    }

    public function sort(Request $request)
    {
        //   $str =substr($request->inputArrayproducts, 0, strlen($request->inputArrayproducts)-2);
        $f = explode(',', $request->inputArrayproducts);
        foreach ($f as $index => $one) {
            $ids = explode(',', $one);
            $department = Slider::where('id', $one)->first();
            if ($department) {
                $department->ordering = $index + 1;
                $department->save();
            }
        }
        return redirect()->back()->with('status', __('cp.update'));
    }

}
