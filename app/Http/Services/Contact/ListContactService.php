<?php

namespace App\Http\Services\Contact;

use App\Http\Filters\Filter\DefaultFilter;
use App\Models\Contact;

class ListContactService
{
    public function run(DefaultFilter $filter)
    {
        $model = new Contact();
        return $model->filterBy($filter)->get();
    }
}
