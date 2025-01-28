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

        if(!$scheduledVisit) {
            return response()->json([
                'message'=>'visit not found'
            ]);
        }

        $scheduledVisit = ScheduledVisitsResource::collection($scheduledVisit);

        return response()->json($scheduledVisit);
    }
    public function updateScheduledVisit(UpdateScheduledVisitsRequest $request, $id)
    {
        $validated = $request->validated();
        $scheduledVisit = ScheduledVisits::updateVisitsByOwner($validated, $id);
        if(!$scheduledVisit) {
            return response()->json([
                'message'=>'visit not found'
            ]);
        }
        $visit = ScheduledVisitsResource::make($scheduledVisit);
        return response()->json([
            'message'=> 'updated successfully',
            'Updated Visit'=> $visit
        ]);
    }
    public function viewBooking()
    {
        $booking = Bookings::getBookingsByOwner();
        if(!$booking) {
            return response()->json([
                'message'=> 'booking not found'
            ]);
        }
        $booking = BookingResource::collection($booking);
        return response()->json($booking);
    }
    public function updateBooking(UpdateBookingsRequest $request, $id)
    {
        $validated = $request->validated();
        $booking = Bookings::updateBookingsByOwner($validated, $id);
        if(!$booking) {
            return response()->json(['message'=> 'booking not found']);
        }
        $booking = BookingResource::make($booking);
        return response()->json([
            'message'=> 'updated successfully',
            'Updated'=> $booking
        ]);
    } 
}
