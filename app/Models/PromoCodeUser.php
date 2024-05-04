<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class PromoCodeUser extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'promo_code_users';

    protected $hidden = ['created_at' , 'updated_at' , 'deleted_at'];
   
   
    public function promo_code(){
        return $this->belongsTo(PromoCode::class , 'promo_code_id');
    }

   
    public function user(){
        return $this->belongsTo(PromoCode::class , 'user_id');
    }


}
