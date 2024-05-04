<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Order extends Model
{
    use SoftDeletes ;
    protected $table = 'orders';
    protected $hidden = ['updated_at', 'deleted_at'];

    protected $fillable = [
        'user_id','status','mobile','address','total','payment_method',
    ];
    protected $appends = ['status_name'];
    
    //relatioship between user and order one to many
    public function user()
    {
        return $this->belongsTo(User::class,'user_id')->withTrashed();
    }
    public function Store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    //relatioship between order and product many to many
    public function products()
    {
        return $this->belongsToMany(Product::class,'order_products','order_id','product_id')->withPivot('quantity','price','color_id','size_id')->withTrashed();
    }
    public function city(){
        return $this->belongsTo(City::class,'delivery_city_id','id')->withTrashed();
    }
    
        public function order_products()
    {
        return $this->hasMany(OrderProduct::class, 'order_id');
    }
    
    public function getStatusNameAttribute()
    {
        $status ="";
        if($this->status == -1)
        {
            $status = __('cp.new');
        }
        else if($this->status == 0)
        {
            $status = __('cp.preparing');
        }
        else if($this->status == 1)
        {
            $status = __('cp.onDelivery');
        }
        else if($this->status == 2)
        {
            $status = __('cp.complete');
        }
        else if($this->status == 3)
        {
            $status = __('cp.cancel');
        }
        else if($this->status == 4)
        {
            $status = __('cp.refund');
        }
        return $status;
    }











}