<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    protected $table = 'payments';
    protected $fillable = ['user_id' , 'amount' ,'payment_method', 'email', 'full_name', 'mobile','country','city', 'status'];
    protected $hidden = ['updated_at', 'deleted_at'];
    
            public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
