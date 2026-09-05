<?php

namespace App\Http\Controllers;

use App\Http\Helpers\HttpResponse;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderStatusRequest;
use App\Http\Resources\Orders\OrderDetailResource;
use App\Http\Resources\Orders\OrderResource;
use App\Http\Services\Order\ListOrderService;
use App\Http\Services\Order\ShowOrderService;
use App\Http\Services\Order\StoreOrderService;
use App\Http\Services\Order\UpdateOrderStatusService;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function __construct(
        private readonly ListOrderService $listService,
        private readonly StoreOrderService $storeService,
        private readonly ShowOrderService $showOrderService,
        private readonly UpdateOrderStatusService $updateStatusService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $result = $this->listService->run($request->user());
        return HttpResponse::ok(OrderResource::collection($result)->resolve($request));
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        $order = $this->showOrderService->run($order, $request->user());

        return HttpResponse::ok(new OrderDetailResource($order));
    }

    public function store(StoreOrderRequest $request): Response
    {
        $this->storeService->run($request->validated(), $request->user());
        return HttpResponse::noContent();
    }

    public function update(UpdateOrderStatusRequest $request, Order $order): Response
    {
        $this->updateStatusService->run($order, $request->validated(), $request->user());
        return HttpResponse::noContent();
    }
}
