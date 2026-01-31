<?php

namespace App\Models;

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
        'avatar_url',
        'user_id',
    ];
}
