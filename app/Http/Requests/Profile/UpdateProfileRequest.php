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
            'role' => ['sometimes', 'integer', Rule::enum(ProfileRole::class)],
            'email' => ['sometimes', 'string', 'email'],
            'phone' => ['sometimes', 'string', 'nullable'],
            'avatar_url' => ['sometimes', 'string', 'nullable'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
        ];
    }
}
