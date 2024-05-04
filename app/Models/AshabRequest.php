<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AshabRequest extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class,'user_id')->withTrashed();
    }
}
