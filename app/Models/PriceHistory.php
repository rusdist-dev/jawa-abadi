<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceHistory extends Model
{
    public $timestamps = false;

    protected $fillable = ['product_id', 'price', 'recorded_on'];

    protected $casts = ['recorded_on' => 'date'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
