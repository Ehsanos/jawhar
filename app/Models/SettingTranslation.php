<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingTranslation extends Model
{
    protected $fillable = ['locale', 'setting_id','title','join_description','description', 'address', 'key_words','feature_description','expectations','expectations_description','ranking','ranking_description','champions','champions_description','statistics','statistics_description','reviews_description'];
}
