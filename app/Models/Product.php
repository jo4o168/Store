<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $description
 * @property string $name
 * @property int $type
 * @property int $stock
 * @property float $price
 * @property bool $active
 * @property Contact $contact
 */
class Product extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'stock',
        'price',
        'active',
        'contact_id',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
