<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOwnersRequest;
use App\Http\Requests\UpdateBookingsRequest;
use App\Http\Requests\UpdateOwnersRequest;
use App\Http\Requests\UpdateScheduledVisitsRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\ScheduledVisitsResource;
use App\Models\Bookings;
use App\Models\Owners;
use App\Models\ScheduledVisits;
use Illuminate\Http\Request;

class OwnersController extends Controller
{
    public function viewScheduledVisit()
    {
        $scheduledVisit = ScheduledVisits::getVisitsByOwner();

        if (!$scheduledVisit) {
            return response()->json([
                'message' => 'visit not found'
            ]);
        }

        $scheduledVisit = ScheduledVisitsResource::collection($scheduledVisit);

        return response()->json($scheduledVisit);
    }
    public function updateScheduledVisit(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,accepted,canceled'
        ]);
        $scheduledVisit = ScheduledVisits::updateVisitsByOwner($id,$validated);
        if (!$scheduledVisit) return response()->json(['message' => 'visit not found']);
        return response()->json(['message' => 'updated successfully',]);
    }
    public function viewBooking()
    {
        $booking = Bookings::getBookingsByOwner();
        if (!$booking) {
            return response()->json([
                'message' => 'booking not found'
            ]);
        }
        $booking = BookingResource::collection($booking);
        return response()->json($booking);
    }
    public function updateBooking(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,confirmed,canceled',
        ]);
        $booking = Bookings::updateBookingsByOwner($validated, $id);
        if (!$booking) {
            return response()->json(['message' => 'booking not found']);
        }
        $booking = BookingResource::make($booking);
        return response()->json([
            'message' => 'updated successfully',
        ]);
    }
}
