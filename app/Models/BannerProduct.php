<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class BannerProduct extends Model
{

    use SoftDeletes;
    protected $table = 'banner_products';

    //protected $fillable = [];
    protected $hidden = ['updated_at', 'deleted_at'];


    //relatioship between user and Cart one to many

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function banner()
    {
        return $this->belongsTo(Banner::class,'banner_id');
    }

}
