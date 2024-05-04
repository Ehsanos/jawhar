<?php

namespace App\Http\Controllers\WEB\Site;

use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Language;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Token;
use App\Models\Setting;
use App\Models\Cart;
use App\Models\Department;
use App\Models\Product;
use App\Models\City;
use App\Models\Combo;
use App\Models\Productoffer;
use App\Models\Comboproduct;
use App\Models\UserAddresse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Notifiy;
use Illuminate\Support\Facades\Auth;
use DB ;

use App\Payment\Merchant;
use App\Payment\Connection;
use App\Payment\Parser;


class OrderController extends Controller
{
    
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



    public  function epayment(Request $request){
    
        $configArray = array();
        
        /*
         Set all your configuration here
        
         If you want to have multiple configuration sets, copy and paste
         the configuration lines and create a new array with a different variable name
         this array can then be parsed into the Merchant constructor from process.php
         
         The debug configuration setting is useful for printing requests and responses
         If you're receiving an error or unexpected output, set this flag to TRUE
         The debug output will help indicate the cause of the problem
         
         Please comment the proxy settings if you do not wish to use a proxy
         
        */
        
        // If using a proxy server, uncomment the following proxy settings
        
        // If no authentication is required, only uncomment proxyServer
        // Server name or IP address and port number of your proxy server
        //$configArray["proxyServer"] = "server:port";
        
        // Username and password for proxy server authentication
        //$configArray["proxyAuth"] = "username:password";
        
        // The below value should not be changed
        //$configArray["proxyCurlOption"] = CURLOPT_PROXYAUTH;
        
        // The CURL Proxy type. Currently supported values: CURLAUTH_NTLM and CURLAUTH_BASIC 
        //$configArray["proxyCurlValue"] = CURLAUTH_NTLM;
        
        
        // If using certificate validation, modify the following configuration settings
        
        // alternate trusted certificate file
        // leave as "" if you do not have a certificate path
        //$configArray["certificatePath"] = "C:/ca-cert-bundle.crt";
        
        // possible values:
        // FALSE = disable verification
        // TRUE = enable verification
        $configArray["certificateVerifyPeer"] = TRUE;
        
        // possible values:
        // 0 = do not check/verify hostname
        // 1 = check for existence of hostname in certificate
        // 2 = verify request hostname matches certificate hostname
        $configArray["certificateVerifyHost"] = 2;
        
        
        // Base URL of the Payment Gateway. Do not include the version.
        $configArray["gatewayUrl"] = "https://ncbtest.mtf.gateway.mastercard.com/api/nvp";
        
        // Merchant ID supplied by your payments provider
        $configArray["merchantId"] = "Test601157406";
        
        // API username in the format below where Merchant ID is the same as above
        $configArray["apiUsername"] = "merchant.Test601157406";
        
        // API password which can be configured in Merchant Administration
        $configArray["password"] = "Miniso2030$";
        
        // The debug setting controls displaying the raw content of the request and 
        // response for a transaction.
        // In production you should ensure this is set to FALSE as to not display/use
        // this debugging information
        $configArray["debug"] = FALSE;
        
        // Version number of the API being used for your integration
        // this is the default value if it isn't being specified in process.php
        $configArray["version"] = "13";
        
        /* 	
         This class holds all the merchant related variables and proxy 
         configuration settings	
        */
            
        //   Merchant ID: Test601157406
        // Username: Administrator
        // Password: Miniso2030$ 
                
        // Unset HTML submit button so it isn't sent in POST request
        if ($request->has("submit"))
        	unset($request->submit);
        
        // Creates the Merchant Object from config. If you are using multiple merchant ID's, 
        // you can pass in another configArray each time, instead of using the one from configuration.php
        $merchantObj = new Merchant($configArray);
        
        // The Parser object is used to process the response from the gateway and handle the connections
        $parserObj = new Parser($merchantObj);
        
        // In your integration, you should never pass this in, but store the value in configuration
        // If you wish to use multiple versions, you can set the version as is being done below
        if ($request->has("version")) {
        	$merchantObj->SetVersion($request->version);
        	unset($request->version);
        }
        
        // form transaction request
        $requestNew = $parserObj->ParseRequest($merchantObj, $request);
        
        // if no post received from HTML page (parseRequest returns "" upon receiving an empty $_POST)
        if ($requestNew == "")
        	die();
        
        // print the request pre-send to server if in debug mode
        // this is used for debugging only. This would not be used in your integration, as DEBUG should be set to FALSE
        if ($merchantObj->GetDebug())
        	echo $requestNew . "<br/><br/>";
        
        // forms the requestUrl and assigns it to the merchantObj gatewayUrl member
        // returns what was assigned to the gatewayUrl member for echoing if in debug mode
        $requestUrl = $parserObj->FormRequestUrl($merchantObj);
        
        // this is used for debugging only. This would not be used in your integration, as DEBUG should be set to FALSE
        if ($merchantObj->GetDebug())
        	echo $requestUrl . "<br/><br/>";
        	
        // attempt transaction
        // $response is used in receipt page, do not change variable name
        $response = $parserObj->SendTransaction($merchantObj, $requestNew);
        
        // print response received from server if in debug mode
        // this is used for debugging only. This would not be used in your integration, as DEBUG should be set to FALSE
        if ($merchantObj->GetDebug()) {
        	// replace the newline chars with html newlines
        	$response = str_replace("\n", "<br/>", $response);
        	echo $response . "<br/><br/>";
        	die();
        }
        
        
        $errorMessage = "";
        $errorCode = "";
        $gatewayCode = "";
        $result = "";
        
        $responseArray = array();
        
        // meaning there's a string cURL Error in the response
        if (strstr($response, "cURL Error") != FALSE) {
          print("Communication failed. Please review payment server return response (put code into debug mode).");
          die();
        }
        
        // [Snippet] howToDecodeResponse - start
        // loop through server response and form an associative array
        // name/value pair format
        if (strlen($response) != 0) {
          $pairArray = explode("&", $response);
          foreach ($pairArray as $pair) {
            $param = explode("=", $pair);
            $responseArray[urldecode($param[0])] = urldecode($param[1]);
          }
        }
        // [Snippet] howToDecodeResponse - end
        
        // [Snippet] howToParseResponse - start
        if (array_key_exists("result", $responseArray))
          $result = $responseArray["result"];
          // [Snippet] howToParseResponse - end
        
        // Form error string if error is triggered
        if ($result == "FAIL") {
          if (array_key_exists("failureExplanation", $responseArray)) {
            $errorMessage = rawurldecode($responseArray["failureExplanation"]);
          }
          else if (array_key_exists("supportCode", $responseArray)) {
            $errorMessage = rawurldecode($responseArray["supportCode"]);
          }
          else {
            $errorMessage = "Reason unspecified.";
          }
        
          if (array_key_exists("failureCode", $responseArray)) {
            $errorCode = "Error (" . $responseArray["failureCode"] . ")";
          }
          else {
            $errorCode = "Error (UNSPECIFIED)";
          }
        }
        
        else {
          if (array_key_exists("response.gatewayCode", $responseArray))
            $gatewayCode = rawurldecode($responseArray["response.gatewayCode"]);
          else
            $gatewayCode = "Response not received.";
        }

    
        if ($errorCode != "" || $errorMessage != "") {
          return $errorCode." ".$errorMessage;
        }
        else {
            return $gatewayCode." ".$result;
         }

    }
        
