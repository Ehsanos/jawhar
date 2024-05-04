<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnableNotificationNetwork extends Model
{

    use SoftDeletes;
    protected $table = 'enable_notification_network';
    protected $fillable = ['user_id' , 'network_id', 'store_id', 'institute_id'];
    protected $hidden = ['updated_at', 'deleted_at'];



}
   