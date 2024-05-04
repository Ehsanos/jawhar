<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;


class ProductImage  extends Model
{
   

    use SoftDeletes;
    protected $fillable = ['product_id', 'product_img'];
    protected $table = 'product_image';
    protected $hidden = ['updated_at', 'deleted_at'];


    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function getProductImgAttribute($image)
    {
        return !is_null($image)? url('uploads/images/products/'.$image):url('uploads/images/products/defualt.jpg');
    }
}