<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['category_name', 'visible', 'description'];

    public function investigations() {
       return $this->hasMany(Investigation::class);
    }
}
