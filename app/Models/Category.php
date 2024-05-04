<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class Category extends Model
{
    //
    public $translatedAttributes = ['name'];

    use SoftDeletes,Translatable;
    protected $table = 'categories';
    protected $fillable = ['image','store_id'];
    protected $hidden = ['updated_at', 'deleted_at'];




    public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/category/'. $value);
    }
    return url('uploads/images/logo.png');
    }

    public function childs(){
        return $this->hasMany(Category::class,'category_id');
    }
    public  function  category(){
        return$this-> hasMany(SubCategory::class ,'category_id');
    }
    public  function  subcategory(){
        return$this-> hasMany(SubCategory::class ,'category_id'); //,'subcategory_id'
    }
}
