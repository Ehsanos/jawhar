<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{

    use SoftDeletes;
    protected $table = 'stores';

    protected $fillable = ['user_id','store_name','mobile','logo','address','details','logo'];
    protected $hidden = ['updated_at', 'deleted_at'];
    protected $casts = ['store_category_id' => 'integer', 'user_id' => 'integer', 'city_id' => 'integer'];
  protected $appends = ['is_favorite','enable_notification' ];



    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
    public function category()
    {
        return $this->belongsTo(StoreCategory::class,'store_category_id');
    }

    public function getLogoAttribute($value)
    {  if($value){
            return url('uploads/images/stores/' . $value);
    }
    return url('uploads/images/logo.png');
    }
    public function getIsFavoriteAttribute()
    {        $user_id = auth('api')->id();

           $favorite =Favorite::where('user_id', $user_id)->where('store_id',$this->id)->first();
           if($favorite){
                  return 1;
           }else{
                return 0;
           }
    }
    public function getEnableNotificationAttribute()
    {        $user_id = auth('api')->id();

           $favorite =EnableNotificationNetwork::where('user_id', $user_id)->where('store_id',$this->id)->first();
           if($favorite){
                  return 1;
           }else{
                return 0;
           }
    }
}
