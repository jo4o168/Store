<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property bool $is_active
 * @property bool $is_featured
 */
class SubscriptionPlan extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'image_url',
        'price',
        'eggs_quantity',
        'frequency',
        'is_active',
        'is_featured',
        'producer_id',
        'product_id',
    ];

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'producer_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'price' => 'decimal:2',
            'eggs_quantity' => 'integer',
            'frequency' => 'integer',
        ];
    }
}
