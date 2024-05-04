<?php
namespace App\Http\Controllers\WEB\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Language;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Productoffer;
use App\Models\Subcategory;

use App\Models\Product;
use App\Models\Branche;
use App\Models\Banner;
use App\Models\Question;
use App\Models\OrderProduct;
use App\Models\Jobrequest;
use App\Models\Department;
use App\Models\ReviewLike;
use App\Models\City;
use App\Models\Color;
use App\Models\BannerProduct;
use App\Models\Comboproduct;
use App\Models\Productspecification;
use App\Models\Combo;
use App\Models\ProductTranslation;
use App\Models\Setting;
use App\Models\Order;
use App\Models\Contact;
use App\Models\Attribute;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\Ad;
use App\Models\Job;
use App\Models\Cart;
use App\Models\News;
use App\Models\Page;
use App\Models\ProductCatecory;
use Picqer;
use DateTime;
use DB;
use Illuminate\Support\Facades\Session;


class FrontController extends Controller
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
                    
        $this->carts=Cart::where('user_key',Session::get('cart.ids'))->get();
        view()->share([
            'locales' => $this->locales,
            'settings' => $this->settings,
            'departments' => $this->departments,
            'carts' => $this->carts,

        ]);
    }
    public function index()
    {
       
        //$departments=Department::where('status','active')->with('categories.subcategories')->get();

        // $departments = Department::where('status', 'active')->with(['categories'=>function($query) {
        //     $query->where('status', 'active')->with(['subcategories'=>function($query) {
        //         $query->where('status', 'active')->with(['banners'=>function($query) {
        //             $query->where('status', 'active'); }]); }]); }])->get();
                //    return $departments;
      
        $big_banners=Banner::where('status','active')->orderBy('id','desc')->where('size',1)->take(2)->get();
        $small_banners=Banner::where('status','active')->orderBy('id','desc')->where('size',0)->take(4)->get();
        $news=News::where('status','active')->orderBy('created_at', 'desc')->take(2)->get();
        $flash_offer=Productoffer::where('is_flash',1)->orderBy('id','desc')->with('product')->where('offer_from','<=',now()->toDateString())->where('offer_to' ,'>=', now()->toDateString())->take(2)->get();
       
        // return $departments;
        $last_products=Product::orderBy('created_at', 'desc')->take(10)->where('status','active')->get();
        $recom_products=Product::orderBy('created_at', 'desc')->where('status','active')->inRandomOrder(7)->limit(7)->get();
        $top_rates=Product::orderBy('rate', 'desc')->take(10)->where('status','active')->get();
      
        $ads= Ad::where('status','active')->orderBy('id','desc')->take(3)->get();

        $pepole_products= OrderProduct::inRandomOrder()->where('type',1)->limit(15)->with('product')->with(['order'=>function($query) {
                    $query->with('address')->with(['user'=>function($query) {
                                   $query; }]); }])->get()->unique('target');   
                               

        return view('website.home', [
           // 'departments' =>$departments,
            //'produts' =>$produts,
            'big_banners' =>$big_banners,
            'small_banners' =>$small_banners,
            'last_products' =>$last_products,
            'recom_products' =>$recom_products,
            'top_rates' =>$top_rates,
            'flash_offer' =>$flash_offer,
            'pepole_products' =>$pepole_products,
            'news' =>$news,
            'ads' =>$ads,
            
        ]);
    }


    public function product($id)
    {

        //$product=Product::with('specifications')->findOrFail($id);
         $product=Product::with('specifications')->findOrFail($id);
         $remaning_time='';
          $productoffer=Productoffer::where('product_id',$product->id)->where('offer_from','<=',now()->toDateString())->where('offer_to' ,'>=', now()->toDateString())->first();
         if($productoffer){
         // $remaning_time= \Carbon\Carbon::parse($productoffer->offer_to)->diffInHours(\Carbon\Carbon::parse(now()));
               $now = new DateTime();
               $sinceThen = $now->diff(new DateTime($productoffer->offer_to));
                $h = $sinceThen->d*24;
                $i = $sinceThen->i;
                $s = $sinceThen->s;
                
                $remaning_time=$h.':'.$i.':'.$s;

          }
         $product_barcode='';
        if($product->barcode !=''){
        $barcode = new Picqer\Barcode\BarcodeGeneratorPNG();
        $product_barcode = $barcode->getBarcode($product->barcode, $barcode::TYPE_CODE_128);
        }
         if($product){
             $config_products=Product::where('id','!=',$product->id)->where('configured_code',$product->configured_code)->get();
            
         }
         
        $reviews=ProductReview::where('product_id',$id)->get();
        $cities=City::where('status','active')->get();
        $produts=Product::where('status','active')->orderBy('created_at', 'desc')->where('category_id', $product->category_id)->take(15)->get();   
        $produtcombo=Comboproduct::where('product_id', $product->id)->first();  
        if($produtcombo){
        $combo_id= $produtcombo->combo_id;

        $combo=Combo::where('id',$combo_id)->where('status','active')->with(['comboproduct'=>function($query)  use($product){
            $query->where('product_id','!=',$product->id);}])->first();   
        
        return view('website.product', [
            'product' =>$product,
            'produts' =>$produts,
            'reviews' =>$reviews,
            'combo' =>$combo,
            'cities' =>$cities,
            'config_products' =>$config_products,
            'product_barcode' =>$product_barcode,
            'remaning_time' =>$remaning_time,
            
        ]);
    }else{
        return view('website.product', [
            'product' =>$product,
            'produts' =>$produts,
            'reviews' =>$reviews,
            'cities' =>$cities,
            'config_products' =>$config_products,
            'product_barcode' =>$product_barcode,
            'remaning_time' =>$remaning_time,

            
        ]);
    }
    }
    public function reviewLanguage(Request $request)
    {
       
        if ($request->ajax()) { 
            $reviews=ProductReview::where('locale',$request->language)->where('product_id',$request->id)->get();

            $view = view('website.more.language')->with(['reviews'=>$reviews])->render();
            return response()->json(['html' => $view]);
        }
    }
    public function commentLike(Request $request)
    {
       
         if(!ReviewLike::where('user_id',Auth::user()->id)->where('review_id',$request->review_id)->exists()){
            $like= new ReviewLike();
            $like->user_id=Auth::user()->id;
            $like->review_id=$request->review_id;
            $like->type=1;
            $like->save();
            $liceCount=ReviewLike::where('type',1)->where('review_id',$request->review_id)->count();
            $dislikeCount=ReviewLike::where('type',0)->where('review_id',$request->review_id)->count();
            return response()->json(['code' => 200 ,'liceCount'=>$liceCount ,'dislikeCount'=>$dislikeCount]);

         }elseif(ReviewLike::where('user_id',Auth::user()->id)->where('type',1)->where('review_id',$request->review_id)->exists()){
            ReviewLike::where('user_id',Auth::user()->id)->where('type',1)->where('review_id',$request->review_id)->delete();
            $liceCount=ReviewLike::where('type',1)->where('review_id',$request->review_id)->count();
            $dislikeCount=ReviewLike::where('type',0)->where('review_id',$request->review_id)->count();
            return response()->json(['code' => 300 ,'liceCount'=>$liceCount ,'dislikeCount'=>$dislikeCount]);

         }elseif(ReviewLike::where('user_id',Auth::user()->id)->where('type',0)->where('review_id',$request->review_id)->exists()){
            ReviewLike::where('user_id',Auth::user()->id)->where('type',0)->where('review_id',$request->review_id)->update(['type'=>1]);
            $liceCount=ReviewLike::where('type',1)->where('review_id',$request->review_id)->count();
            $dislikeCount=ReviewLike::where('type',0)->where('review_id',$request->review_id)->count();
            return response()->json(['code' => 400 ,'liceCount'=>$liceCount ,'dislikeCount'=>$dislikeCount]);
        }else{
            return response()->json(['code' => 500]);
        }
        
    }

    public function commentDisLike(Request $request)
    {
       
         if(!ReviewLike::where('user_id',Auth::user()->id)->where('review_id',$request->review_id)->exists()){
            $like= new ReviewLike();
            $like->user_id=Auth::user()->id;
            $like->review_id=$request->review_id;
            $like->type=0;
            $like->save();
            $liceCount=ReviewLike::where('type',1)->where('review_id',$request->review_id)->count();
            $dislikeCount=ReviewLike::where('type',0)->where('review_id',$request->review_id)->count();
            return response()->json(['code' => 200 ,'liceCount'=>$liceCount ,'dislikeCount'=>$dislikeCount]);

         }elseif(ReviewLike::where('user_id',Auth::user()->id)->where('type',0)->where('review_id',$request->review_id)->exists()){
            ReviewLike::where('user_id',Auth::user()->id)->where('type',0)->where('review_id',$request->review_id)->delete();
            $liceCount=ReviewLike::where('type',1)->where('review_id',$request->review_id)->count();
            $dislikeCount=ReviewLike::where('type',0)->where('review_id',$request->review_id)->count();
            return response()->json(['code' => 300 ,'liceCount'=>$liceCount ,'dislikeCount'=>$dislikeCount]);

         }elseif(ReviewLike::where('user_id',Auth::user()->id)->where('type',1)->where('review_id',$request->review_id)->exists()){
            ReviewLike::where('user_id',Auth::user()->id)->where('type',1)->where('review_id',$request->review_id)->update(['type'=>0]);
            $liceCount=ReviewLike::where('type',1)->where('review_id',$request->review_id)->count();
            $dislikeCount=ReviewLike::where('type',0)->where('review_id',$request->review_id)->count();
            return response()->json(['code' => 400 ,'liceCount'=>$liceCount,'dislikeCount'=>$dislikeCount]);

        }else{
            return response()->json(['code' => 500]);
        }
        
    }

    public function productReview(Request $request)
    { 

        $validator = Validator::make($request->all(), [

            'rate' => 'required|digits_between:1,5',        

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200,
                'validator' =>implode("\n",$validator-> messages()-> all()) ]);
        }

        if(ProductReview::where('user_id',Auth::user()->id)->where('product_id',$request->product_id)->exists())
           {  
        $message = __('website.reviewed');
        return response()->json(['status' => false, 'code' => 300, 'message' =>  $message]);
           }
        
        $review= new ProductReview();
        $review->user_id=Auth::user()->id; 
        $review->product_id=$request->product_id; 
        $review->comment=$request->comment; 
        $local=is_rtl($request->comment);
        if($local==1){
            $review->locale='ar';
        }else{
            $review->locale='en';
        }
       
        $review->rate=convertAr2En($request->rate);
         
        if(OrderProduct::where('user_id',Auth::user()->id)->where('type',1)->where('target',$request->product_id)->exists())
        {
           $review->is_bought=1;
        }
        $review->save();
        
        if($review){   
    
            $avg_rate = ProductReview::where('product_id',$request->product_id)->avg('rate');
            $product_rate=Product::where('id',$request->product_id)->first();

            $product_rate->rate =round($avg_rate);
            $product_rate->save();
            $message = __('api.ok');
           // $view = view('website.more.language')->with(['reviews'=>$review])->render();
            return response()->json(['status' => true, 'code' => 400, 'message' => $message,  ]);
     }
            $message = __('api.whoops');
            return response()->json(['status' => false, 'code' => 500, 'message' => $message,  ]);
       

    }


    public function contact()
    {
       
        $branches = Branche::orderBy('id', 'desc')->get();
        $cites = City::orderBy('id', 'desc')->get();
        return view('website.contact', [
            'branches' =>$branches,
            'cites' =>$cites,
        ]);
    }
    public function getBranchesByCityId($id)
    {
       
        $branches = Branche::where('city_id',$id)->orderBy('id', 'desc')->get();
         $view = view('website.includs.branches')->with(['branches'=>$branches])->render();
            return response()->json(['html' => $view]);
    }
    public function jobs()
    {
       
        $jobs = Job::where('end_date','>',now())->orderBy('id', 'desc')->get();
        $jobb= $jobs->first();
       // return $job;
        return view('website.jobs', [
            'jobs' =>$jobs,
            'jobb' =>$jobb,
        ]);
    }
    public function jobDetails($id)
    {
       
        $jobDetails = Job::where('id', $id)->first();
        return view('website.more.jobDetails', [
            'jobDetails' =>$jobDetails,
        ])->render();
    }
    public function applyJob(Request $request,$id)
    {
       
          $validator = Validator::make($request->all(), [
            'mobile' => 'required|min:7|max:12',
            'email' => 'required|email',
            'chooseFile'  => 'required|mimes:doc,docx,pdf|max:2048',
            'name' => 'required',
        
            
        ]);
       // return implode("\n",$validator-> messages()-> all()) ;
          if ($validator->fails()) {
            return response()->json(['code' => 300,
                'validator' =>implode("\n",$validator-> messages()-> all()) ]);
        }
    
       
      //  return $request->chooseFile;
         $job= new Jobrequest();
         $file = $request->file('chooseFile');

         //you also need to keep file extension as well
         $name = time().$file->getClientOriginalName();

         //using the array instead of object
         $image['filePath'] = $name;
         $file->move(public_path().'/uploads/jobRequest/', $name);
         $job->file= public_path().'/uploads/jobRequest/'. $name;
         $job->name= $request->name;
         $job->mobile= $request->mobile;
         $job->email= $request->email;
         $job->job_id= $id;
         $job->save();
         return response()->json(['status' => true, 'code' => 200]);
    }
    public function faq()
    {
        $qustions=Question::where('status','active')->get();
        return view('website.FAQ', [
            'qustions' =>$qustions,
        ]);
    }
    public function faqSearch(Request $request)
    {
        $qustions=Question::where('status','active')->whereTranslationLike('question', '%'. $request->search.'%')->get();
        return view('website.FAQ', [
            'qustions' =>$qustions,
        ]);
    }
    public function askQustion(Request $request)
    {
         $validator = Validator::make($request->all(), [

            'qustion' => 'required',        

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'code' => 200,
                'validator' =>implode("\n",$validator-> messages()-> all()) ]);
        }
        
            $qustion= new Contact();
            $qustion->qustion=$request->qustion;
            $qustion->save();
            
        if($qustion){   
    
           
            return response()->json(['status' => true, 'code' => 200 ]);
     }
            return response()->json(['status' => false, 'code' => 300  ]);
    }

    public function forgetPassword(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'validator' =>implode("\n",$validator-> messages()-> all()) ]);
        }
        return response()->json(['status' => true]);

    }


    
    public function productsBySubcategory(Request $request,$id)
    {


        $ids=ProductCatecory::where('subcategory_id',$id)->pluck('product_id')->toArray();
        $categories=Category::where('status','active')->get();
      //  $subcategories=Subcategory::withCount('subcategoryCounts')->where('status','active')->get();/////
        $colors=Color::withCount('productCounts')->get();//////
        $attributes=Attribute::where('is_filtered',1)->orderBy('ordering','desc')->withCount('productCount')->get();
        
        $subcategory=Subcategory::where('id',$id)->first();
        $subcategory_name=$subcategory->name;
        $subcategory_id=$subcategory->id;
        $cat_name=$subcategory->category->name;
        
        $products=Product::whereIn('id', $ids)->where('status','active');
        
         if ($request->ajax()) { 
             
                if($request->has('color_id') && $request->color_id!='' ){
                    $products = Product::whereIn('color_id', $request->color_id)->where('status','active');

                 }
                 if($request->has('subcategory_id') && $request->subcategory_id!='' ){
                    $product_ids=Productspecification::whereIn('attribute_id',$request->subcategory_id)->pluck('product_id')->toArray();
                      if($request->has('color_id')){
                     $products = $products->whereIn('id', $product_ids);
                      }else{
                          $products = Product::where('status','active')->whereIn('id', $product_ids);
                      }
                   

                 }
                 
                 
                 if($request->has('viewBy') && $request->viewBy!='' ){
                  $products= $products->orderBy('id',$request->viewBy);
                 }
                 if($request->has('price') && $request->price!='' ){
                    $products= $products->orderBy('price',$request->price); 
                 }
                 if($request->has('rate') && $request->rate!='' ){
                    $products= $products->orderBy('rate',$request->rate); 
                 }
                 if($request->has('alpha') && $request->alpha!='' ){
                     $sorted = Product::whereIn('id', $ids)->where('status','active')->get()->sortBy('name')->pluck('id')->toArray();
                     $orderedIds = implode(',', $sorted);
                     $products= $products->orderByRaw(\DB::raw("FIELD(id, ".$orderedIds." )"));
                 }
                 

                     $is_more='yes';
                     $products= $products->paginate(18);
                if($products->count() < 12){$is_more='no';}
                $view = view('website.includs.broductsBySubcategoey')->with(['products'=>$products])->render();
                return response()->json(['html' => $view,'is_more'=>$is_more]);
               
         }
        
        // if ($request->ajax()) { 
        //     if($request->type=='orderByDate'){
        //         $products= $products->orderBy('id',$request->viewBy);
        //          if($request->has('price') && $request->price!='' ){
        //             $products= $products->orderBy('price',$request->price); 
        //          }
        //          if($request->has('rate') && $request->rate!='' ){
        //             $products= $products->orderBy('rate',$request->rate); 
        //          }
        //          if($request->has('alpha') && $request->alpha!='' ){
        //              $sorted = Product::get()->sortBy('name')->pluck('id')->toArray();
        //              $orderedIds = implode(',', $sorted);
        //              $products = Product::orderByRaw(\DB::raw("FIELD(id, ".$orderedIds." )"));
        //          }
        //              $is_more='yes';
        //              $products= $products->paginate(12);
        //         if($products->count() < 12){$is_more='no';}
        //         $view = view('website.includs.broductsBySubcategoey')->with(['products'=>$products])->render();
        //         return response()->json(['html' => $view,'is_more'=>$is_more]);
                
        //     }elseif($request->type=='orderByPrice'){
                
        //          $products= $products->orderBy('price',$request->price);
        //          if($request->has('viewBy') && $request->viewBy!='' ){
        //             $products= $products->orderBy('id',$request->viewBy); 
        //          }
        //          if($request->has('rate') && $request->rate!='' ){
        //             $products= $products->orderBy('rate',$request->rate); 
        //          }
        //          if($request->has('alpha') && $request->alpha!='' ){
        //              $sorted = Product::get()->sortBy('name')->pluck('id')->toArray();
        //              $orderedIds = implode(',', $sorted);
        //              $products = Product::orderByRaw(\DB::raw("FIELD(id, ".$orderedIds." )"));
        //          }
        //              $is_more='yes';
        //              $products= $products->paginate(12);
        //         if($products->count() < 12){$is_more='no';}
        //         $view = view('website.includs.broductsBySubcategoey')->with(['products'=>$products])->render();
        //         return response()->json(['html' => $view,'is_more'=>$is_more]);
                
                
        //     }elseif($request->type=='orderByRate'){
        //          $products= $products->orderBy('rate',$request->rate);
        //          if($request->has('viewBy') && $request->viewBy!='' ){
        //             $products= $products->orderBy('id',$request->viewBy); 
        //          }
        //          if($request->has('price') && $request->price!='' ){
        //             $products= $products->orderBy('price',$request->price); 
        //          }
        //          if($request->has('alpha') && $request->alpha!='' ){
        //              $sorted = Product::get()->sortBy('name')->pluck('id')->toArray();
        //              $orderedIds = implode(',', $sorted);
        //              $products = Product::orderByRaw(\DB::raw("FIELD(id, ".$orderedIds." )"));
        //          }
        //              $is_more='yes';
        //              $products= $products->paginate(12);
        //         if($products->count() < 12){$is_more='no';}
        //         $view = view('website.includs.broductsBySubcategoey')->with(['products'=>$products])->render();
        //         return response()->json(['html' => $view,'is_more'=>$is_more]);
                
        //     }else{
        //         $products= $products->orderBy('rate',$id);
        //          if($request->has('viewBy') && $request->viewBy!='' ){
        //             $products= $products->orderBy('id',$request->viewBy); 
        //          }
        //          if($request->has('price') && $request->price!='' ){
        //             $products= $products->orderBy('price',$request->price); 
        //          }
        //          if($request->has('alpha') && $request->alpha!='' ){
        //              $sorted = Product::get()->sortBy('name')->pluck('id')->toArray();
        //              $orderedIds = implode(',', $sorted);
        //               $products= $products->orderByRaw(\DB::raw("FIELD(id, ".$orderedIds." )"));
        //          }
        //              $is_more='yes';
        //               $products= $products->paginate(12);
        //         if($products->count() < 12){$is_more='no';}
        //         $view = view('website.includs.broductsBySubcategoey')->with(['products'=>$products])->render();
        //         return response()->json(['html' => $view,'is_more'=>$is_more]);
                
        //     }
           
        // }
            $products= $products->paginate(12);
        return view('website.products.productsBySubcategory', [
            'categories' =>$categories,
            'products' =>$products,
            'subcategory_name' =>$subcategory_name,
            'subcategory_id' =>$subcategory_id,
            'cat_name' =>$cat_name,
           // 'subcategories' =>$subcategories,
            'colors' =>$colors,
            'attributes' =>$attributes,
        ]);
    } 
    public function productsByBanner(Request $request,$id)
    {

        //$categories=Category::where('status','active')->get();
        $banner=Banner::where('id',$id)->first();
        $banner_name=$banner->title;
         $products=BannerProduct::where('banner_id', $id)->with(['product'=>function($query) {
            $query->where('status', 'active');}])->paginate(16);
            
          if ($request->ajax()) { 
            $is_more='yes';
            if($products->count() < 16){$is_more='no';}
            $view = view('website.includs.productsByBanner')->with(['products'=>$products])->render();
            return response()->json(['html' => $view,'is_more'=>$is_more]);
        }  
        
        return view('website.products.productsByBanner', [
           // 'banner' =>$banner,
            'products' =>$products,
            'banner_name' =>$banner_name,
        ]);
    } 
    public function topRated(Request $request)
    {
       
        $top_rates=Product::orderBy('rate', 'desc')->where('status','active')->paginate(16);
        
              if ($request->ajax()) { 
            $is_more='yes';
            if($top_rates->count() < 16){$is_more='no';}
            $view = view('website.includs.topRate')->with(['top_rates'=>$top_rates])->render();
            return response()->json(['html' => $view,'is_more'=>$is_more]);
        }
        
        return view('website.products.topRated', [
            'top_rates' =>$top_rates,
        ]);
    } 
    public function recomendedProducts(Request $request)
    {
       
        $recomendedProducts=Product::orderBy('created_at', 'desc')->where('status','active')->inRandomOrder()->paginate(18);
     
          if ($request->ajax()) { 
            $is_more='yes';
            if($recomendedProducts->count() < 16){$is_more='no';}
            $view = view('website.includs.recomendedProduct')->with(['recomendedProducts'=>$recomendedProducts])->render();
            return response()->json(['html' => $view,'is_more'=>$is_more]);
        }
        
        return view('website.products.recomendedProducts', [
            'recomendedProducts' =>$recomendedProducts,
        ]);
    } 

   
    public function search (Request $request)
    {  

        $search = $request->input('search');
 
        $products=Product::orderBy('created_at', 'desc')->whereTranslationLike('name', '%'. $search.'%')->paginate(18);
   
            if ($request->ajax()) { 
            $is_more='yes';
            if($products->count() < 16){$is_more='no';}
            $view = view('website.includs.search')->with(['products'=>$products])->render();
            return response()->json(['html' => $view,'is_more'=>$is_more]);
        }
        
        return view('website.search', [
            'products' =>$products,
            'search' =>$search,
        ]);
    }
    
    public function filter (Request $request,$id)
    {  

 
        $products=Product::where('status','active');
   
          if ($request->type== 'viewBy' ) {

              $products = $products->orderBy('id', $id)->get();
            
        }
          if ($request->type== 'alpha' ) {

           
            if($id=='asc'){
              
             $sorted = Product::get()->sortBy('name')->pluck('id')->toArray();
             $orderedIds = implode(',', $sorted);
             $products = Product::orderByRaw(\DB::raw("FIELD(id, ".$orderedIds." )"))->paginate(1);
            }else{
                
             $sorted = Product::get()->sortByDesc('name')->pluck('id')->toArray();
             $orderedIds = implode(',', $sorted);
             $products = Product::orderByRaw(\DB::raw("FIELD(id, ".$orderedIds." )"))->paginate(1);
             
        
            } 
        }
        
        
           if ($request->ajax()) { 
            $is_more='yes';
            if($products->count() < 16){$is_more='no';}
            $html = view('website.includs.broductsBySubcategoey')->with(['products'=>$products])->render();
            return response()->json(['html' => $html,'is_more'=>'yes']);
        }
        
          $html=view('website.includs.broductsBySubcategoey')->with(['products' =>$products])->render();
       
        return response()->json(['status' => true, 'code' => 200 ,'html'=>$html]);
    }
    
    public function getConfigProduct ($id)
    {  

       $product=Product::with('specifications')->findOrFail($id);
        $cities=City::where('status','active')->get();
                 $product_barcode='';
        if($product->barcode !=''){
        $barcode = new Picqer\Barcode\BarcodeGeneratorPNG();
        $product_barcode = $barcode->getBarcode($product->barcode, $barcode::TYPE_CODE_128);
        }
        
         if($product){
             $config_products=Product::where('id','!=',$product->id)->where('configured_code',$product->configured_code)->get();
             $reviews=ProductReview::where('product_id',$id)->get();
           //  return $config_products;
             $view = view('website.more.configProduct',['config_products'=>$config_products ,'reviews'=>$reviews,'product_barcode'=>$product_barcode])->with('product',$product)->with('cities',$cities)->render();
              return response()->json( ['view'=>$view]);
         }
   

    }
}
