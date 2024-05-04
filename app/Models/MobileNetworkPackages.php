<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileNetworkPackages extends Model
{
    public function MobileCompany()
    {
        return $this->belongsTo(MobileCompany::class,'mobile_companies_id');
    }
    public function result($value)
    {
        if($this->is_dollar){
            $exchange_rate = Setting::orderByDesc('id')->first()->exchange_rate;

            return (double)number_format($exchange_rate * $value, 2, '.', '');
        }
        else{
            return(double)number_format($value, 2, '.', '');
        }

    }
    public function result1($value)
    {
        if($this->is_dollar){
            return(double)number_format($value, 2, '.', '');
        }
        else{
            $exchange_rate = Setting::orderByDesc('id')->first()->exchange_rate;

            return (double)number_format( $value / $exchange_rate, 2, '.', '');
        }

    }
}
