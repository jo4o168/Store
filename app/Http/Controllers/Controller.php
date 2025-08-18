<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;

    private string $exception;
    protected int $exceptionCode = 400 {
        set {
            $this->exceptionCode = $value;
        }
    }

    protected function setExceptionMessage($exception): void
    {
        $this->exception = $exception;
    }

    protected function getException(): Exception
    {
        return new Exception($this?->exception, $this?->exceptionCode);
    }


    protected function checkPermission(string $permission): void
    {
        if (!Gate::check($permission)) {
            throw new AccessDeniedHttpException('access denied', null, 403);
        }
    }
}
