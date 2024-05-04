<?php
namespace App\Http\Controllers\WEB\Admin;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Institute;
use App\Models\City;




class InstituteController extends Controller
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
        $institutes = null;

        if(is_jawhar_user())
        {
            $institutes = Institute::orderBy('id', 'desc')->get();
        }
        else {

            if (is_one_institute()) {
                $institutes = Institute::where("city_id", get_user_city_id())->orderBy('id', 'desc')->get();
            } else {
                if (get_one_institute() != 0) {
                    $institutes = Institute::where("city_id", get_user_city_id())->where("id", get_one_institute())->orderBy('id', 'desc')->get();
                } else {
                    $institutes = Institute::where("city_id", get_user_city_id())->orderBy('id', 'desc')->get();

                }

            }
        }

        return view('admin.institutes.home', [
            'institutes' => $institutes ,
        ]);
    }

    public function create()
    {
        $cities = null;

        if(is_jawhar_user())
        {
            $cities = City::where('status','active')->get();
        }
        else {

            if (is_one_institute()) {
                $cities = City::where("id", get_user_city_id())->where('status','active')->get();

            } else {
                if (get_one_institute() != 0) {
                    $cities = City::where("id", get_user_city_id())->where('status','active')->get();
                } else {
                    $cities = City::where("id", get_user_city_id())->where('status','active')->get();

                }

            }
        }
        $user = User::orderBy('id', 'desc')->get();

        return view('admin.institutes.create',['cities'=>$cities ,'user'=>$user]);
    }

    public function edit($id)
    {

        $cities = null;

        if(is_jawhar_user())
        {
            $cities = City::where('status','active')->get();
        }
        else {

            if (is_one_institute()) {
                $cities = City::where("id", get_user_city_id())->where('status','active')->get();

            } else {
                if (get_one_institute() != 0) {
                    $cities = City::where("id", get_user_city_id())->where('status','active')->get();
                } else {
                    $cities = City::where("id", get_user_city_id())->where('status','active')->get();

                }

            }
        }
        $user = User::orderBy('id', 'desc')->get();
        $item = Institute::findOrFail($id);
        return view('admin.institutes.edit', [
            'item' => $item,
            'cities'=>$cities,
             'user'=>$user
        ]);

    }

       public function store(Request $request)
    {
        //
        $roles = [
           'name' => 'required',
           'app_percent' => 'required',
           'city_id' => 'required',
            'user_id' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png',

        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
          //  $roles['details_' . $locale] = 'required';
        }
        $this->validate($request, $roles);
        $institute= new Institute();
        $institute->name = $request->name;
        $institute->app_percent = $request->app_percent;
        $institute->user_id = $request->user_id;
        $institute->city_id = $request->city_id;

        foreach ($locales as $locale)
        {
           // $ad->translateOrNew($locale)->details = $request->get('details_' . $locale);
        }
        if ($request->hasFile('image')) {
            $logo = $request->file('image');
            $extention = $logo->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($logo)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/institutes/".$file_name);
            $institute->image = $file_name;
        }
        $institute->save();
        return redirect()->back()->with('status', __('cp.create'));
    }


    public function update(Request $request, $id)
    {
           $roles = [
          'name' => 'required',
          'app_percent' => 'required',
          'user_id' => 'required',
          'city_id' => 'required',
       //  'image' => 'required|image|mimes:jpeg,jpg,png',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
          //  $roles['details_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $institute = Institute::query()->findOrFail($id);
        $institute->name = $request->get('name');
        $institute->app_percent = $request->get('app_percent');
        $institute->user_id = $request->get('user_id');
        $institute->city_id = $request->get('city_id');
        foreach ($locales as $locale)
        {
           // $ad->translateOrNew($locale)->details = $request->get('details_' . $locale);
        }

        if ($request->hasFile('image')) {
            $logo = $request->file('image');
            $extention = $logo->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($logo)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/institutes/".$file_name);
            $institute->image = $file_name;
        }

        $institute->save();
        return redirect()->back()->with('status', __('cp.update'));
    }


}
