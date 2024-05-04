<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use Translatable;
    public $translatedAttributes = ['title','join_description','description', 'address', 'key_words','feature_description','expectations','expectations_description','ranking','ranking_description','champions','champions_description','statistics','statistics_description','reviews_description'];
//    protected $appends = ['privacy','terms','aboutus'];
    protected $hidden = ['translations'];

    public function getLogoAttribute($logo)
    {
        return !is_null($logo)?url('uploads/images/settings/'.$logo):null;
    }

    public function getImageAttribute($image)
    {
        //return url('uploads/settings/'.$image);
        return !is_null($image)?url('uploads/images/settings/'.$image):null;
    }



}

