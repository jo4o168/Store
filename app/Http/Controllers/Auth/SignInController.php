<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponse;
use App\Http\Requests\Auth\SignInRequest;
use App\Http\Services\Auth\SignInService;
use Illuminate\Http\JsonResponse;

class SignInController extends Controller
{
    public function __construct(private readonly SignInService $signInService)
    {

    }

    public function __invoke(SignInRequest $request): JsonResponse
    {
        $result = $this->signInService->run($request->validated());
        return HttpResponse::ok($result);
    }
}
