<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;


class AzkarDetails  extends Model
{
   

    use SoftDeletes;
    protected $fillable = ['azkar_id', 'details','repetition'];
    protected $table = 'azkar_details';
    protected $hidden = ['updated_at', 'deleted_at'];


    public function azkar()
    {
        return $this->belongsTo(Azkar::class,'azkar_id');
    }

    public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/azkar/' . $value);
    }
    return url('uploads/images/logo.png');
    }
}