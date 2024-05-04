<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class Review extends Model
{
    //

    use SoftDeletes;
    protected $table = 'reviews';
    protected $hidden = ['updated_at', 'deleted_at'];


    public function getImageAttribute($value)
    {
        return url('uploads/images/reviews/'. $value);
    }


}
