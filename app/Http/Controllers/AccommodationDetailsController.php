<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccommodation_detailsRequest;
use App\Http\Requests\UpdateAccommodation_detailsRequest;
use App\Http\Resources\AccommodationResource;
use App\Models\AccommodationDetails;
use Illuminate\Http\Request;
use PHPUnit\Metadata\Uses;

class AccommodationDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'owner'], ['except' => ['show']]);
    }
    /**
     * Display the specified resource.
     */
    public function show()
    {
        $accommodationDetails = AccommodationDetails::getAccommodationDetails();
        $accommodation = AccommodationResource::collection($accommodationDetails);

        return response()->json($accommodation);
    }

    public function filter(Request $request)
    {
        $filters = $request->only(['date', 'location', 'persons', 'reviews', 'rentRange', 'preferredBy']);
        $accommodationDetails = AccommodationDetails::getFilteredAccommodationDetails($filters);
        $accommodation = AccommodationResource::collection($accommodationDetails);
        return response()->json($accommodation);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function view()
    {
        $accommodation = AccommodationDetails::getAccommodationDetailsById(auth()->user()->id);
        if (!$accommodation) {
            return response()->json([
                'error' => 'No accommodation details found.'
            ], 404);
        }
        $accommodation = AccommodationResource::collection($accommodation);
        return response()->json($accommodation);
    }
    public function store(StoreAccommodation_detailsRequest $request)
    {
        $validated = $request->validated();
        $validated['owner_id'] = auth('owner')->id();

        $id = AccommodationDetails::createNewAccommodation($validated);

        return response()->json([
            'message' => 'details saved successfully',
            'accommodation_id' => $id
        ], 201);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccommodation_detailsRequest $request, $id)
    {
        $validated = $request->validated();
        $validated['owner_id'] = auth('owner')->id();

        $updatedAccommodation = AccommodationDetails::updateAccommodationDetails($id, $validated);

        if ($updatedAccommodation == null) {
            return response()->json([
                'error' => 'Accommodation not found'
            ], 404);
        }
        return response()->json([
            'message' => 'Accommodation updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deleted = AccommodationDetails::deleteAccommodationDetails($id);

        if ($deleted === null) {
            return response()->json([
                'error' => 'Accommodation not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Accommodation deleted successfully'
        ], 200);
    }
}
