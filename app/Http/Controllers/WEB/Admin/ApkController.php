<?php
namespace App\Http\Controllers\WEB\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Response;
use Image;
use App\Models\Language;

use App\Models\Setting;
use App\Models\ImportProduct;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;

class ApkController extends Controller
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
        return view('admin.apk.home', [
     
        ]);
    }


    public function create()
    {
    
        return view('admin.apk.create');
    }



    public function store(Request $request)
    {
        $roles = [
         'file'  => 'required',
        ];

        $this->validate($request, $roles);
         $file = $request->file('file');
         $file_name = "Jawhar.apk";
        // $name = time().$file->getClientOriginalName();

         $file->move(public_path().'/uploads/apk/', $file_name);
          return redirect()->back()->with('status', __('cp.create'));


    }
}
