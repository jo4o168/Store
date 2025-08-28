<?php

namespace App\Models;

class Product extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'sku',
        'category_id',
    ];
}
