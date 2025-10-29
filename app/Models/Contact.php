<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $full_name
 */
class Contact extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'nickname',
        'email',
        'document',
        'gender',
        'type',
        'profile_picture',
        'site',
        'instagram',
        'facebook',
        'linkedin',
        'birthdate',
    ];
}
