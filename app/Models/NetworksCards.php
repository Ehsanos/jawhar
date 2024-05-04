<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class NetworksCards extends Model
{
    //

  

    use SoftDeletes;
    protected $table = 'networks_cards';
    protected $hidden = ['updated_at', 'deleted_at'];

    protected $fillable = ['card_id','pin','password','wifi_id','network_id','is_used','Status'];
    
    public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/networks/'. $value);
    }
    return url('uploads/images/logo.png');
    }
  
    public  function  wifiName(){
        return $this->belongsTo(Wifi::class, 'wifi_id');
    }
    public  function  networkName(){
        return $this->belongsTo(Networks::class, 'network_id');
    }

}
