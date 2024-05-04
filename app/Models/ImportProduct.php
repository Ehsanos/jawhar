<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ImportProduct extends Model
{

    use SoftDeletes;
    protected $table = 'import_products';

  //  protected $fillable = [];
    protected $hidden = ['updated_at', 'deleted_at'];

    // public function getFileNameAttribute($value)
    // {
    //     return url('uploads/excel/' . $value);
    // }

}
