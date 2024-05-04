<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;


class InstituteCourses extends Model
{
    use SoftDeletes;
    protected $table = 'institute_courses';
    
        //relatioship  one to many
    public function institute()
    {
        return $this->belongsTo(Institute::class,'institute_id')->withTrashed();
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
