<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends BaseModel
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'roles',
        'remember_token',
    ];

}
