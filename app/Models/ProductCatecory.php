<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductCatecory extends Model
{
   

    use SoftDeletes;
    protected $table = 'product_catecories';
    protected $with = ['subCategory'];
    protected $hidden = ['updated_at', 'deleted_at'];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    public function subCategory(){
        return $this->belongsTo(Subcategory::class,'subcategory_id');
    }
 

}