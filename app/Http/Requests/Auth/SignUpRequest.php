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
            "password" => ["required", "min:8", "confirmed"],
        ];
    }
}
