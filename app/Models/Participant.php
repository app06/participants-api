<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = ['email', 'first_name', 'last_name', 'event_id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
