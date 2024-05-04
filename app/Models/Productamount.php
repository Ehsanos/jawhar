<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Productamount extends Model
{
    use SoftDeletes;
    protected $table = 'productamounts';
    protected $hidden = ['updated_at'];


    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }


}
