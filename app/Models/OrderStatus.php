<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class OrderStatus extends Model
{
    //
    public $translatedAttributes = ['name'];

    use SoftDeletes,Translatable;
    protected $table = 'order_statuses';
    protected $hidden = ['updated_at', 'deleted_at','translations'];


}
