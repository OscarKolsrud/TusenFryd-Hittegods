<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['location_name', 'visible', 'description'];

    public function investigations() {
        return $this->hasMany(Investigation::class);
    }
}
