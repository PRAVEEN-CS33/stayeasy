<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSharingRentRequest;
use App\Http\Requests\UpdateSharingRentRequest;
use App\Http\Resources\SharingRentResource;
use App\Models\SharingRent;

class SharingRentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owner');
    }

    public function index()
    {
        $sharingRents = SharingRent::getAllRents();
        return SharingRentResource::collection($sharingRents);
    }

    public function store(StoreSharingRentRequest $request)
    {
        $validated = $request->validated();
        $sharingRent = SharingRent::createRent($validated);
        return new SharingRentResource($sharingRent);
    }

    public function update(UpdateSharingRentRequest $request, SharingRent $sharingRent)
    {
        $validated = $request->validated();
        $updatedRent = SharingRent::updateRent($sharingRent, $validated);
        return new SharingRentResource($updatedRent);
    }

    public function destroy(SharingRent $sharingRent)
    {
        SharingRent::deleteRent($sharingRent);
        return response()->json(['message' => 'Sharing rent deleted successfully']);
    }
}