<?php

namespace App\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NetworkRequest extends Model
{
    use SoftDeletes ;
    protected $table = 'network_request';
    protected $fillable = ['name', 'address' , 'mobile'];
    protected $hidden = ['updated_at'];

   
      }
  
        