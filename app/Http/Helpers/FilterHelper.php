<?php

namespace App\Http\Helpers;

class FilterHelper
{
    public static function checkOperator($operator = null): string
    {
        return match ($operator) {
            'gt' => '>',
            'gteq' => ">=",
            'like' => "like",
            'nlike' => "not like",
            'ilike' => "ilike",
            'nilike' => "not ilike",
            'lt' => "<",
            'lteq' => "<=",
            'eq' => "=",
            'neq' => "!=",
            default => '='
        };
    }

    public static function checkCondition($condition = null): string
    {
        return match ($condition) {
            'whereIn' => 'whereIn',
            'orWhere' => 'orWhere',
            default => 'where',
        };
    }

    public static function checkConditionForBuilder($condition = null): string
    {
        return match (trim($condition)) {
            'orWhere' => 'orWhere',
            default => 'where',
        };
    }
}
