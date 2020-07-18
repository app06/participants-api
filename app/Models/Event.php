<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'date' => 'datetime'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
