<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;


class ProductSize  extends Model
{
   

    use SoftDeletes;
    protected $fillable = ['product_id', 'size_id'];
    protected $table = 'product_sizes';
    protected $hidden = ['updated_at', 'deleted_at'];
    protected $with = ['size'];


    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function size()
    {
        return $this->belongsTo(Size::class,'size_id');
    }
}