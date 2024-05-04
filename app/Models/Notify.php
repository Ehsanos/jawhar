<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notify extends Model
{
    use SoftDeletes;
    public $table = 'notify';
    protected $fillable = ['message','user_id','order_id','created_at'];
    protected $hidden = ['updated_at','deleted_at'];





}
