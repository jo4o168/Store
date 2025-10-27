<?php

namespace App\Http\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SignInService
{
    public function run(array $data): array
    {
        if (!Auth::attempt($data)) {
            abort(401);
        }
        $user = User::query()->select('id', 'name')->find(Auth::id());
        $accessToken = $user->createToken('auth_token')->plainTextToken;
        return compact('accessToken', 'user');


    }
}
