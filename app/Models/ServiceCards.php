<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCards extends Model
{
    //

  

    use SoftDeletes;
    protected $table = 'service_cards';
    protected $hidden = ['updated_at', 'deleted_at'];

    protected $fillable = ['card_id','pin','password','product_id','service_id','is_used','Status'];
    
    public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/networks/'. $value);
    }
    return url('uploads/images/logo.png');
    }
  
    public  function  serviceName(){
        return $this->belongsTo(Service::class, 'service_id');
    }
    public  function  productName(){
        return $this->belongsTo(ProductService::class, 'product_id');
    }
    
    public function admin(){
        return $this->belongsTo(Admin::class,'admin_id');
 }

}
