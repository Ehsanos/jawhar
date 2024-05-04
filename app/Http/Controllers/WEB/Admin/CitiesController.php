<?php
namespace App\Http\Controllers\WEB\Admin;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WEB\Admin;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\City;




class CitiesController extends Controller
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
    public function index()
    {
        $cities = City::orderBy('id', 'desc')->get();
        return view('admin.cities.home',['cities'=>$cities]);
    }

    public function create()
    {
        $countries = Country::orderBy('id', 'desc')->get();
        return view('admin.cities.create',['countries'=>$countries]);
    }

    public function store(Request $request)
    {
        $roles = [
            'country'=> 'required' ,

        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $cities= new City();
        $cities->country = $request->get('country');
        foreach ($locales as $locale)
        {
            $cities->translateOrNew($locale)->name = $request->get('name_' . $locale);
        }

        $cities->save();
        return redirect()->back()->with('status', __('cp.create'));
    }


    public function show($id)
    {

    }


    public function edit($id)
    {
        $cities = City::findOrFail($id);
        $countries = Country::orderBy('id', 'desc')->get();
        return view('admin.cities.edit', [
            'cities' => $cities,'countries'=>$countries] );
    }

    public function update(Request $request, $id)
    {
        //
        $roles = [
            'country'=> 'required' ,
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
        }
        $this->validate($request, $roles);


        $cities = City::query()->findOrFail($id);
        $cities->country = $request->get('country');
        foreach ($locales as $locale)
        {
            $cities->translateOrNew($locale)->name = $request->get('name_' . $locale);
        }


        $cities->save();
        return redirect()->back()->with('status', __('cp.update'));
    }

    public function destroy($id)
    {
        $cities = City::query()->findOrFail($id);
        if ($cities) {
            City::query()->where('id', $id)->delete();
            return "success";
        }
        return "fail";
    }
}
