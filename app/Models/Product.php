<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $description
 * @property string $name
 * @property float $price
 * @property Profile $producer
 * @property bool $is_active
 * @property string $image_url
 * @property int $stock_quantity
 * @property int $unit
 */
class Product extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'stock_quantity',
        'unit',
        'price',
        'image_url',
        'is_active',
        'producer_id',
    ];

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'producer_id');
    }
}
