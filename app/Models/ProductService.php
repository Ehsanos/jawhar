<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductService extends Model
{
    public $translatedAttributes = ['name'];
    use SoftDeletes ,Translatable;
    protected $table = 'product_service';
    protected $fillable = ['image'];
    protected $hidden = ['updated_at'];
  protected $appends = ['nagma_price'];


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
    
    public function getAppPercentAttribute(){
        return ($this->getRawOriginal('price') - $this->purchasing_price) / $this->getRawOriginal('price') *100 ;
    }
      
    public function getNagmaPriceAttribute(){
       $promo_code = PromoCode::where('target_type' , '1')->whereHas('users' , function ($q){
                $q->where('user_id' , auth('api')->id());
            })->whereHas('targets' , function($q2){
                $q2->where('target_id' , $this->id);
            })->first();
            if($promo_code){
              $jawharProfit = ($this->getRawOriginal('price') * @$this->app_percent) / 100;
              $price = ($jawharProfit * $promo_code->percent) / 100 ;
              return $this->getRawOriginal('price') - $price ;
          }
          return 0;
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

    public  function  serviceCards(){
        return $this->hasMany(ServiceCards::class, 'product_id')->where('is_used',0);
    }

    public function getImageAttribute($value)
    {
        return url('uploads/images/productServices/'. $value);
    }
    public function productService(){
        return $this->belongsTo(Service::class,'service_id');
    }
    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
    }
    public function city(){
        return $this->belongsTo(City::class,'city_id')->withDefault([
        'name' => __('cp.all'),
    ]);
    }
    public function whatsapp(){
        return $this->belongsTo(WhatsappUsers::class,'wapp_id');
    }
    public function whatsapp_service_worker(){
        return $this->belongsTo(WhatsappUsers::class,'wapp_id_service_worker');
    }
    
}
