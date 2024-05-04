<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class OrderProduct extends Model
{
    use SoftDeletes;
    protected $table = 'order_products';
    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];
   protected $with = ['product','color','size'];
    
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id', 'id');
    }
            public function color()
    {
        return $this->belongsTo(Color::class,'color_id');
    }
    public function size()
    {
        return $this->belongsTo(Size::class,'size_id');
    }
   
}