<?php

namespace App\Http\Controllers\WEB\Admin;


use App\Models\ServiceImage;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;

use App\Models\Category;
use App\Models\Language;
use App\Models\Product;
use App\Models\Setting;
use App\Models\SubCategory;
use App\Models\User;
use DemeterChain\C;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;
use Storage;

class ProductsController extends Controller
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
        $products = Product::query();
        
        if ($request->has('categoryId') ) {
            if ($request->get('categoryId') != null)
            {
                $products->where('category_id', $request->get('categoryId'));
            }

        }
        
        if ($request->has('available') ) {
            if ($request->get('available') != null)
            {
                $products->where('available', $request->get('available'));
            }

        }

        $products = $products->where('status','active')->where('store_id', 0)->orderByDesc('id')->paginate(20);//get();
        return view('admin.products.home', [
            'products' => $products,
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
        $categories = Category::where('status','active')->get();
        $subcategory = Subcategory::where('status','active')->get();
        return view('admin.products.create', [
            'categories' => $categories ,
            'subCategory' =>$subcategory,
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
//        return $request;
        //
        $roles = [

            'price'  => 'required |numeric',
            'sub_category_id'   => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif',
            // 'offer_from' => 'required_unless:discount,0',
            // 'offer_to' => 'required_unless:discount,0' ,

        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

//return $request;
        $product= new Product();
        $product->price = $request->price;
        $product->category_id =$request->category ;
        $product->discount =$request->discount ;
        $product->offer_from = $request->offer_from;
        $product->offer_to = $request->offer_to;
        $product->subCategory_id = $request->sub_category_id;
        $product->is_dollar = $request->is_dollar;
        $product->count = $request->product_count;
       if ($request->has('top_selling')){
        $product->most_selling = $request->top_selling;
       }
       if ($request->has('newest')){
            $product->newest = $request->newest;
       }

        foreach ($locales as $locale)
        {
            $product->translateOrNew($locale)->name = $request->get('name_' . $locale);
            $product->translateOrNew($locale)->description = $request->get('description_' . $locale);

        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/products/$file_name");
            $product->image = $file_name;
        }
        $product->save();

 if($request->has('filename')  && !empty($request->filename))
        {
           foreach($request->filename as $one)
           {
               if (isset(explode('/', explode(';', explode(',', $one)[0])[0])[1])) {
                    $fileType = strtolower(explode('/', explode(';', explode(',', $one)[0])[0])[1]);
                    $name = auth()->guard('admin')->user()->id. "_" .str_random(8) . "_" .  "_" . time() . "_" . rand(1000000, 9999999);
                    $attachType = 0;
                    if (in_array($fileType, ['jpg','jpeg','png','pmb'])){
                        $newName = $name. ".jpg";
                        $attachType = 1;
                        Image::make($one)->resize(800, null, function ($constraint) {$constraint->aspectRatio();})->save("uploads/images/products/$newName");
                    }
                    $product_image=new ProductImage();
                    $product_image->product_id = $product->id;
                    $product_image->product_img = $newName;
                    $product_image->save();
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
        $product=Product::findOrFail($id);
        if($product->store_id > 0){
                  $categories = Category::where('status','active')->where('store_id',$product->store_id)->get();
  
        }else{
          $categories = Category::where('status','active')->get();
        }
        $subcategories = Subcategory::where('id',$product->subCategory_id)->first();
        return view('admin.products.edit', [
            'categories' => $categories ,
            'subcategories' => $subcategories ,
            'product' => $product ,
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

            'price'  => 'required |numeric',
            'category_id'   => 'required',
            'sub_category'   => 'required',
            // 'offer_from' => 'required_unless:discount,0',
            // 'offer_to' => 'required_unless:discount,0' ,

        ];
        $locales = Language::all()->pluck('lang');
        foreach ($locales as $locale) {
            $roles['name_' . $locale] = 'required';
        }
        $this->validate($request, $roles);

        $product = Product::query()->findOrFail($id);
        $product->price = $request->price;
        $product->category_id =$request->category_id ;
        $product->subCategory_id =$request->sub_category ;
        $product->type = $request->type;
        $product->available = $request->available;
        $product->discount =$request->discount ;
        $product->offer_from = $request->offer_from;
        $product->offer_to = $request->offer_to;
        $product->is_dollar = $request->is_dollar;
       if ($request->top_selling =! Null){
        $product->most_selling = $request->top_selling;
       }
       if ($request->newest =! Null){
            $product->newest = $request->newest;
       }


        foreach ($locales as $locale)
        {
            $product->translateOrNew($locale)->name = $request->get('name_' . $locale);
            $product->translateOrNew($locale)->description = $request->get('description_' . $locale);

        }
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extention = $image->getClientOriginalExtension();
            $file_name = str_random(15) . "" . rand(1000000, 9999999) . "" . time() . "_" . rand(1000000, 9999999) . "." . $extention;
            Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save("uploads/images/products/$file_name");
            $product->image = $file_name;
        }
        $product->save();
 $imgsIds = $product->attachments->pluck('id')->toArray();
        $newImgsIds = ($request->has('oldImages'))? $request->oldImages:[];
        $diff = array_diff($imgsIds,$newImgsIds);
        ProductImage::whereIn('id',$diff)->delete();

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
                        Image::make($one)->resize(800, null, function ($constraint) {$constraint->aspectRatio();})->save("uploads/images/products/$newName");
                    }
                    $product_image=new ProductImage();
                    $product_image->product_id = $product->id;
                    $product_image->product_img = $newName;
                    $product_image->save();
                }
           }
        }
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
        //
        $item = Product::query()->findOrFail($id);
        if ($item) {
            Product::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }
    public function getSubCategoryByCategoryId($id){
        $subCategory = Category::where('id' ,$id)->get();
        return response()->json(  ['status'=>true, 'subcategory'=> $subCategory]);
    }


public function getSubcategories($id){
    return Subcategory::where('category_id', $id)->where('status', 'active')->get();
}


    public function productImages()
    {
        
        // $files = File::allFiles('uploads/images/products');
        $files = Storage::allFiles('uploads/images/products');
              return view('admin.products.productImages',[
                    'files'=>$files
                ]);
    }
    public function addImages()
    {
        return view('admin.products.addImages');
        
              
    }
    public function storeImage(Request $request)
    {
        $image = $request->file('file');
        $imageName = $image->getClientOriginalName();
        $image->move(public_path('uploads/images/products'),$imageName);

        return response()->json(['success'=>$imageName]);
    }
    public function deleteImage(Request $request)
    {
       
      File::delete($request->image);

        return response()->json(['success'=>'ok']);
    }
     public function removeImageFromDropZone(Request $request)
    {
        $filename =  $request->get('filename');
        $path=public_path().'/uploads/images/products/'.$filename;
        if (file_exists($path)) {
            unlink($path);
        }
        return $filename;  
    }

}
