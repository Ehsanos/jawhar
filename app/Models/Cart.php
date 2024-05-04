<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Cart extends Model
{

    use SoftDeletes;
    protected $table = 'carts';
   protected $with = ['color','size'];
    protected $fillable = ['user_id' ,'fcm_token', 'product_id','size_id' ,'color_id' ,'quantity'];
    protected $hidden = ['updated_at', 'deleted_at'];


    //relatioship between user and Cart one to many

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id', 'id');
    }
    public function color()
    {
        return $this->belongsTo(Color::class,'color_id');
    }
    public function size()
    {
        return $this->belongsTo(Size::class,'size_id');
    }

}
