<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class File extends Model
{

    use SoftDeletes;
    protected $table = 'files';

    protected $fillable = ['admin_id' ,'type'];
    protected $hidden = ['updated_at', 'deleted_at'];

    // public function getFileNameAttribute($value)
    // {
    //     return url('uploads/excel/' . $value);
    // }

}
