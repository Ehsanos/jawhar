<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use SoftDeletes ;
    protected $table = 'games';
    protected $hidden = ['updated_at'];
    protected $appends = ['new_price'];

    
    public function getNewPriceAttribute($value){
        return $this->api_id > 0 ? ($this->price + (($this->price * $this->commission)/100)) : $this->price;
    }
    
    public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/games/' . $value);
    }
    return url('uploads/images/logo.png');
    }
    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
    
    public function api()
    {
        return $this->belongsTo(Api::class,'api_id');
    }
}
  
        