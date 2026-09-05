<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $name
 */
class Profile extends BaseModel
{
    protected $fillable = [
        'name',
        'role',
        'email',
        'phone',
        'cpf',
        'address',
        'address_number',
        'city',
        'state',
        'zip_code',
        'complement',
        'avatar_url',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function producerSetting(): HasOne
    {
        return $this->hasOne(ProducerSetting::class, 'producer_id');
    }
}
