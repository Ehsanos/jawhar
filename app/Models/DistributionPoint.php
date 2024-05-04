<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DistributionPoint extends Model
{

    use SoftDeletes;
    protected $table = 'distribution_points';
    protected $fillable = ['name' , 'mobile', 'city_id', 'address', 'latitude', 'longitude', 'Status', 'image'];
    protected $hidden = ['updated_at', 'deleted_at'];


    public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/distributionPoints/' . $value);
    }
    return url('uploads/images/logo.png');
    }
        public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
}
   