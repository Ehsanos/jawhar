<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Wifi;

class NagmaGame extends Model
{
    protected $table = 'nagma_games';
    
        public function user()
    {
        return $this->belongsTo(User::class,'user_id')->withTrashed();
    }
}
