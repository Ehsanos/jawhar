<?php
namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use Image;
use App\Models\Language;
use App\Models\Setting;
use App\Models\Ad;


class AdsController extends Controller
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
        $ads = Ad::orderBy('id', 'desc')->get();
        return view('admin.ads.home', [
            'ads' =>$ads,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.ads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $roles = [
          //  'link' => 'required|url',
            'image' => 'required|image|mimes:jpeg,jpg,png',

        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['details_' . $locale] = 'required';
            $roles['title_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $ad= new Ad();
        $ad->link = $request->link;

        foreach ($locales as $locale)
        {
            $ad->translateOrNew($locale)->details = $request->get('details_' . $locale);
            $ad->translateOrNew($locale)->title = $request->get('title_' . $locale);
        }
        if ($request->hasFile('image')) {
            $logo = $request->file('image');
            $extention = $logo->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($logo)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/ads/".$file_name);
            $ad->image = $file_name;
        }
        $ad->save();
        return redirect()->back()->with('status', __('cp.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        //
        $item = Ad::findOrFail($id);
        return view('admin.ads.edit', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $roles = [
        //    'link' => 'required|url',
         //   'image' => 'required|image|mimes:jpeg,jpg,png',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['details_' . $locale] = 'required';
            $roles['title_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $ad = Ad::query()->findOrFail($id);
        $ad->link = $request->get('link');
        foreach ($locales as $locale)
        {
            $ad->translateOrNew($locale)->details = $request->get('details_' . $locale);
            $ad->translateOrNew($locale)->title = $request->get('title_' . $locale);
        }

        if ($request->hasFile('image')) {
            $logo = $request->file('image');
            $extention = $logo->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($logo)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/ads/".$file_name);
            $ad->image = $file_name;
        }

        $ad->save();
        return redirect()->back()->with('status', __('cp.update'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $ad = Ad::query()->findOrFail($id);
        if ($ad) {
            Ad::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }
    

        public function sorting()
    {

        $items = Ad::where('status','active')->orderBy('ordering','asc')->get();
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
              $department=Ad::where('id',$one)->first();
              if($department){
                 $department->ordering=$index+1;
                 $department->save(); 
              }
          }
        return redirect()->back()->with('status', __('cp.update'));
    }    
}
