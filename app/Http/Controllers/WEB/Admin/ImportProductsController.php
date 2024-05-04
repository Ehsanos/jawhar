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
class ImportProductsController extends Controller
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
        $files = ImportProduct::orderBy('id', 'desc')->get();
        return view('admin.importFiles.home', [
            'files' =>$files,
        ]);
    }


    public function create()
    {
    
        return view('admin.importFiles.create');
    }

    public function import(Request $request) 
    {
        $files = ImportProduct::where('status', 1)->get();
        foreach($files as $file){
           
         $files = ImportProduct::where('id', $file->id)->update(['status'=>2]);
         
         Excel::import(new ProductImport, public_path().'/uploads/excel/'.$file->file_name);

         $files = ImportProduct::where('id', $file->id)->update(['status'=>3]);
        }
        
        return redirect()->back()->with('status', __('cp.create'));
    }
    

    public function store(Request $request)
    {
        $roles = [
         'file'  => 'required',
        ];

        $this->validate($request, $roles);
         $item= new ImportProduct();
         $file = $request->file('file');
         $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . ".xlsx";
        // $name = time().$file->getClientOriginalName();

         $file->move(public_path().'/uploads/excel/', $file_name);
         $item->file_name = $file_name;
         $item->status= 1;
         $item->save();
          return redirect()->back()->with('status', __('cp.create'));


    }



    public function edit($id)
    {
       
    }


    public function update(Request $request, $id)
  {
   
    }

 
    public function destroy($id)
    {

    }
    public function jobrequests()
    {

    }
}
