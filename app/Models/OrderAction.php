<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderAction extends Model
{
    use SoftDeletes;
    protected $table = 'order_action';
    protected $hidden = ['updated_at'];


    public function orderProduct(){
        return $this->belongsTo(User::class,'order_product_id ');
     }
}
