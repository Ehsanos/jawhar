<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class Productspecification  extends Model
{
   

    use SoftDeletes ,Translatable;
    //protected $fillable = [];
     public $translatedAttributes = ['answer'];
    protected $table = 'productspecifications';
    protected $hidden = ['updated_at', 'deleted_at','translations'];
    protected $with = ['attribute'];



    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
    public function attribute()
    {
        return $this->belongsTo(Attribute::class,'attribute_id');
    }

}