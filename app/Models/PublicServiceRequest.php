<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicServiceRequest extends Model
{
    use SoftDeletes ;
    protected $table = 'public_service_request';
    protected $fillable = ['name', 'address' , 'mobile'];
    protected $hidden = ['updated_at'];
    protected $with = ['city','publicService'];

    public function publicService()
    {
        return $this->belongsTo(PublicService::class,'sub_public_service_id');
    }
   
    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
   
}
  
        