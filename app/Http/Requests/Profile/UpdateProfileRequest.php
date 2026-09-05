<?php

namespace App\Http\Requests\Profile;

use App\Enum\ProfileRole;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'role' => ['sometimes', 'integer', Rule::in([ProfileRole::CLIENT->value, ProfileRole::PRODUCER->value])],
            'email' => ['sometimes', 'string', 'email'],
            'phone' => ['sometimes', 'string', 'nullable'],
            'cpf' => ['sometimes', 'string', 'nullable'],
            'address' => ['sometimes', 'string', 'nullable'],
            'address_number' => ['sometimes', 'string', 'nullable', 'max:20'],
            'city' => ['sometimes', 'string', 'nullable'],
            'state' => ['sometimes', 'string', 'nullable'],
            'zip_code' => ['sometimes', 'string', 'nullable'],
            'complement' => ['sometimes', 'string', 'nullable'],
            'avatar_url' => ['sometimes', 'string', 'nullable'],
        ];
    }
}
