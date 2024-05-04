<?php
namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Image;
use App\Models\Language;
use App\Models\Setting;
use App\Models\DistributionPoint;
use App\Models\City;


class DistributionPointsController extends Controller
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
        view()->share([
            'locales' => $this->locales,
            'settings' => $this->settings,

        ]);
    }
    public function index()
    {
        $items = DistributionPoint::orderBy('id', 'desc')->get();
        return view('admin.distribution_points.home', [
            'items' =>$items,
        ]);
    }


    public function create()
    {
        $cities = City::where('status','active')->get();
        return view('admin.distribution_points.create',['cities'=>$cities]);
    }


    public function store(Request $request)
    {
        //
        $roles = [
           'name' => 'required',
           'mobile' => 'required',
           'city_id' => 'required',
           'latitude' => 'required',
           'longitude' => 'required',
           
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            // $roles['details_' . $locale] = 'required';
            // $roles['title_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $ad= new DistributionPoint();
        $ad->name = $request->name;
        $ad->mobile = $request->mobile;
        $ad->city_id = $request->city_id;
        $ad->latitude = $request->latitude;
        $ad->longitude = $request->longitude;


        if ($request->hasFile('image')) {
            $logo = $request->file('image');
            $extention = $logo->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($logo)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uplodistribution_points/images/ads/".$file_name);
            $ad->image = $file_name;
        }
        $ad->save();
        return redirect()->back()->with('status', __('cp.create'));
    }


    public function edit($id)
    {
        $cities = City::where('status','active')->get();
        $item = DistributionPoint::findOrFail($id);
        return view('admin.distribution_points.edit', [
            'item' => $item,
            'cities'=>$cities,
        ]);
    }


    public function update(Request $request, $id)
    {
        //
        $roles = [
        //    'link' => 'required|url',
         //   'image' => 'required|image|mimes:jpeg,jpg,png',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            // $roles['details_' . $locale] = 'required';
            // $roles['title_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $ad = DistributionPoint::query()->findOrFail($id);
        $ad->name = $request->name;
        $ad->mobile = $request->mobile;
        $ad->city_id = $request->city_id;
        $ad->latitude = $request->latitude;
        $ad->longitude = $request->longitude;


        $ad->save();
        return redirect()->back()->with('status', __('cp.update'));
    }


    

        public function sorting()
    {

        $items = DistributionPoint::where('status','active')->orderBy('ordering','asc')->get();
        return view('admin.ads.sorting', [
            'items' => $items
        ]);
    }  
      public function sort(Request $request)
    {
       //   $str =substr($request->inputArrayproducts, 0, strlen($request->inputArrayproducts)-2);
            $f= explode(',',$request->inputArrayproducts);
          foreach($f as  $index=> $one){
              $ids= explode(',',$one);
              $department=DistributionPoint::where('id',$one)->first();
              if($department){
                 $department->ordering=$index+1;
                 $department->save(); 
              }
          }
        return redirect()->back()->with('status', __('cp.update'));
    }    
}
