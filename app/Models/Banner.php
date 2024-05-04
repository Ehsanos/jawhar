<?php



namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model

{
    use SoftDeletes, Translatable;

    public $translatedAttributes = ['title', 'description'];
    protected $fillable = ['image'];
    protected $hidden = [ 'deleted_at','translations'];

    public function getImageAttribute($value)
    {
        return url('uploads/images/banners/' . $value); 
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class,'subcategory_id');
    }
    public function bannerProduct()
    {
        return $this->hasMany(BannerProduct::class);
    }
}

