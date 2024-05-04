<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    
    use SoftDeletes;
    protected $fillable = ['image'];
    protected $casts = ['product_id' => 'string'];
    
    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }


    public function getImageAttribute($value)
    {
    	if($value  && file_exists( public_path().'/uploads/products/' . $value)){
    		 if( filter_var($value, FILTER_VALIDATE_URL) === FALSE){
                 return url('uploads/images/products/' . $value); 
            }else{
                return $value;
            }
    	}else{
            return url('uploads/images/defaultCategory.jpg');
    	}
    }


}
