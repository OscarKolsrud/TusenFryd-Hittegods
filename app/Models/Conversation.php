<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['investigation_id', 'messagetype', 'from_guest',
        'user_id', 'message'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function investigation() {
        return $this->belongsTo(Investigation::class);
    }
}
