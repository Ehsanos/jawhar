<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class Productoffer extends Model
{
 


    use SoftDeletes;
    protected $table = 'productoffers';
    protected $fillable = ['discount', 'offer_from' ,'offer_to'];
    protected $hidden = ['updated_at', 'deleted_at',];

    

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    } 


}
