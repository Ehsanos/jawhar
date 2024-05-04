<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductServiceTranslation extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['name'];
}
