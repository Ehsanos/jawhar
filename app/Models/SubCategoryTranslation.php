<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategoryTranslation extends Model
{
    //
    use SoftDeletes;
    protected $table = 'sub_category_translations';
    protected $fillable = ['name'];
}
