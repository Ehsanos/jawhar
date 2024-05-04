<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Country extends Model
{
    public $translatedAttributes = ['name'];

    use SoftDeletes,Translatable;
    protected $table = 'countries';

}