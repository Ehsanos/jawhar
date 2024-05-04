<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Azkar extends Model
{
    public $translatedAttributes = ['name','details'];
    use SoftDeletes ,Translatable;
    protected $table = 'azkar';
    protected $fillable = ['image'];
    protected $hidden = ['updated_at'];

    public function getImageAttribute($value)
    {  if($value){
        return url('uploads/images/azkar/'. $value);
    }
    return url('uploads/images/logo.png');
    }
    
        
        public function attachments(){
        return $this->hasMany(AzkarAttachment::class,'azkar_id');
    }
        public function details(){
        return $this->hasMany(AzkarDetails::class,'azkar_id');
    }
}
  
        