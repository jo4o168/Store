<?php

namespace App\Http\Controllers;

use App\Enum\Permissions;
use App\Http\Filters\Filter\DefaultFilter;
use App\Http\Helpers\HttpResponse;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Services\ProductController\ListProductService;
use App\Http\Services\ProductController\StoreProductService;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class
ProductController extends Controller
{
    public function __construct(
        private readonly ListProductService  $listService,
        private readonly StoreProductService $storeService,
    )
    {

    }

    public function index(DefaultFilter $filter): JsonResponse
    {
        $this->checkPermission(Permissions::PRODUCT_LIST->value);
        $result = $this->listService->run($filter);
        return HttpResponse::ok($result);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $this->checkPermission(Permissions::PRODUCT_STORE->value);
        $result = $this->storeService->run($request->validated());
        return HttpResponse::created($result);
    }

    public function show(Product $product)
    {
        //
    }

    public function update(Request $request, Product $product)
    {
        //
    }

    public function destroy(Product $product)
    {
        //
    }
}
