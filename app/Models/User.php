<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens,SoftDeletes;

    protected $hidden = [
        'password', 'fcm_token', 'image' , 'created_at', 'updated_at', 'deleted_at'
    ];
    
    protected $fillable = ['lang' ];
    

        public function getImageProfileAttribute($value)
    {
        if($value){
           if( filter_var($value, FILTER_VALIDATE_URL) === FALSE){
                 return url('uploads/images/users/' . $value);
            }else{
                return $value;
            }
        }else{
            return url('uploads/images/users/defualtUser.png');
        }
    }
    

    public function getTypeAttribute($value)
    {
        return (string)$value;
    }

    public function getPhoneAttribute($value)
    {
        if ($value != null)
            return $value;
        return "";
    }


    public function notification()
    {
        return $this->hasMany(NotificationMessage::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id','id');

    }
    public function wallet()
    {
        return $this->hasMany(UserWallet::class, 'user_id','id');

    }



}
