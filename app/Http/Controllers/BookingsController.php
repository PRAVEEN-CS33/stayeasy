<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingsRequest;
use App\Http\Requests\UpdateBookingsRequest;
use App\Http\Resources\BookingResource;
use App\Models\Bookings;
use App\Models\SharingRent;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Bookings::getBooking();

        $bookings = BookingResource::collection($bookings);

        return response()->json($bookings);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreBookingsRequest $request)
    {
        $validated = $request->validated();
        $validated['booking_date'] = now();
        $isAvailableSlot = SharingRent::checkAvailability($validated['sharing_rent_type_id'], $validated['no_of_slots']);

        if (! $isAvailableSlot) {
            return response()->json(['message' => 'Sharing slot not available']);
        }

        $id = Bookings::saveBooking($validated);

        SharingRent::reduceSlot($validated['sharing_rent_type_id'], $validated['no_of_slots']);

        return response()->json([
            'message' => 'booking successfull',
            "id" => $id
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingsRequest $request, $id)
    {
        $validated = $request->validated();
        if ($validated['status'] === 'canceled') {
            SharingRent::addAvailability($id, $validated);
        }
        $booking = Bookings::updateBooking($validated, $id);
        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found',
            ], 404);
        }
        return response()->json(['message' => 'Booking updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $booking = Bookings::deleteBooking($id);

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found'
            ], 404);
        }

        return response()->json([
            'message' => 'deleted successfully'
        ]);
    }
}