    public function image_extensions()
    {

        return array('jpg', 'png', 'jpeg', 'gif', 'bmp', 'pdf');

    }



        public  function checkout(Request $request){

           // $carts=Cart::where('user_key',Session::get('cart.ids'))->get();
                 if(auth()->check()){
                   $carts=Cart::where('user_id',Auth::user()->id)->orWhere('user_key',Session::get('cart.ids'))->with('product')->get();
                }else{
                   $carts=Cart::where('user_key',Session::get('cart.ids'))->with('product')->get();
                }
        
            $adresses=UserAddresse::where('user_id',Auth::user()->id)->get();
            $cities=City::where('status','active')->get();
            
            return view('website.checkout',[
                'carts'=>$carts,
                'adresses'=>$adresses,
                'cities'=>$cities
                ]);
        }
        
        

        

  

    public function store(Request $request)
    {
          if(Session::has('miniso_admin_loged_in_as_user','miniso_admin_loged_in_as_user')){
              return;
          }
          
         if(auth()->check()){
           if(Cart::where('user_id',Auth::user()->id)->orWhere('user_key',Session::get('cart.ids'))->with('product')->count() <1){
               return ;
           }
        }else{
           if(Cart::where('user_key',Session::get('cart.ids'))->count() < 1){
               return ;
        }
        }
          
        // if(Cart::where('user_key',Session::get('cart.ids'))->count() < 1){
        //     return;
        // }
      //    Productoffer::where('product_id',$one->product->id)->where('is_flash',1)->where('offer_to','>',now());
          $rules = [
        'orderAdress' => 'required',
        'paymentWay' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['code' => 300,
                'validator' =>implode("\n",$validator-> messages()-> all()) ]);
        }
        
