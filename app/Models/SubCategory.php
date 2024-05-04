<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class SubCategory extends Model
{
    //
    

    use SoftDeletes,Translatable;
    public $translatedAttributes = ['name'];
    protected $table = 'sub_categories';
    protected $fillable = ['image','store_id'];
    protected $hidden = ['updated_at', 'deleted_at'];


    public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/category/'. $value);
    }
    return url('uploads/images/logo.png');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    
}
