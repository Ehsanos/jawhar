<?php

namespace App\Http\Controllers\WEB\Admin;


use App\Models\Language;
use App\Models\Azkar;
use App\Models\AzkarAttachment;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use Auth;
class AzkarController extends Controller
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
        $azkar = Azkar::query();
        

        $azkar = $azkar->orderBy('id', 'desc')->get();//paginate(20);
        return view('admin.azkar.home', [
            'azkar' => $azkar,
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
        return view('admin.azkar.create', [
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $roles = [

           // 'type'   => 'required',
       //     'image' => 'required|image|mimes:jpeg,jpg,png,gif',

           // 'offer_from' => 'required_unless:discount,0',
           // 'offer_to' => 'required_unless:discount,0|min:'.(int)$request->offer_from ,

        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
        }
        $this->validate($request, $roles);


        $azkar= new Azkar(); 
        foreach ($locales as $locale)
        {
            $azkar->translateOrNew($locale)->name = $request->get('name_' . $locale);

        }


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            //$extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . '.jpg';
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/azkar/$file_name");
            $azkar->image = $file_name;
        }

        $azkar->save();
        

         if($request->has('filename')  && !empty($request->filename))
        {
           foreach($request->filename as $one)
           {
               if (isset(explode('/', explode(';', explode(',', $one)[0])[0])[1])) {
                    $fileType = strtolower(explode('/', explode(';', explode(',', $one)[0])[0])[1]);
                    $name = auth()->guard('admin')->user()->id. "_" .str_random(8) . "_" .  "_" . time() . "_" . rand(1000000, 9999999);
                    $attachType = 0;
                    if (in_array($fileType, ['jpg','jpeg','png','pmb'])){
                        $newName = $name.".jpg";
                        $attachType = 1;
                        Image::make($one)->resize(800, null, function ($constraint) {$constraint->aspectRatio();})->save("uploads/images/azkar/$newName");
                    }
                    $azkar_image=new AzkarAttachment();
                    $azkar_image->azkar_id = $azkar->id;
                    $azkar_image->image = $newName;
                    $azkar_image->save();
                }
           }
        }
        
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
        $azkar=Azkar::findOrFail($id);
        return view('admin.azkar.edit', [
            'azkar' => $azkar ,
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
        //
        $roles = [


          //  'type'   => 'required',
            'image' => 'image|mimes:jpeg,jpg,png',

        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $azkar = Azkar::query()->findOrFail($id);

   
        foreach ($locales as $locale)
        {
            $azkar->translateOrNew($locale)->name = $request->get('name_' . $locale);

        }

    
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
          //  $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . '.jpg';
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/azkar/$file_name");
            $azkar->image = $file_name;
        }
        $azkar->save();

//return  $azkar->attachments;
       
        $imgsIds = (!empty($azkar->attachments))? $azkar->attachments->pluck('id')->toArray():[];
        $newImgsIds = ($request->has('oldImages'))? $request->oldImages:[];
        $diff = array_diff($imgsIds,$newImgsIds);
        AzkarAttachment::whereIn('id',$diff)->delete();

        if($request->has('filename')  && !empty($request->filename)){
           foreach($request->filename as $one)
           {
               if (isset(explode('/', explode(';', explode(',', $one)[0])[0])[1])) {
                    $fileType = strtolower(explode('/', explode(';', explode(',', $one)[0])[0])[1]);
                    $name = auth()->guard('admin')->user()->id. "_" .str_random(8) . "_" .  "_" . time() . "_" . rand(1000000, 9999999);
                    $attachType = 0;
                    if (in_array($fileType, ['jpg','jpeg','png','pmb'])){
                        $newName = $name. ".jpg";
                        $attachType = 1;
                        Image::make($one)->resize(800, null, function ($constraint) {$constraint->aspectRatio();})->save("uploads/images/azkar/$newName");
                    }
                    $azkar_image=new AzkarAttachment();
                    $azkar_image->azkar_id = $azkar->id;
                    $azkar_image->image = $newName;
                    $azkar_image->save();
                }
           }
        }

        return redirect()->back()->with('status', __('cp.update'));
    }


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
   
        public function sorting()
    {

        $items = Azkar::where('status','active')->orderBy('ordering','asc')->get();
        return view('admin.azkar.sorting', [
            'items' => $items
        ]);
    }  
      public function sort(Request $request)
    {
       //   $str =substr($request->inputArrayproducts, 0, strlen($request->inputArrayproducts)-2);
            $f= explode(',',$request->inputArrayproducts);
          foreach($f as  $index=> $one){
              $ids= explode(',',$one);
              $department=Azkar::where('id',$one)->first();
              if($department){
                 $department->ordering=$index+1;
                 $department->save(); 
              }
          }
           return redirect()->back()->with('status', __('cp.update'));
    } 
    

}
