<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class Favorite extends Model
{
    //
    
    use SoftDeletes;
    protected $table = 'favorites';
    //protected $fillable = [];
    protected $hidden = ['updated_at', 'deleted_at'];

    
    public function user(){
        return $this->belongsTo(User::class,'user_id');
     }
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }




}
