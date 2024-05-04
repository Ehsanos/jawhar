<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class News extends Model
{

    use SoftDeletes;
    protected $table = 'news';
    protected $fillable = ['image'];
    protected $hidden = ['updated_at', 'deleted_at'];


    public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/news/'. $value);
    }
    return url('uploads/images/logo.png');
    }
    

}
