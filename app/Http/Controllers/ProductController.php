<?php

namespace App\Http\Controllers;

use App\Http\Filters\Filter\DefaultFilter;
use App\Http\Helpers\HttpResponse;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Products\ListProductResource;
use App\Http\Resources\Products\ShowProductResource;
use App\Http\Services\Product\DeleteProductService;
use App\Http\Services\Product\ListProductService;
use App\Http\Services\Product\ShowProductService;
use App\Http\Services\Product\StoreProductService;
use App\Http\Services\Product\UpdateProductService;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class
ProductController extends Controller
{
    public function __construct(
        private readonly ListProductService   $listService,
        private readonly StoreProductService  $storeService,
        private readonly ShowProductService   $showService,
        private readonly UpdateProductService $updateService,
        private readonly DeleteProductService $deleteService,
    )
    {

    }

    public function index(DefaultFilter $filter): JsonResponse
    {
        $result = $this->listService->run($filter);
        return HttpResponse::ok(ListProductResource::collection($result));
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $this->storeService->run($request->validated());
        return HttpResponse::created([]);
    }

    public function show(Product $product): JsonResponse
    {
        $result = $this->showService->run($product);
        return HttpResponse::ok(new ShowProductResource($result));
    }

    public function update(UpdateProductRequest $request, Product $product): Response
    {
        $this->updateService->run($request->validated(), $product);
        return HttpResponse::noContent();
    }

    public function destroy(Product $product): Response
    {
        $this->deleteService->run($product);
        return HttpResponse::noContent();
    }
}
