<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property bool $master
 * @property UserPermission $userPermission
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'email_verified_at',
        'password',
        'roles',
        'master',
        'remember_token',
        'active',
    ];

    protected $casts = [
        'roles' => 'json',
    ];

    public function userPermissions(): HasOne
    {
        return $this->hasOne(UserPermission::class);
    }
}
