<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Api extends Model
{
    use HasFactory , SoftDeletes;
    protected $hidden = ['updated_at', 'deleted_at'];
    protected $table = 'apis';


}
