<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingCompanyTranslation extends Model
{
    //
    use SoftDeletes;
       protected $table = 'shipping_company_translations';
    protected $fillable = ['name'];
}
