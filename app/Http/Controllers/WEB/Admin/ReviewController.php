<?php


namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Review;
use App\Models\Country;

class ReviewController extends Controller
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
        //
        $items = Review::orderBy('id','desc')->get();

        return view('admin.reviews.home', [
            'items' => $items,
        ]);
    }


    public function create()
    {
        $countries = Review::all();
        return view('admin.reviews.create' );
    }


    public function store(Request $request)
    {


        $roles = [
            'image' => 'required|image|mimes:jpeg,jpg,png',
            'name' => 'required',
            'rate' => 'required',
            'details' => 'required',

        ];

        $this->validate($request, $roles);


        $cat= new Review();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/reviews/".$file_name);
            $cat->image = $file_name;
           
        }
        $cat->name=$request->name;
        $cat->rate=$request->rate;
        $cat->details=$request->details;
        $cat->save();
        return redirect()->back()->with('status', __('cp.create'));

    }


    public function show($id)
    {
        //
        $item = Review::findOrFail($id);
    }


    public function edit($id)
    {

        $item = Review::findOrFail($id);
        
        return view('admin.reviews.edit', [
            'item' => $item] );
    }


    public function update(Request $request, $id)
    {

        $roles = [
            'image' => 'image|mimes:jpeg,jpg,png',
        ];
        $this->validate($request, $roles);
        $item = Review::query()->findOrFail($id);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/reviews/".$file_name);
            $item->image = $file_name;
        }
        $item->name=$request->name;
        $item->rate=$request->rate;
        $item->details=$request->details;
        $item->save();
        return redirect()->back()->with('status', __('cp.update'));
    }


    public function destroy($id)
    {

        $item = Review::query()->findOrFail($id);
        if ($item) {
            Review::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }
}
