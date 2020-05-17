<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    public function investigations() {
        return $this->belongsToMany(Investigation::class);
    }
}
