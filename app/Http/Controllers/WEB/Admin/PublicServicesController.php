<?php
namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\PublicService;
use App\Models\City;




class PublicServicesController extends Controller
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
        $data = null;

        if(is_jawhar_user())
        {
            $data = PublicService::orderBy('parent_id', 'asc')->get();
        }
        else {

            if (is_one_public_services()) {
                $data = PublicService::where("city_id", get_user_city_id())->orderBy('parent_id', 'asc')->get();
            } else {
                if (get_one_public_services() != 0) {
                    if (is_one_public_services_root())
                    {
                        $data = PublicService::where("city_id", get_user_city_id())->where(function ($q) {
                            $q->where("id", get_one_public_services())->orwhere("parent_id", get_one_public_services());
                        })->orderBy('parent_id', 'asc')->get();
                    }
                    else
                    {
                        $data = PublicService::where("city_id", get_user_city_id())->where("id", get_one_public_services())->orderBy('parent_id', 'asc')->get();
                    }
                } else {
                    $data = PublicService::where("city_id", get_user_city_id())->orderBy('parent_id', 'asc')->get();

                }

            }
        }

        return view('admin.publicServices.home', [
            'publicServices' => $data ,
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

            if (is_one_public_services()) {
                $cities = City::where("id", get_user_city_id())->where('status','active')->get();

            } else {
                if (get_one_public_services() != 0) {
                    $cities = City::where("id", get_user_city_id())->where('status','active')->get();
                } else {
                    $cities = City::where("id", get_user_city_id())->where('status','active')->orderBy('id', 'desc')->get();

                }

            }
        }


        $data = null;

        if(is_jawhar_user())
        {
            $data = PublicService::orderBy('id', 'desc')->where('parent_id',0)->get();
        }
        else {

            if (is_one_public_services()) {
                $data = PublicService::where("city_id", get_user_city_id())->orderBy('id', 'desc')->where('parent_id',0)->get();
            } else {
                if (get_one_public_services() != 0) {
                    $data = PublicService::where("city_id", get_user_city_id())->where("id", get_one_public_services())->orderBy('id', 'desc')->where('parent_id',0)->get();
                } else {
                    $data = PublicService::where("city_id", get_user_city_id())->orderBy('id', 'desc')->where('parent_id',0)->get();

                }

            }
        }

        return view('admin.publicServices.create', [
            'publicServices' => $data ,
            'cities' => $cities ,
        ]);
    }


    public function store(Request $request)
    {
        $roles = [
        'name' => 'required',
        'price' => 'required',
        'parent_id' => 'required',
        'is_dollar' => 'required',
        'city_id' => 'required',
        'purchasing_price' => 'required',
        ];

        $this->validate($request, $roles);

        $item= new PublicService();

        $item->name=$request->name;
        $item->price=$request->price;
        $item->purchasing_price=$request->purchasing_price;
        $item->is_dollar =$request->is_dollar;
        $item->city_id=$request->city_id;
        $item->parent_id=$request->parent_id;
                if ($request->hasFile('image')) {
            $logo = $request->file('image');
            $extention = $logo->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($logo)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/public_services/".$file_name);
            $item->image = $file_name;
        }

        $item->save();
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
        $cities = null;

        if(is_jawhar_user())
        {
            $cities = City::where('status','active')->get();
        }
        else {

            if (is_one_public_services()) {
                $cities = City::where("id", get_user_city_id())->where('status','active')->get();

            } else {
                if (get_one_public_services() != 0) {
                    $cities = City::where("id", get_user_city_id())->where('status','active')->get();
                } else {
                    $cities = City::where("id", get_user_city_id())->where('status','active')->orderBy('id', 'desc')->get();

                }

            }
        }

        $item = PublicService::findOrFail($id);

        $data = null;

        if(is_jawhar_user())
        {
            $data = PublicService::orderBy('id', 'desc')->where('parent_id',0)->get();
        }
        else {

            if (is_one_public_services()) {
                $data = PublicService::where("city_id", get_user_city_id())->orderBy('id', 'desc')->where('parent_id',0)->get();
            } else {
                if (get_one_public_services() != 0) {
                    if(is_one_public_services_root())
                    {
                        $data = PublicService::where("city_id", get_user_city_id())->where("id", get_one_public_services())->orderBy('id', 'desc')->where('parent_id',0)->get();
                    }
                    else
                    {
                        $data = PublicService::where("city_id", get_user_city_id())->where(function ($q)use ($item) {
                            $q->where("id", get_one_public_services())->orwhere("id", $item->parent_id);
                        })->orderBy('id', 'desc')->where('parent_id',0)->get();
                    }

                } else {
                    $data = PublicService::where("city_id", get_user_city_id())->orderBy('id', 'desc')->where('parent_id',0)->get();

                }

            }
        }

        return view('admin.publicServices.edit', [
            'item' => $item,
            'publicServices' => $data ,
            'cities' => $cities ,
        ]);

    }

    public function update(Request $request, $id)
    {
        $roles = [
            'name' => 'required',
            'price' => 'required',
            'parent_id' => 'required',
            'is_dollar' => 'required',
            'city_id' => 'required',
            'purchasing_price' => 'required',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
        }
        $this->validate($request, $roles);


        $item = PublicService::query()->findOrFail($id);
        $item->name=$request->name;
        $item->price=$request->price;
        $item->purchasing_price=$request->purchasing_price;
        $item->is_dollar =$request->is_dollar;
        $item->city_id=$request->city_id;
        $item->parent_id=$request->parent_id;
        if ($request->hasFile('image')) {
            $logo = $request->file('image');
            $extention = $logo->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($logo)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/public_services/".$file_name);
            $item->image = $file_name;
        }
        $item->save();

        return redirect()->back()->with('status', __('cp.update'));
    }



}
