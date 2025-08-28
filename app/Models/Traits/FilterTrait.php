<?php

namespace App\Models\Traits;

use App\Http\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

trait FilterTrait
{
    public function parseSpecialCondition($field, $value = null, $condition = '='): array|string|null
    {
        $query = "";
        if (isset($this->specialCondition[$field])) {
            $query = preg_replace("/{value}/", $value, $this->specialCondition[$field]);
            $query = preg_replace("/{condition}/", $condition, $query);
        }
        return $query;
    }

    public function scopeFilterBy(Builder $builder, QueryFilter $filter): void
    {
        $filter->apply($builder);
    }
}
