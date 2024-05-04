<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublicService extends Model
{
    use SoftDeletes ;
    protected $table = 'public_services';
    protected $hidden = ['updated_at'];
    protected $with = ['parent'];
    
    public function getImageAttribute($value)
    {  if($value){
            return url('uploads/images/public_services/' . $value);
        }
        return url('uploads/images/logo.png');
    }
    
    public function parent()
    {
        return $this->belongsTo(PublicService::class,'parent_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class,'city_id');
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
  
        