<?php

namespace App\Http\Controllers\Stats;

use App\Http\Controllers\Controller;
use App\Http\Helpers\HttpResponse;
use App\Http\Resources\Stats\ProducerStatsResource;
use App\Http\Services\Stats\GetProducerStatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProducerStatsController extends Controller
{
    public function __construct(private readonly GetProducerStatsService $service)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        return HttpResponse::ok(
            (new ProducerStatsResource($this->service->run($request->user())))->resolve($request)
        );
    }
}
