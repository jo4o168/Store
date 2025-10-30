<?php

namespace App\Http\Requests\Contact;

use App\Http\Requests\BaseRequest;

class StoreContactRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['sometimes', 'string', 'nullable'],
            'full_name' => ['sometimes', 'string', 'nullable'],
            'nickname' => ['sometimes', 'string', 'nullable'],
            'email' => ['sometimes', 'string', 'nullable'],
            'document' => ['sometimes', 'string', 'nullable'],
            'gender' => ['sometimes', 'integer', 'nullable'],
            'type' => ['required', 'integer'],
            'profile_picture' => ['sometimes', 'string', 'nullable'],
            'site' => ['sometimes', 'string', 'nullable'],
            'instagram' => ['sometimes', 'string', 'nullable'],
            'facebook' => ['sometimes', 'string', 'nullable'],
            'linkedin' => ['sometimes', 'string', 'nullable'],
            'birthdate' => ['sometimes', 'string', 'nullable'],
        ];
    }
}
