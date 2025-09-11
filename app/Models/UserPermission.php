<?php

namespace App\Models;

class UserPermission extends BaseModel
{
    protected $fillable = [
        'permissions',
        'user_id'
    ];
    public $timestamps = false;
}
