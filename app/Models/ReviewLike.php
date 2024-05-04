<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Translatable;

class ReviewLike  extends Model
{
   

    use SoftDeletes;
    //protected $fillable = [];
    protected $table = 'review_likes';
    protected $hidden = ['updated_at', 'deleted_at'];


    public function productReview()
    {
        return $this->belongsTo(ProductReview::class,'review_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}