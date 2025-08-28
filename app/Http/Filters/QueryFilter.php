<?php

namespace App\Http\Filters;

use App\Http\Helpers\FilterHelper;
use App\Http\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

abstract class QueryFilter
{
    protected Builder $builder;

    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): void
    {
        $this->builder = $builder;

        if ($this->request['criteria']) {
            foreach ($this->request['criteria'] as $filtersCriteria) {
                foreach ($filtersCriteria as $filterGroup) {
                    if (isset($filterGroup['filters'])) {
                        foreach ($filterGroup['filters'] as $filters) {
                            if (!isset($filters['value']) || !isset($filters['field'])) {
                                continue;
                            }
                            $filters['conditions'] = explode('.', $filters['condition'] ?? '');
                            $condition = array_shift($filters['conditions']) ?? $filters['condition'];
                            $builderCondition = FilterHelper::checkConditionForBuilder($condition);
                            $this->builder->{$builderCondition}(function (Builder $query) use ($filters, $condition) {
                                $query->when(isset($filters['field']), function ($query) use ($filters, $condition) {
                                    $attributes = explode('.', $filters['field']);
                                    $condition = array_shift($filters['conditions']) ?? $condition;
                                    $params = compact('query', 'filters', 'condition', 'attributes');
                                    if (count($attributes) === 1) {
                                        $query = $this->applyConditions($params);
                                    }
                                    if (count($attributes) > 1) {
                                        $this->applyRelation($params);
                                    }
                                });
                            });
                        }
                    }
                    if (isset($filterGroup['orders'])) {
                        foreach ($filterGroup['orders'] as $order) {
                            $this->builder->orderBy("{$order['field']}", "{$order['direction']}");
                        }
                    }
                }
            }
        }
    }

    private function applyRelation($params): void
    {
        $builderCondition = "whereHas";
        extract($params);
        $attributes = $attributes ?? [];
        $relationName = array_shift($attributes);
        $relations = StringHelper::camelizeDromedary($relationName);
        $modelName = StringHelper::camelize($relationName);

        $query->{$builderCondition}($relations, function ($query) use ($filters, $modelName, $condition, $attributes) {
            $params = compact('query', 'filters', 'condition', 'attributes', 'modelName');
            if (count($attributes) === 1) {
                $query = $this->applyConditions($params);
            }
            if (count($attributes) > 1) {
                $query = $this->applyRelation($params);
            }
        });
    }

    private function applyConditions($params)
    {
        extract($params);
        $attributes = $attributes ?? [];
        $conditionType = isset($filters['condition_type'])
            ? FilterHelper::checkOperator($filters['condition_type'])
            : '=';
        $valueFilter = $filters['value'];
        $table = $query->getModel()?->getTable() ?? $filters['field'];
        $relationAttribute = end($attributes);
        $filterField = "$table.$relationAttribute";

        if (!Schema::hasColumn($table, $relationAttribute)) {
            return $query;
        }

        switch ($condition) {
            case 'whereIn':
                $words = array_filter(explode(' ', $filters['value']));
                $query->whereIn("{$relationAttribute}", $words);
                break;
            case 'where':
                $condition = in_array($conditionType, ["like", "ilike"]) ? "%{$valueFilter}%" : "{$valueFilter}";
                $query->where("{$filterField}", "{$conditionType}", "{$condition}");
                break;
            case 'orWhere':
                $condition = in_array($conditionType, ["like", "ilike"]) ? "%{$valueFilter}%" : "{$valueFilter}";
                $query->orWhere("{$filterField}", "{$conditionType}", "{$condition}");
                break;
            case 'whereSp':
                if (!isset($modelName)) {
                    break;
                }
                $model = StringHelper::loadNamespaceByClass($modelName);
                if ($model) {
                    $whereCondition = app($model)
                        ->parseSpecialCondition($relationAttribute, $valueFilter, $conditionType);
                    if ($whereCondition) {
                        $query->whereRaw($whereCondition);
                    }
                }
                break;
            default:
                break;
        }
        return $query;
    }
}
