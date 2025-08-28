<?php

namespace App\Http\Helpers;

use Error;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class HttpResponse
{
    public static function ok($data, $message = 'success'): JsonResponse
    {
        return response()->json(compact('data', 'message'));
    }

    public static function download($data): BinaryFileResponse
    {
        return response()->download($data);
    }

    public static function created($data, $message = 'created'): JsonResponse
    {
        return response()->json(compact('data', 'message'), 201);
    }

    public static function noContent(): Response
    {
        return response()->noContent();
    }

    public static function forbidden(): JsonResponse
    {
        return response()->json(['error' => 'Access denied'], 403);
    }

    public static function badRequest(Error|Exception|Throwable $error): JsonResponse
    {
        $code = $error?->getCode() === 0 ? 400 : $error->getCode();

        if (is_string($code)) {
            $code = 400;
        }

        return response()->json(['error' => self::mountError($error) ?? 'Bad Request'], $code);
    }

    public static function error($data, $message, $code = 400): JsonResponse
    {
        $success = false;
        return response()->json(compact('data', 'message', 'success'), $code);
    }

    public static function unauthorized($error = null): JsonResponse
    {
        return response()->json(['error' => self::mountError($error) ?? 'Unauthenticated'], 401);
    }

    public static function serverError($error = null): JsonResponse
    {
        return response()->json(['error' => self::mountError($error) ?? 'Server Error'], 500);
    }

    private static function mountError($error = null): array|null|string
    {
        if (gettype($error) === 'string') {
            return $error;
        }
        if (!$error?->getMessage()) {
            return null;
        }
        return [
            'message' => $error->getMessage(),
            'file' => $error->getFile(),
            'line' => $error->getLine(),
        ];
    }
}
