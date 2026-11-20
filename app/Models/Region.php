<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = ['name', 'slug', 'location'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
