<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "username" => ["required", "unique:users"],
            "email" => ["required", "email", "unique:users"],
            "name" => ["required", "string"],
            "password" => [
                "required",
                'confirmed',
                "min:8",
                "max:64",
                "regex:/[a-z]/",
                "regex:/[A-Z]/",
                "regex:/[0-9]/",
                "regex:/[@$!%*#?&]/",
            ]
        ];
    }
}
