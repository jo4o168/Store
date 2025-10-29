<?php

namespace App\Http\Services\Contact;

use App\Models\Contact;

class UpdateContactService
{
    public function run(array $data, Contact $contact): void
    {
        $contact->fill($data);
        $contact->save();
    }
}
