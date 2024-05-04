<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestRenewCard extends Model
{
    use SoftDeletes;
    public $table = 'request_renew_card';
    protected $fillable = ['user_id','wifi_id ','mobile','balance','network_id'];
    protected $hidden = ['updated_at','deleted_at'];


    public function userName(){
        return $this->belongsTo(User::class,'user_id','id')->withTrashed();
    }
    public function network(){
        return $this->belongsTo(Networks::class,'network_id','id')->withTrashed();
    }
    public function wifi(){
        return $this->belongsTo(Wifi::class,'wifi_id','id')->withTrashed();
    }
    public function Store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }



}
