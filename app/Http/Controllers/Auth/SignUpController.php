<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponse;
use App\Http\Requests\Auth\SignUpRequest;
use App\Http\Services\Auth\SignUpService;
use Illuminate\Http\JsonResponse;

class SignUpController extends Controller
{
    public function __construct(private readonly SignUpService $signUpService)
    {

    }


    public function __invoke(SignUpRequest $request): JsonResponse
    {
        $this->signUpService->run($request->validated());
        return HttpResponse::created([]);
    }
}
