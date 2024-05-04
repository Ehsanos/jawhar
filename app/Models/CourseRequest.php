<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseRequest extends Model
{
    use SoftDeletes ;
    protected $table = 'course_request';
    protected $fillable = [ 'mobile'];
    protected $hidden = ['updated_at'];

       public function institute()
    {
        return $this->belongsTo(Institute::class,'institute_id')->withTrashed();
    }
       public function course()
    {
        return $this->belongsTo(InstituteCourses::class,'course_id')->withTrashed();
    }
      }
  
        