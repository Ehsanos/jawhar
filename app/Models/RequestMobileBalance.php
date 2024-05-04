<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestMobileBalance extends Model
{
    use SoftDeletes;
    protected $table = 'request_mobile_balance';
    protected $fillable = ['user_id' , 'mobile','balance','network_id'];
    protected $hidden = ['updated_at', 'deleted_at'];


    public function mobileCompany(){
        return $this->belongsTo(MobileCompany::class,'network_id','id');
    }
    public function requestmobilebalance(){
        return $this->belongsTo(MobileNetworkPackages::class,'network_packages_id','id');
    }
    public function userName(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}