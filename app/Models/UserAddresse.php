<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddresse extends Model
{
    //


    use SoftDeletes;
    protected $table = 'user_addresses';
    protected $hidden = ['updated_at', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }

}