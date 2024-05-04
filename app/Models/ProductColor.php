<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;


class ProductColor  extends Model
{
   

    use SoftDeletes;
    protected $fillable = ['product_id', 'color_id'];
    protected $table = 'product_colors';
    protected $hidden = ['updated_at', 'deleted_at'];
    protected $with = ['color'];


    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function color()
    {
        return $this->belongsTo(Color::class,'color_id');
    }
    public function getColorImageAttribute($value)
    {  if($value){
            return url('uploads/images/products/' . $value);
    }
   '';
    }

}