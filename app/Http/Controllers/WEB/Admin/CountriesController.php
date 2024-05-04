<?php
namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Country;




class CountriesController extends Controller
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
        $countries = Country::orderBy('id', 'desc')->get();
        return view('admin.countries.home',['countries'=>$countries]);
    }

    public function create()
    {

        return view('admin.countries.create');
    }

    public function store(Request $request)
    {
        $roles = [
          //  'deliveryCost'=> 'required' ,

        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $country= new Country();
        foreach ($locales as $locale)
        {
            $country->translateOrNew($locale)->name = $request->get('name_' . $locale);
        }

        $country->save();
        return redirect()->back()->with('status', __('cp.create'));
    }


    public function show($id)
    {

    }


    public function edit($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.countries.edit');

    }

    public function update(Request $request, $id)
    {
        //
        $roles = [
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
        }
        $this->validate($request, $roles);


        $country = Country::query()->findOrFail($id);
        foreach ($locales as $locale)
        {
            $country->translateOrNew($locale)->name = $request->get('name_' . $locale);
        }


        $country->save();
        return redirect()->back()->with('status', __('cp.update'));
    }

    public function destroy($id)
    {
        $country = Country::query()->findOrFail($id);
        if ($country) {
            Country::query()->where('id', $id)->delete();
            return "success";
        }
        return "fail";
    }
}
