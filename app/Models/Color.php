<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['color', 'class', 'colorcode', 'description'];


    public function investigations() {
        return $this->belongsToMany(Investigation::class);
    }
}
