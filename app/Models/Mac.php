<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mac extends Model
{
    protected $table = 'macs';

    public function person()
    {
        return $this->belongsTo(Person_mac::class, 'person_id');
    }

}
