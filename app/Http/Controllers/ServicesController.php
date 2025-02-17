<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServicesRequest;
use App\Http\Requests\UpdateServicesRequest;
use App\Http\Resources\ServicesResource;
use App\Models\Services;

class ServicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owner');
    }

    public function index()
    {
        $services = Services::getAllServices();
        return ServicesResource::collection($services);
    }

    public function store(StoreServicesRequest $request)
    {
        $validated = $request->validated();
        $service = Services::createService($validated);
        return response()->json([
            'message' => 'Service added successfully',
            'service' => $service
        ], 201);
    }

    public function update(UpdateServicesRequest $request, Services $service)
    {
        $validated = $request->validated();
        $updatedService = Services::updateService($service, $validated);
        return response()->json(['message' => 'Service updated successfully', 'service' => $service], 201);
    }

    public function destroy(Services $service)
    {
        Services::deleteService($service);
        return response()->json(['message' => 'Service deleted successfully']);
    }
}
