<?php


namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Color;

class ColorController extends Controller
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
        //
        $items = Color::all();

        return view('admin.colors.home', [
            'items' => $items,
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
        return view('admin.colors.create');
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
            'color_hexa' => 'required',
            // 'name'     => 'required',

        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
        }
        $this->validate($request, $roles);


        $cat= new Color();


        foreach ($locales as $locale)
        {
            $cat->translateOrNew($locale)->name = $request->get('name_' . $locale);
        }

        $cat->color_hexa = $request->color_hexa;
        $cat->save();
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
        $item = Color::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $item = Color::findOrFail($id);
        return view('admin.colors.edit', [
            'item' => $item
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

        $roles = [
            'image' => 'image|mimes:jpeg,jpg,png',
        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
        }
        $this->validate($request, $roles);


        $item = Color::query()->findOrFail($id);

        foreach ($locales as $locale)
        {
            $item->translateOrNew($locale)->name = $request->get('name_' . $locale);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/category/".$file_name);
            $item->image = $file_name;
        }
        $item->save();

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

        $item = Color::query()->findOrFail($id);
        if ($item) {
            Color::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }
    
    public function sorting()
    {

        $items = Color::where('status','active')->orderBy('ordering','asc')->get();
        return view('admin.colors.sorting', [
            'items' => $items
        ]);
    }  
      public function sort(Request $request)
    {
       //   $str =substr($request->inputArrayproducts, 0, strlen($request->inputArrayproducts)-2);
            $f= explode(',',$request->inputArrayproducts);
          foreach($f as  $index=> $one){
              $ids= explode(',',$one);
              $department=Color::where('id',$one)->first();
              if($department){
                 $department->ordering=$index+1;
                 $department->save(); 
              }
          }
          return redirect()->back();
    }
}
