<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    public $translatedAttributes = ['name'];
    use SoftDeletes ,Translatable;
    protected $table = 'service';
    protected $hidden = ['updated_at'];

    public function productService(){
        return $this->hasMany(ProductService::class,'service_id');
    }
    
    public  function  serviceCards(){
        return $this->hasMany(ServiceCards::class, 'service_id')->where('is_used',0);
    }
    
}
