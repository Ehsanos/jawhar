<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;


class City extends Model
{
    public $translatedAttributes = ['name'];

    use SoftDeletes,Translatable;
    protected $table = 'cities';

    public function country()
    {
        return $this->belongsTo(Country::class,'country', 'id')->first();
    }

}
