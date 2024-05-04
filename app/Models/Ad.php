<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
    //
    public $translatedAttributes = ['details','title'];

    use SoftDeletes,Translatable;
    protected $table = 'ads';
    protected $fillable = ['link' , 'image'];
    protected $hidden = ['updated_at', 'deleted_at'];


    public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/ads/' . $value);
    }
    return url('uploads/images/logo.png');
    }
}
   