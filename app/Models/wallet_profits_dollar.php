<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class wallet_profits_dollar extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function worker_name()
    {
        if(!is_null($this->worker_id) && !empty($this->worker_id))
        {
            $qq = User::where("id",$this->worker_id)->first();
            return $qq->name;
        }

        return "";
    }

    public function city_name()
    {
        if(!is_null($this->city_id) && !empty($this->city_id))
        {
            $qq = City::where("id",$this->city_id)->first();
            return $qq->name;
        }

        return "";
    }

    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class,'user_id');
    }
    public function admin_worker(){
        return $this->belongsTo(Admin::class,'worker_id');
    }
}
