<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Size extends Model
{
    public $translatedAttributes = ['name'];

    use SoftDeletes,Translatable;
    protected $table = 'sizes';
           protected $hidden = [ 'updated_at','deleted_at','translations','created_at','status'];

    
        public function productSizes()
{
     return $this->hasMany(Product::class,'size_id');
   
}

}