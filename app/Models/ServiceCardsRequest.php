<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCardsRequest extends Model
{
    use SoftDeletes ;
    protected $table = 'service_cards_request';
    protected $fillable = ['name', 'address' , 'mobile'];
    protected $hidden = ['updated_at'];
    protected $with = ['service','product','cards','user','city'];



// texts	id	user_id	service_id	service_cards_id	product_service_id
    public function service()
    {
        return $this->belongsTo(Service::class,'service_id');
    }
    public function cards()
    {
        return $this->belongsTo(ServiceCards::class,'service_cards_id');
    }
    public function product()
    {
        return $this->belongsTo(ProductService::class,'product_service_id');
    }
   
    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
   
}
  
        