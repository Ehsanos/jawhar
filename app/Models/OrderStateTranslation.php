<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStateTranslation extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['name'];
    protected $table = 'order_state_translations';
}
