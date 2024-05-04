<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BalanceCard extends Model
{
    //

    use SoftDeletes;
    protected $table = 'balance_cards';
    protected $fillable = ['serial_number' , 'password','price','is_used'];
    protected $hidden = ['updated_at', 'deleted_at'];


}
   