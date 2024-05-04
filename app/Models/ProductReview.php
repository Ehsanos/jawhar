<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class ProductReview  extends Model
{
   

    use SoftDeletes;
    //protected $fillable = [];
    protected $table = 'product_reviews';
    protected $hidden = ['updated_at', 'deleted_at'];


    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }


}