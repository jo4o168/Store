<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $description
 * @property string $name
 * @property string $egg_size
 * @property string $egg_color
 * @property int $kit_quantity
 * @property float $price
 * @property float $subscription_price
 * @property float $one_time_price
 * @property bool $allow_subscription
 * @property bool $allow_one_time_purchase
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
        'egg_size',
        'egg_color',
        'kit_quantity',
        'description',
        'stock_quantity',
        'unit',
        'price',
        'subscription_price',
        'one_time_price',
        'allow_subscription',
        'allow_one_time_purchase',
        'image_url',
        'is_active',
        'producer_id',
    ];

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'producer_id');
    }

    public function subscriptionPlans(): HasMany
    {
        return $this->hasMany(SubscriptionPlan::class);
    }

    protected function casts(): array
    {
        return [
            'allow_subscription' => 'boolean',
            'allow_one_time_purchase' => 'boolean',
            'is_active' => 'boolean',
            'price' => 'decimal:2',
            'subscription_price' => 'decimal:2',
            'one_time_price' => 'decimal:2',
        ];
    }
}
