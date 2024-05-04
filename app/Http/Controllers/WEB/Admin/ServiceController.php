<?php

namespace App\Http\Controllers\WEB\Admin;


use App\Models\Azkar;
use App\Models\Service;
use App\Models\ServiceImage;
use App\Models\Category;
use App\Models\Language;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Subcategory;
use App\Models\User;
use DemeterChain\C;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;

class ServiceController extends Controller
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
    public function index(Request $request)
    {
        $service = Service::all();


        return view('admin.services.home', [
            'service' => $service,
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

        return view('admin.services.create');

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
//
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';

        }
        $this->validate($request, $roles);


        $service= new Service();

        $service->name =$request->name;

        foreach ($locales as $locale)
        {
            $service->translateOrNew($locale)->name = $request->get('name_' . $locale);
        }

        $service->save();

        return redirect()->back()->with('status', __('cp.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $service = Service::findOrFail($id);

        return view('admin.services.edit', [
            'service' => $service ,

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
    {    $roles = [

      //  'image' => 'required|image|mimes:jpeg,jpg,png,gif',

    ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
         //   $roles['details_' . $locale] = 'required';
        }
        $this->validate($request, $roles);


        $service= Service::query()->findOrFail($id);;

        $service->name =$request->name;
   //     $azkar->details =$request->details;



        foreach ($locales as $locale)
        {
            $service->translateOrNew($locale)->name = $request->get('name_' . $locale);
        //    $azkar->translateOrNew($locale)->details = $request->get('details_' . $locale);

        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/azkar/$file_name");
            $service->image = $file_name;
        }

        $service->save();

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
        $item = Azkar::query()->findOrFail($id);
        if ($item) {
            Azkar::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }


}
