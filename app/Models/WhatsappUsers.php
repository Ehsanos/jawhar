<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappUsers extends Model
{
    protected $table = 'whatsapp_users';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
