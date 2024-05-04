<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductServiceRequest extends Model
{


    public function user()
    {
        return $this->belongsTo(User::class,'user_id')->withTrashed();
    }
    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }
    public function Whatsapp()
    {
        return $this->belongsTo(WhatsappUsers::class,'whatsapp_id');
    }
    public function productService()
    {
        return $this->belongsTo(ProductService::class,'product_service_id');
    }
}
