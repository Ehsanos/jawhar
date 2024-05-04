<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductAddition extends Model
{
   

    use SoftDeletes;
    protected $table = 'product_additions';
    protected $with = ['addition'];
    protected $hidden = ['updated_at', 'deleted_at'];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    public function addition(){
        return $this->belongsTo(Addition::class,'addition_id');
    }
 

}