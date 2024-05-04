<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Color extends Model
{
    public $translatedAttributes = ['name'];
    use SoftDeletes,Translatable;
    protected $table = 'colors';
           protected $hidden = [ 'updated_at','deleted_at','translations','created_at','status'];
    public function productColors()
{
     return $this->hasMany(Product::class,'color_id');
   
}

}