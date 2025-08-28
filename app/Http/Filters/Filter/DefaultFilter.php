<?php

namespace App\Http\Filters\Filter;

use App\Http\Filters\QueryFilter;
use Illuminate\Http\Request;

class DefaultFilter extends QueryFilter
{
    public function handle(string $field, string $value, array $filters)
    {
        // must be overridden
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function addParameter($parameter): void
    {
        $filledArray = $this->request->request->all();
        $search['criteria']['filter_group'] = array_merge(
            $filledArray['criteria']['filter_group'] ?? [], $parameter['criteria']['filter_group']
        );
        if ($filledArray) {
            $this->request->request->add($search);
        }
    }
}
