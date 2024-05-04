<?php


namespace App\Http\Controllers\WEB\Admin;

use App\Models\UserPermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;

use Image;
use App\Models\Setting;
use App\Models\Language;
use App\Models\News;
use App\Models\Country;

class NewsController extends Controller
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

    public function index($my_store_id=-1)
    {

        $items = News::query();


            if($my_store_id != -1)
            {
                $items = $items->where("store_id",$my_store_id);
            }
            else
            {
                $admin = auth('admin')->user();
                if ($admin->id != 1) {
                $admin_perm = UserPermission::where('user_id', $admin->id)->first();
                $items = $items->where("store_id", $admin_perm->store_id);
                } else
                    if ($admin->id == 1) {
                        $items = $items->where("store_id", 0);
                    }
            }

        $items = $items->orderBy('ordering', 'asc')->get();

        return view('admin.news.home', [
            'items' => $items,
        ]);
    }


    public function create($my_store_id = "")
    {

        $countries = News::all();
        return view('admin.news.create', [
            'my_store_id' => $my_store_id,
        ]);
    }


    public function store(Request $request)
    {


        $roles = [
            'title' => 'required',

        ];


        $cat = new News();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/news/" . $file_name);
            $cat->image = $file_name;

        }
        $cat->link = $request->link;
        $cat->title = $request->title;

        if($request->my_store_id != "")
        {
            $cat->store_id = $request->my_store_id;
        }
        else
        {
            $admin = auth('admin')->user();
            if ($admin->id != 1) {
                $admin_perm = UserPermission::where('user_id', $admin->id)->first();
                $cat->store_id = $admin_perm->store_id;
            }
        }

        $cat->save();
        return redirect()->back()->with('status', __('cp.create'));

    }


    public function edit($id)
    {

        $item = News::findOrFail($id);

        return view('admin.news.edit', [
            'item' => $item]);
    }


    public function update(Request $request, $id)
    {

        $roles = [
            'title' => 'required',
        ];

        $this->validate($request, $roles);


        $item = News::query()->findOrFail($id);


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/news/" . $file_name);
            $item->image = $file_name;
        }
        $item->link = $request->link;
        $item->title = $request->title;

        $admin = auth('admin')->user();
        if ($admin->id != 1) {
            $admin_perm = UserPermission::where('user_id', $admin->id)->first();
            $item->store_id = $admin_perm->store_id;
        }

        $item->save();

        return redirect()->back()->with('status', __('cp.update'));
    }


    public function destroy($id)
    {

        $item = News::query()->findOrFail($id);
        if ($item) {
            News::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }

    public function sorting($my_store_id="")
    {
        $items = News::query();


        if($my_store_id != "")
        {
            $items = $items->where("store_id",$my_store_id);
        }
        else
        {
            $admin = auth('admin')->user();
            if ($admin->id != 1) {
                $admin_perm = UserPermission::where('user_id', $admin->id)->first();
                $items = $items->where("store_id", $admin_perm->store_id);
            } else
                if ($admin->id == 1) {
                    $items = $items->where("store_id", 0);
                }
        }

        $items = $items->orderBy('ordering', 'asc')->where('status', 'active')->get();

        return view('admin.news.sorting', [
            'items' => $items
        ]);
    }

    public function sort(Request $request)
    {
        //   $str =substr($request->inputArrayproducts, 0, strlen($request->inputArrayproducts)-2);
        $f = explode(',', $request->inputArrayproducts);
        foreach ($f as $index => $one) {
            $ids = explode(',', $one);
            $department = News::where('id', $one)->first();
            if ($department) {
                $department->ordering = $index + 1;
                $department->save();
            }
        }
        return redirect()->back()->with('status', __('cp.update'));
    }
}
