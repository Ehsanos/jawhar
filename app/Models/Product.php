<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;
use Illuminate\Http\Request;

class Product extends Model
{
    //
    public $translatedAttributes = ['name' ,'description'];

    use SoftDeletes,Translatable;
    protected $table = 'products';
    protected $fillable = ['image' , 'price' ,'discount', 'offer_from' ,'offer_to'];
    protected $hidden = ['updated_at', 'deleted_at','translations'];

  protected $appends = ['store_mobile','is_fevorite','is_cart','real_price' ];
    
//use App\Models\Setting;


    public function getRealPriceAttribute()
    {
        return $this->getAttributes()['price'];
    }


    public function getPriceAttribute($value)
    {
        if(get_user_carrency_from_api() == "turkey")
        {
            return  $this->result($value);

        }
        elseif(get_user_carrency_from_api() == "dollar")
        {
            return  $this->result1($value);
        }

        return  $this->result($value);
    }
    public function result($value)
{
    if($this->is_dollar){
        $exchange_rate = Setting::orderByDesc('id')->first()->exchange_rate;

        return (double)number_format($exchange_rate * $value, 2, '.', '');
    }
    else{
        return(double)number_format($value, 2, '.', '');
    }

}
    public function result1($value)
    {
        if($this->is_dollar){
            return(double)number_format($value, 2, '.', '');
        }
        else{
            $exchange_rate = Setting::orderByDesc('id')->first()->exchange_rate;

            return (double)number_format( $value / $exchange_rate, 2, '.', '');
        }

    }

    public function getIsFevoriteAttribute()
    {
        $fcm =Favorite::where('fcm_token',request()->header('fcmToken'))->where('product_id',$this->id)->first();
        if($fcm){
            return "1";
        }else{
            return "0";
        }

    }
    public function getIsCartAttribute()
    {        $user_id = auth('api')->id();

           $cart =Cart::where('user_id', $user_id)->where('product_id',$this->id)->first();
           if($cart){
               return $cart->quantity ;
           }else{
                return "0";
           }

    }
    public function getStoreMobileAttribute()
    {        
           $store =Store::where('id', $this->store_id)->first();
           if($store){
               return $store->mobile ;
           }else{
                return "No Number";
           }

    }


    public function getImageAttribute($value)
    {  if($value){
            return url('uploads/images/products/' . $value);
    }
    return url('uploads/images/logo.png');
    }

    public function subcategory(){
        return $this->belongsTo(SubCategory::class,'subCategory_id','id');
    }
    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    //relatioship between order and product many to many
    public function orders()
    { 
        return $this->belongsToMany(Order::class,'order_products','order_id','product_id')->withPivot('quantity','price');
    }
    
        public function attachments(){
        return $this->hasMany(ProductImage::class,'product_id');
    }
        public function colors(){
        return $this->hasMany(ProductColor::class,'product_id');
    }
        public function sizes(){
        return $this->hasMany(ProductSize::class,'product_id');
    }
        public function userReview(){
        return $this->hasMany(ProductReview::class,'product_id');
    }
    
        public function additions(){
        return $this->hasMany(ProductAddition::class,'product_id');
     }
       

}
