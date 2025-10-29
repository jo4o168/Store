<?php

namespace App\Http\Controllers;

use App\Http\Filters\Filter\DefaultFilter;
use App\Http\Helpers\HttpResponse;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Services\Contact\DeleteContactService;
use App\Http\Services\Contact\ListContactService;
use App\Http\Services\Contact\ShowContactService;
use App\Http\Services\Contact\StoreContactService;
use App\Http\Services\Contact\UpdateContactService;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    public function __construct(
        private readonly ListContactService   $listService,
        private readonly ShowContactService   $showService,
        private readonly StoreContactService  $storeService,
        private readonly UpdateContactService $updateService,
        private readonly DeleteContactService $deleteService
    )
    {

    }

    public function index(DefaultFilter $filter): JsonResponse
    {
        $result = $this->listService->run($filter);
        return HttpResponse::ok($result);
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        $this->storeService->run($request->validated());
        return HttpResponse::created([]);
    }

    public function show(Contact $contact): JsonResponse
    {
        $result = $this->showService->run($contact);
        return HttpResponse::ok($result);
    }

    public function update(UpdateContactRequest $request, Contact $contact): Response
    {
        $this->updateService->run($request->validated(), $contact);
        return HttpResponse::noContent();
    }

    public function destroy(Contact $contact): Response
    {
        $this->deleteService->run($contact);
        return HttpResponse::noContent();
    }
}
