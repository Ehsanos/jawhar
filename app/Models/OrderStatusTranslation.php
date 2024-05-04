<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatusTranslation extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['name'];
    protected $table = 'order_status_translations';

}
