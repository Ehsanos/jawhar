<?php
namespace App\Http\Controllers\WEB\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Language;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Page;


class PrivacyController extends Controller
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
        $item = Page::where('id', '2')->first();
        return view('website.privacy', [
            'item' =>$item,
        ]);
    }


    public function about()
    {
       // $item = Page::where('id', '2')->first();
    //    return view('website.aboutUs');
       $setting=Setting::all();
       $categories=Category::all(); 
       $item = Page::where('id', '1')->first();
      //dd();
       return view('website.aboutUs', [
        'categories' =>$categories,

        'item' =>$item ,
        
        'setting' =>$setting,
        ]);
     }

   
}
