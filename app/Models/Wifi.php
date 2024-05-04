<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Wifi extends Model
{
    public $translatedAttributes = ['name'];

    use SoftDeletes;
    protected $table = 'wifi';
    protected $hidden = ['updated_at', 'deleted_at'];

    protected $fillable = [ 'name','city_id','image'];

    public  function  city(){
        return $this-> belongsTo(City::class ,'city_id');
    }
    

    public function store(){
        return $this->belongsTo(Store::class ,'store_id');
    }
    
  
      public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/networks/'. $value);
    }
    return url('uploads/images/logo.png');
    }
}
