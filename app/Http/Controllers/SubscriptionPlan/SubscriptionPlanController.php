<?php

namespace App\Http\Controllers\SubscriptionPlan;

use App\Http\Controllers\Controller;
use App\Http\Filters\Filter\DefaultFilter;
use App\Http\Helpers\HttpResponse;
use App\Http\Requests\SubscriptionPlan\StoreSubscriptionPlanRequest;
use App\Http\Requests\SubscriptionPlan\UpdateSubscriptionPlanRequest;
use App\Http\Services\SubscriptionPlan\DeleteSubscriptionPlanService;
use App\Http\Services\SubscriptionPlan\ListSubscriptionPlanService;
use App\Http\Services\SubscriptionPlan\ShowSubscriptionPlanService;
use App\Http\Services\SubscriptionPlan\StoreSubscriptionPlanService;
use App\Http\Services\SubscriptionPlan\UpdateSubscriptionPlanService;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function __construct(
        private readonly ListSubscriptionPlanService   $listService,
        private readonly StoreSubscriptionPlanService  $storeService,
        private readonly ShowSubscriptionPlanService   $showService,
        private readonly UpdateSubscriptionPlanService $updateService,
        private readonly DeleteSubscriptionPlanService $deleteService,
    )
    {

    }

    public function index(DefaultFilter $filter, Request $request)
    {
        $result = $this->listService->run($filter, $request->user());
        return HttpResponse::ok($result);
    }

    public function store(StoreSubscriptionPlanRequest $request)
    {
        $payload = $request->validated();
        $payload['image'] = $request->file('image');

        $this->storeService->run($payload, $request->user());
        return HttpResponse::noContent();
    }

    public function show(SubscriptionPlan $subscriptionPlan)
    {
        $result = $this->showService->run($subscriptionPlan);
        return HttpResponse::ok($result);
    }

    public function update(UpdateSubscriptionPlanRequest $request, SubscriptionPlan $subscriptionPlan)
    {
        $payload = $request->validated();
        $payload['image'] = $request->file('image');

        $this->updateService->run($payload, $subscriptionPlan, $request->user());
        return HttpResponse::noContent();
    }

    public function destroy(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $this->deleteService->run($subscriptionPlan, $request->user());
        return HttpResponse::noContent();
    }
}
