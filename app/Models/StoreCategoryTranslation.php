<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreCategoryTranslation extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['name'];
    protected $hidden = ['updated_at', 'deleted_at','created_at'];
    
}
