<?php

namespace App\Http\Services\Contact;

use App\Models\Contact;

class ShowContactService
{
    public function run(Contact $contact)
    {
        return $contact->where('id', $contact->id)->first();
    }
}
