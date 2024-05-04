<?php
namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Institute;
use App\Models\InstituteCourses;




class InstituteCoursesController extends Controller
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
        $instituteCourses = null;

        if(is_jawhar_user())
        {
            $instituteCourses = InstituteCourses::orderBy('id', 'desc')->get();
        }
        else
        {
            if(is_one_institute())
            {
                $instituteCourses = InstituteCourses::join("institutes","institutes.id","=","institute_courses.institute_id")->select("institutes.*","institute_courses.*")->where("institutes.city_id",get_user_city_id())->orderBy('institute_courses.id', 'desc')->get();

            }
            else
            {
                if(get_one_institute() != 0)
                {
                    $instituteCourses = InstituteCourses::join("institutes","institutes.id","=","institute_courses.institute_id")->select("institutes.*","institute_courses.*")->where("institutes.city_id",get_user_city_id())->where("institutes.id",get_one_institute())->orderBy('institute_courses.id', 'desc')->get();

                }
                else
                {
                    $instituteCourses = InstituteCourses::join("institutes","institutes.id","=","institute_courses.institute_id")->select("institutes.*","institute_courses.*")->where("institutes.city_id",get_user_city_id())->orderBy('institute_courses.id', 'desc')->get();

                }


            }

        }


        return view('admin.instituteCourses.home', [
            'instituteCourses' => $instituteCourses ,
        ]);

    }

    public function create()
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

        return view('admin.instituteCourses.create', [
            'institutes' => $institutes ,
        ]);
    }

    public function edit($id)
    {
        $item = InstituteCourses::findOrFail($id);

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


        return view('admin.instituteCourses.edit', [
            'item' => $item,
            'institutes' => $institutes ,
        ]);

    }

       public function store(Request $request)
    {
        //
        $roles = [
           'institute_id' => 'required',
           'name' => 'required',
           'details' => 'required',
           'is_dollar' => 'required',
           'price' => 'required',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
          //  $roles['details_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $institute= new InstituteCourses();
        $institute->institute_id = $request->institute_id;
        $institute->name = $request->name;
        $institute->details = $request->details;
        $institute->is_dollar =$request->is_dollar;
        $institute->price = $request->price;

        foreach ($locales as $locale)
        {
           // $ad->translateOrNew($locale)->details = $request->get('details_' . $locale);
        }
        $institute->save();
        return redirect()->back()->with('status', __('cp.create'));
    }


    public function update(Request $request, $id)
    {
        //
        $roles = [
          'name' => 'required',
          'is_dollar' => 'required',
          'price' => 'required',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
          //  $roles['details_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $institute = InstituteCourses::query()->findOrFail($id);
        $institute->institute_id = $request->institute_id;
        $institute->name = $request->name;
        $institute->details = $request->details;
        $institute->is_dollar =$request->is_dollar;
        $institute->price = $request->price;
        foreach ($locales as $locale)
        {
           // $ad->translateOrNew($locale)->details = $request->get('details_' . $locale);
        }



        $institute->save();
        return redirect()->back()->with('status', __('cp.update'));
    }


}
