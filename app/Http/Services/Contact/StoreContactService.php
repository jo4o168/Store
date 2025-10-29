<?php

namespace App\Http\Services\Contact;

use App\Models\Contact;

class StoreContactService
{
    public function run(array $data): void
    {
        Contact::create($data);
    }
}
