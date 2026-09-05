<?php

namespace App\Http\Controllers;

use App\Http\Helpers\HttpResponse;
use App\Models\Profile;
use App\Models\Product;
use App\Models\SubscriptionPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    public function producers(): JsonResponse
    {
        $producers = Profile::query()
            ->with('producerSetting')
            ->where('role', 1)
            ->get();

        return HttpResponse::ok($producers);
    }

    public function producer(string $id): JsonResponse
    {
        $producer = Profile::query()
            ->with('producerSetting')
            ->where('role', 1)
            ->findOrFail($id);

        return HttpResponse::ok($producer);
    }

    public function plans(Request $request): JsonResponse
    {
        $plans = SubscriptionPlan::query()
            ->with(['product:id,name,kit_quantity,egg_size,egg_color,allow_one_time_purchase,image_url,producer_id'])
            ->where('is_active', true)
            ->when($request->filled('producer_id'), fn ($q) => $q->where('producer_id', (int) $request->input('producer_id')))
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', (int) $request->input('product_id')))
            ->get();

        return HttpResponse::ok($plans);
    }

    public function products(Request $request): JsonResponse
    {
        $products = Product::query()
            ->where('is_active', true)
            ->when($request->filled('producer_id'), fn ($q) => $q->where('producer_id', (int) $request->input('producer_id')))
            ->get();

        return HttpResponse::ok($products);
    }
}
