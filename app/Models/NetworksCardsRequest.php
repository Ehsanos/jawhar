<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NetworksCardsRequest extends Model
{
    use SoftDeletes ;
    protected $table = 'networks_cards_request';
    protected $fillable = ['name', 'address' , 'mobile'];
    protected $hidden = ['updated_at'];
    protected $with = ['city','publicService'];

    public function publicService()
    {
        return $this->belongsTo(NetworksCards::class,'network_id');
    }
   
    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
   
}
  
        