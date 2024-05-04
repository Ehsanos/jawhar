<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class PromoCode extends Model
{
    
    use HasFactory,SoftDeletes;
    
    protected $table = 'promo_codes';

    protected $hidden = ['created_at' , 'updated_at' , 'deleted_at'];
    
    protected $appends = ['type'];
    
    
    public function users(){
        return $this->hasMany(PromoCodeUser::class , 'promo_code_id');
    }
  
    public function targets(){
        return $this->hasMany(PromoCodeTarget::class , 'promo_code_id');
    }
    
    public function getTypeAttribute(){
        if($this->target_type == 1){
            return __('cp.ProductService');
        }else if($this->target_type == 2){
            return __('cp.Networks');
        }else if($this->target_type == 3){
            return __('cp.GameServies');
        }
        return '';
    }
    

    public function scopeFilter($query)
    {
        if (request()->has('status')) {
            if (request()->get('status') != null)
                $query->where('status',  request()->get('status'));
        }


        if (request()->has('name')) {
            if (request()->get('name') != null)
                $query->where(function($q)
                {$q->where('name','like', '%'. request()->get('name').'%');
                });
        }

        if (request()->has('code')) {
            if (request()->get('code') != null)
                $query->where(function($q)
                {$q->where('code','like', '%'. request()->get('code').'%');
                });
        }
    }

}
