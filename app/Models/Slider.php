<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class Slider extends Model
{
    //
    public $translatedAttributes = ['details','title'];

    use SoftDeletes,Translatable;
    protected $table = 'sliders';
    protected $fillable = ['type' , 'image'];
    protected $hidden = ['updated_at', 'deleted_at'];


    public function getImageAttribute($value)
    {
        return url('uploads/images/ads/' . $value);
    }
}