        $total = 0 ;
        $is_flash = 0 ;
        $offer_id = 0 ;
      //  $carts=Cart::where('user_key',Session::get('cart.ids'))->get();
        
        if(auth()->check()){
           $carts=Cart::where('user_id',Auth::user()->id)->orWhere('user_key',Session::get('cart.ids'))->with('product')->get();
        }else{
           $carts=Cart::where('user_key',Session::get('cart.ids'))->with('product')->get();
        }
        
         foreach($carts as $one){
             if($one->type==1){
                 
              if($one->product->is_flash !=0){
                 $id=Productoffer::where('product_id',$one->product->id)->where('is_flash',1)->where('offer_to','>',now())->first();
                  if(OrderProduct::where('type',1)->where('target',$one->product->id)->where('is_flash',1)->where('offer_id',$id->id)->exists()){
                    $total += $one->product->price * $one->quantity;
                  }else{
                       $total += $one->product->flash_price;
                       $is_flash=1;
                       $offer_id=1;
                  }
                  
             }elseif($one->product->price_offer !=0){
                $total += $one->product->price_offer * $one->quantity;
             }else{
             $total += $one->product->price * $one->quantity;
             }
             }else{
                  $total += $one->combo->price * $one->quantity;
             }
         }
       

         if($carts){
                $order=new Order();
                $order->user_id = Auth::user()->id;
                $order->total_price = $total ;
                $order->address_id = $request->orderAdress ;
                $order->customer_locale = Session::get('locale');
                $order->customer_ip = request()->ip();
                $order->payment_type = $request->paymentWay ;
                $order->save();
                    
                foreach($carts as $details){
                     if($details->type==1){
                         if($details->product->is_flash==1){
                             $price=$details->product->flash_price;
                         }elseif($details->product->price_offer !=0){
                            $price=$details->product->price_offer;
                         }else{
                            $price=0;
                         }
                         $data[] = [
                        'order_id' => $order->id,
                        'type' => 1,
                        'target' => $details->target,
                        'quantity' => $details->quantity,
                        'price' =>  $details->product->price,
                        'offer_price' => $price,
                        'is_flash' => $is_flash,
                        'offer_id' => $offer_id,
                        'user_id'=>Auth::user()->id,
                    ]; 
                     }else{
                    
                           $data[] = [
                        'order_id' => $order->id,
                        'type' => 2,
                        'target' => $details->target,
                        'quantity' => $details->quantity,
                        'price' => $details->combo->price,
                        'offer_price' => 0,
                        'is_flash' => $is_flash,
                        'offer_id' => $offer_id,
                        'user_id'=>Auth::user()->id,
                    ];  
                     }
    
                }          
                OrderProduct::insert($data);
                $cart_qty=Cart::where('type',1)->where('user_key',Session::get('cart.ids'))->get();
                foreach($cart_qty as $item)
                {
                    Product::where('id',$item->target)->decrement('quantity', $item->quantity);
                }
                 $ids=Cart::where('type',2)->where('user_key',Session::get('cart.ids'))->pluck('target')->toArray();
                 $combos=Combo::whereIn('id',$ids)->pluck('id')->toArray();
                 $combo=Comboproduct::whereIn('combo_id',$combos)->pluck('product_id')->toArray();
                 Product::whereIn('id',$combo)->decrement('quantity');

                
                   
             //   Cart::where('user_key',Session::get('cart.ids'))->delete();
                Cart::where('user_id',Auth::user()->id)->orWhere('user_key',Session::get('cart.ids'))->delete();
        

                    return response()->json(['status' => true]);
                 
         } else{
                 return redirect()->back()->with('status', __('website.no-produt'));
              }
    }





public  function checkPayment(Request $request){

}










  
   
    



}


