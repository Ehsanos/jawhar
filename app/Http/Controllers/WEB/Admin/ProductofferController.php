<?php

namespace App\Http\Controllers\WEB\Admin;


use App\Models\ServiceImage;
use App\Models\Category;
use App\Models\Language;
use App\Models\Product;
use App\Models\Setting;
use App\Models\ProductImage;
use App\Models\Productoffer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;

class ProductofferController extends Controller
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
        $products = Productoffer::query();
        
        // if ($request->has('categoryId') ) {
        //     if ($request->get('categoryId') != null)
        //     {
        //         $products->where('category_id', $request->get('categoryId'));
        //     }

        // }
        
        // if ($request->has('available') ) {
        //     if ($request->get('available') != null)
        //     {
        //         $products->where('available', $request->get('available'));
        //     }

        // }

        $productoffers = $products->orderBy('created_at', 'desc')->get();//paginate(20);
        return view('admin.productoffers.home', [
            'productoffers' => $productoffers,
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
        $products=Product::where('status','active')->get();
        return view('admin.productoffers.create', [
            'products' => $products 
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
            'discount_price'  => 'required |numeric',
            'product_id'   => 'required',

           'offer_from' => 'required_unless:discount,0',
           'offer_to' => 'required_unless:discount,0',
        ];


        $this->validate($request, $roles);

            $productoffer =new Productoffer(); 
            $productoffer->product_id=$request->product_id;
            $productoffer->discount_price=$request->discount_price;
            $productoffer->offer_from=$request->offer_from;
            $productoffer->offer_to=$request->offer_to;
              if($request->is_flash != null){
             $productoffer->is_flash=$request->is_flash;                 
              }else{
                $productoffer->is_flash=0;                 

              }

            $productoffer->save();

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
        $productoffer=Productoffer::findOrFail($id);
        $products=Product::where('status','active');

        return view('admin.productoffers.edit', [
            'productoffer' => $productoffer ,
            'products' => $products ,
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
            'discount_price'  => 'required |numeric',
            'product_id'   => 'required',

           'offer_from' => 'required_unless:discount,0',
           'offer_to' => 'required_unless:discount,0|min:'.(int)$request->offer_from ,
        ];

        $this->validate($request, $roles);

           $productoffer =new Productoffer(); 
           $productoffer->product_id=$request->product_id;
           $productoffer->discount_price=$request->discount_price;
           $productoffer->offer_from=$request->offer_from;
           $productoffer->offer_to=$request->offer_to;
           if($request->is_flash != null){
            $productoffer->is_flash=$request->is_flash;                 
             }else{
               $productoffer->is_flash=0;                 

             }

           $productoffer->save();
        
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
        $item = Productoffer::query()->findOrFail($id);
        if ($item) {
            Productoffer::query()->where('id', $id)->delete();

            return "success";
        }
        return "fail";
    }


}
