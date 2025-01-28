<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingsRequest;
use App\Http\Requests\UpdateBookingsRequest;
use App\Http\Resources\BookingResource;
use App\Models\Bookings;
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

        return response()->json([
            "Bookings"=> $bookings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreBookingsRequest $request)
    {
        // Validation rules for booking
        $validated = $request->validated();
        $validated['user_id'] = auth('user')->id();

        Bookings::saveBooking($validated);

        return response()->json([
            'message' => 'booking successfull'
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingsRequest $request, $id)
    {
        $validated = $request->validated();
        $booking = Bookings::updateBooking($validated, $id);

        if (!$booking) {
            return response()->json([
                'message' => 'Booking not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Booking updated successfully',
            'booking' => $booking,
        ]);
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
