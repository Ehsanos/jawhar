<?php
namespace App\Http\Controllers\WEB\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Language;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Department;
use App\Models\PageTranslation;
use App\Models\Page;


class pagesController extends Controller
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
        
        $this->departments = Department::where('status', 'active')->with(['categories'=>function($query) {
            $query->where('status', 'active')->with(['subcategories'=>function($query) {
              $query->where('status', 'active')->with(['banners'=>function($query) {
                 $query->where('status', 'active'); }]); }]); }])->get();
                 
        view()->share([
            'locales' => $this->locales,
            'settings' => $this->settings,
            'departments' => $this->departments,

        ]);
    }
    public function privacy()
    {
        $item = Page::where('id', '2')->first();
        $setting=Setting::all();
        return view('website.pages.privacy', [
            'item' =>$item ,
            'setting' =>$setting,
        ]);
    }


    public function about()
    {
       $item = Page::where('id', '1')->first();
      //dd();
       return view('website.aboutus', [
        'item' =>$item ,

        ]);
     }

     public function term()
     {
 
        $setting=Setting::all();
        $categories=Category::all(); 
        $item = Page::where('id', '3')->first();
       //dd();
        return view('website.pages.term', [
         'categories' =>$categories,
         'item' =>$item ,
         'setting' =>$setting,
         ]);
      }


      public function returnPolicy()
      {
  
         $setting=Setting::all();
         $categories=Category::all(); 
         $item = Page::where('id', '4')->first();
        //dd();
         return view('website.pages.returnPolicy', [
          'categories' =>$categories,
          'item' =>$item ,
          'setting' =>$setting,
          ]);
       }


       public function show($slug)
{
        $item = Page:: where('slug', $slug)->first();

       return view('website.pages', [
        'item' =>$item ,
  
        ]);
}
   
}
