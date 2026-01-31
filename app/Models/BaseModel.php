<?php

namespace App\Models;

use App\Http\Filters\Filter\DefaultFilter;
use App\Models\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @method static find(int $id)
 * @method filterBy(DefaultFilter $filter)
 * @method static updateOrCreate(array $array, array $array)
 * @method static where(...$args)
 * @method static whereIn(string $string, [] $array)
 * @method static orderBy(string $string)
 * @method static create(array $data)
 * @method static whereId(mixed $id)
 * @method static whereHas(...$args)
 * @method static whereNull(...$args)
 * @method static whereNotNull(...$args)
 *
 */
class BaseModel extends Model
{
    use HasFactory, FilterTrait, SoftDeletes;
}
