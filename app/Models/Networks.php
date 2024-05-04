<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Networks extends Model
{
    use SoftDeletes;
    protected $table = 'networks';
    protected $hidden = ['updated_at', 'deleted_at'];
    protected $appends = ['enable_notification' ,'nagma_price'];

    protected $fillable = ['name','price','image','wifi_id','Status'];


    
    
    public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/networks/'. $value);
    }
        return url('uploads/images/logo.png');
    }

    public  function  wifiName(){
        return $this->belongsTo(Wifi::class, 'wifi_id');
    }
    public  function  networksCards(){
        return $this->hasMany(NetworksCards::class, 'network_id')->where('is_used',0);
    }
    
    public function getNagmaPriceAttribute(){
       $promo_code = PromoCode::where('target_type' , '2')->whereHas('users' , function ($q){
                $q->where('user_id' , auth('api')->id());
            })->whereHas('targets' , function($q2){
                $q2->where('target_id' , $this->id);
            })->first();

             if($promo_code){
              $jawharProfit = ($this->getRawOriginal('price') * @$this->wifiName->store->app_percent) / 100;
              $price = ($jawharProfit * $promo_code->percent) / 100 ;
              return $this->getRawOriginal('price') - $price ;
          }
          return 0;
    }
    
   
    public function getEnableNotificationAttribute()
    {        $user_id = auth('api')->id();

        $favorite =EnableNotificationNetwork::where('user_id', $user_id)->where('network_id',$this->id)->first();
        if($favorite){
            return 1;
        }else{
            return 0;
        }
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





}
