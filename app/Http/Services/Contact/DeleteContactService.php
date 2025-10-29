<?php

namespace App\Http\Services\Contact;

use App\Models\Contact;

class DeleteContactService
{
    public function run(Contact $contact): void
    {
        $contact->delete();
    }
}
