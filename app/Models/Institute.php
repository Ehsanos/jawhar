<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class Institute extends Model
{
    //
    public $translatedAttributes = ['name','details'];

    use SoftDeletes,Translatable;
    protected $table = 'institutes';
    protected $fillable = ['image'];
    protected $hidden = ['updated_at', 'deleted_at'];
  protected $appends = ['enable_notification' ];


    public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/institutes/'. $value);
    }
    return url('uploads/images/logo.png');
    }

    public function getEnableNotificationAttribute()
    {        $user_id = auth('api')->id();

           $favorite =EnableNotificationNetwork::where('user_id', $user_id)->where('institute_id',$this->id)->first();
           if($favorite){
                  return 1;
           }else{
                return 0;
           }
    }
    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
