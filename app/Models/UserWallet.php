<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserWallet extends Model
{

    use SoftDeletes;
    protected $table = 'user_wallet';

    protected $fillable = ['user_id' ,'order_id ', 'points'];
    protected $hidden = ['updated_at', 'deleted_at'];


    public function __construct($lolo = null)
    {
        if($lolo == null)
        {
            $this->table = "user_wallet".currency();
        }
        else
        {
            if($lolo == "dollar")
            {
                $this->table = "user_wallet1";
            }
            elseif($lolo == "turkey")
            {
                $this->table = "user_wallet";
            }
        }

        parent::__construct();
    }

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
