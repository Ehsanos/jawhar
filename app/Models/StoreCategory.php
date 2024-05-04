<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class StoreCategory extends Model
{
    //
    public $translatedAttributes = ['name'];

    use SoftDeletes,Translatable;
    protected $table = 'store_categories';
    protected $fillable = ['image'];
    protected $hidden = ['created_at','updated_at', 'deleted_at','translations'];




    public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/category/'. $value);
    }
    return url('uploads/images/logo.png');
    }


}
