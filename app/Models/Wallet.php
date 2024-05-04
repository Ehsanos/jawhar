<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Wallet extends Model
{
    //

    use SoftDeletes;
    protected $table = 'wallets';
    protected $fillable = ['user_id','amount','type','target_type','target_id'];
    protected $hidden = ['updated_at', 'deleted_at'];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
