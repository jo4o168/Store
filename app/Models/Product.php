<?php

namespace App\Models;

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
}
