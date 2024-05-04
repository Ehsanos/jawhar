<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkSections extends Model
{
    protected $table = 'network_sections';

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
