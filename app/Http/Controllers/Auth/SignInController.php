<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Services\Auth\SignInService;

class SignInController extends Controller
{
    public function __construct(private readonly SignInService $signInService)
    {

    }

    public function __invoke(SignInRequest $request): void
    {
        $this->signInService->run($request->validated());
    }
}
