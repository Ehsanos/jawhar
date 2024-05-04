<?php
namespace App\Http\Controllers\WEB\Site;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\Models\Language;
use App\Models\Category;

use App\Models\Product;
use App\Models\Setting;
use App\Models\Department;
use App\Models\Page;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;
use Auth;

class CartController extends Controller
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
    public function index()
    {
         
       
        //$items=\Cart::session('cart')->getContent();
        if(auth()->check()){
           $carts=Cart::where('user_id',Auth::user()->id)->orWhere('user_key',Session::get('cart.ids'))->with('product')->get();
        }else{
           $carts=Cart::where('user_key',Session::get('cart.ids'))->with('product')->get();
        }
       // $items=Cart::where('user_key',Session::get('cart.ids'))->with('product')->get();
        $produts=Product::all()->where('id',1);
        $setting=Setting::all();
        
        return view('website.user.myCart', [
          //  'items' =>$items,
            'produts' =>$produts,
            'setting' =>$setting,
            'carts' =>$carts,
           
        ]);
    }

    public function addComboTcart($id)
    {

        if(Cart::where('user_key',Session::get('cart.ids'))->where('type',2)->where('target',$id)->exists()){

            return ['status'=>false];
        }
          $cart= new Cart();
          $cart->type=2;
          $cart->quantity=1;
          $cart->target=$id;
          if(auth()->check()){
          $cart->user_id=Auth::user()->id;              
          }

          if(Session::get('cart.ids')!=null){
          $cart->user_key=Session::get('cart.ids');
          }else{
            //$cart->user_key=user_keyfloor(time()-999999999).$id.str_random(10);
            $cart->user_key=uniqid().$id.str_random(10);
          }
          $cart->save();

            $cart = [   
                 "ids" => $cart->user_key,                    
            ];
 
            Session::put('cart', $cart);
         
                    $cartAll = view('website.more._headCart')->render();
        //$count=Cart::where('user_key',Session::get('cart.ids'))->count();
        
          if(auth()->check()){
              $count=Cart::where('user_key',Session::get('cart.ids'))->orWhere('user_id',Auth::user()->id)->with('product')->count();
          }else{
              $count=Cart::where('user_key',Session::get('cart.ids'))->count();
        }
        
      $cartTotal = 0 ;
      foreach(Cart::where('user_key',Session::get('cart.ids'))->get() as $one){
      
        if($one->type==1) {
            $cartTotal += @$one->product->price * @$one->quantity;
            
        } else {$cartTotal += @$one->combo->price * @$one->quantity;
            
        }
    }
        return ['status'=>'done','count'=>$count ,'cartTotal'=>$cartTotal, 'cartAll'=>$cartAll];
    }


    public function addToCart($id)
    {

        if(Cart::where('user_key',Session::get('cart.ids'))->where('type',1)->where('target',$id)->exists()){
            Cart::where('user_key',Session::get('cart.ids'))->where('target',$id)->increment('quantity');
            $cartAll = view('website.more._headCart')->render();
          //  $count=Cart::where('user_key',Session::get('cart.ids'))->count();
            
            if(auth()->check()){
              $count=Cart::where('user_key',Session::get('cart.ids'))->orWhere('user_id',Auth::user()->id)->with('product')->count();
            }else{
              $count=Cart::where('user_key',Session::get('cart.ids'))->count();
          }
        
           $cartTotal=\Cart::session('cart')->getTotal();
            return ['status'=>'done','count'=>$count ,'cartTotal'=>$cartTotal, 'cartAll'=>$cartAll];
        }
        $product = Product::find($id);
          $cart= new Cart();
          $cart->type=1;
          $cart->quantity=1;
          $cart->target=$id;
          if(auth()->check()){
          $cart->user_id=Auth::user()->id;              
          }

          if(Session::get('cart.ids')!=null){
          $cart->user_key=Session::get('cart.ids');
          }else{
            $cart->user_key=uniqid().$id.str_random(10);
          }
          $cart->save();

            $cart = [   
                 "ids" => $cart->user_key,                    
            ];
 
            Session::put('cart', $cart);
         
                    $cartAll = view('website.more._headCart')->render();
       // $count=Cart::where('user_key',Session::get('cart.ids'))->count();
        
         if(auth()->check()){
              $count=Cart::where('user_key',Session::get('cart.ids'))->orWhere('user_id',Auth::user()->id)->with('product')->count();
          }else{
              $count=Cart::where('user_key',Session::get('cart.ids'))->count();
        }
        
       //$cartTotal=\Cart::session('cart')->getTotal();
        return ['status'=>'done','count'=>$count , 'cartAll'=>$cartAll];


        
    }


    public function update(Request $request,$id)
    {
        $settings=Setting::findOrFail(1);
            if(auth()->check()){
                  $item=Cart::where('user_key',Session::get('cart.ids'))->where('target',$id)->where('type',1)->orWhere('user_id',Auth::user()->id)->where('target',$id)->where('type',1)->with('product')->first();
            //return $item;
              }else{
                  $item=Cart::where('user_key',Session::get('cart.ids'))->where('target',$id)->where('type',1)->first();
            }
                
     //   $item=Cart::where('user_key',Session::get('cart.ids'))->where('target',$id)->where('type',1)->first();
          if( $request->type==1){
              if($item){
                $item->increment('quantity');
                          $cartTotal = 0 ; 
                         // $x=Cart::where('user_key',Session::get('cart.ids'))->with('product')->get();
                          if(auth()->check()){
                              $x=Cart::where('user_key',Session::get('cart.ids'))->orWhere('user_id',Auth::user()->id)->with('product')->get();
                          }else{
                              $x=Cart::where('user_key',Session::get('cart.ids'))->get();
                        }
            
                          
                          foreach( $x as $one){
                               if($one->type==1) {
                                   if($one->product->price_offer !=0){
                                      $cartTotal += @$one->product->price_offer * @$one->quantity;
                                
                                   } else {
                                      $cartTotal += @$one->product->price * @$one->quantity;
                                   }
                              }else{
                                   $cartTotal += @$one->combo->price * @$one->quantity;
                              }
                            }
                             $total=$cartTotal-($cartTotal*$settings->vat_amount/100);
                             $grand_total=$cartTotal-($cartTotal*$settings->vat_amount/100) +$settings->deliveryCost;
                             $total_with_vat =$cartTotal+$settings->deliveryCost;

                     return ['status'=>'done','total'=>$total,'grand_total'=>$grand_total,'total_with_vat'=>$total_with_vat,'cartTotal'=>$cartTotal];
            }
          }else{
                if($item){
                    if($item->quantity > 1){
                        $item->decrement('quantity');
                            $cartTotal = 0 ;   
                          if(auth()->check()){
                              $x=Cart::where('user_key',Session::get('cart.ids'))->orWhere('user_id',Auth::user()->id)->with('product')->get();
                          }else{
                              $x=Cart::where('user_key',Session::get('cart.ids'))->get();
                        }
                        
                            foreach($x as $one){
                               if($one->type==1) {
                                   if($one->product->price_offer !=0){
                                      $cartTotal += @$one->product->price_offer * @$one->quantity;
                                
                                   } else {
                                      $cartTotal += @$one->product->price * @$one->quantity;
                                   }
                              }else{
                                   $cartTotal += @$one->combo->price * @$one->quantity;
                              }
                             
                            }
                             $total=$cartTotal-($cartTotal*$settings->vat_amount/100);
                             $grand_total=$cartTotal-($cartTotal*$settings->vat_amount/100) +$settings->deliveryCost;
                             $total_with_vat =$cartTotal+$settings->deliveryCost;
                     return ['status'=>'done','total'=>$total,'grand_total'=>$grand_total,'total_with_vat'=>$total_with_vat,'cartTotal'=>$cartTotal];
                    }
                   return;
            }
               return;
          }
           



        
    }
 
    public function remove(Request $request ,$id)
    {
       $settings=Setting::findOrFail(1);
        Cart::where('id',$id)->delete();
        $cartAll = view('website.more._headCart')->render();
      //  $count=Cart::where('user_key',Session::get('cart.ids'))->count();
        
         if(auth()->check()){
              $count=Cart::where('user_key',Session::get('cart.ids'))->orWhere('user_id',Auth::user()->id)->with('product')->count();
          }else{
              $count=Cart::where('user_key',Session::get('cart.ids'))->count();
        }
            
        
        $Total = 0 ;
           if(auth()->check()){
                  $x=Cart::where('user_key',Session::get('cart.ids'))->orWhere('user_id',Auth::user()->id)->with('product')->get();
              }else{
                  $x=Cart::where('user_key',Session::get('cart.ids'))->get();
            }
                        
       foreach($x as $one){
          
            if($one->type==1) {
                     if($one->product->price_offer !=0){
                      $Total += @$one->product->price_offer * @$one->quantity;
                
                   } else {
                      $Total += @$one->product->price * @$one->quantity;
                   }
                
            } else {
                $Total += @$one->combo->price * @$one->quantity;
            }
       }
        $cartTotal=$Total-($Total*$settings->vat_amount/100);
         $grand_total=$Total-($Total*$settings->vat_amount/100) +$settings->deliveryCost;
        $total_with_vat =$Total+$settings->deliveryCost;
        return ['status'=>'done','count'=>$count ,'cartTotal'=>$cartTotal, 'cartAll'=>$cartAll,'grand_total'=>$grand_total,'total_with_vat'=>$total_with_vat];
    }

    
    public function removeByProduct(Request $request ,$id)
    {
        $settings=Setting::findOrFail(1);
       // Cart::where('user_key',Session::get('cart.ids'))->where('target',$id)->where('type',1)->delete();
        
        if(auth()->check()){
                Cart::where('user_key',Session::get('cart.ids'))->where('target',$id)->where('type',1)->orWhere('user_id',Auth::user()->id)->where('target',$id)->where('type',1)->delete();
            //return $item;
              }else{
                Cart::where('user_key',Session::get('cart.ids'))->where('target',$id)->where('type',1)->delete();
            }
            
        $cartAll = view('website.more._headCart')->render();
       // $count=Cart::where('user_key',Session::get('cart.ids'))->count();
        
        if(auth()->check()){
              $count=Cart::where('user_key',Session::get('cart.ids'))->orWhere('user_id',Auth::user()->id)->with('product')->count();
          }else{
              $count=Cart::where('user_key',Session::get('cart.ids'))->count();
        }
        
        $Total = 0 ;
           if(auth()->check()){
                  $x=Cart::where('user_key',Session::get('cart.ids'))->orWhere('user_id',Auth::user()->id)->with('product')->get();
              }else{
                  $x=Cart::where('user_key',Session::get('cart.ids'))->get();
            }
            
       foreach($x as $one){
          
            if($one->type==1) {
                 if($one->product->price_offer !=0){
                      $Total += @$one->product->price_offer * @$one->quantity;
                
                   } else {
                      $Total += @$one->product->price * @$one->quantity;
                   }
                
            } else {
                $Total += @$one->combo->price * @$one->quantity;
            }
       }
        $cartTotal=$Total-($Total*$settings->vat_amount/100);
         $grand_total=$Total-($Total*$settings->vat_amount/100) +$settings->deliveryCost;
        $total_with_vat =$Total+$settings->deliveryCost;
        return ['status'=>'done','count'=>$count ,'cartTotal'=>$cartTotal, 'cartAll'=>$cartAll,'grand_total'=>$grand_total,'total_with_vat'=>$total_with_vat];
    }

    
   
}
