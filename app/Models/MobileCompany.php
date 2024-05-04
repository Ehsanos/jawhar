<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MobileCompany extends Model
{
    use SoftDeletes;
    protected $table = 'mobile_companies';
    protected $fillable = ['name'];
    protected $hidden = ['updated_at', 'deleted_at'];

    public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/mobilecompany/' . $value);
    }
        return url('uploads/images/logo.png');
    }
}
   