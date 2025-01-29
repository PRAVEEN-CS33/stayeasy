<?php

namespace App\Http\Controllers;

use App\Models\Bookings;
use App\Models\Payments;
use App\Models\Reviews;
use App\Models\ScheduledVisits;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    public function analytics()
    {

        $auth_id = auth()->user()->id;

        $bookings = Bookings::getBookingAnalytics($auth_id);

        $revenue = Payments::getPaytmentAnalytics($auth_id);

        $guestEngagement = ScheduledVisits::getGuestAnalytic($auth_id);

        $visits = ScheduledVisits::getVisitAnalytic($auth_id);

        $propertyPerformance = Bookings::getPropertyPerformance($auth_id);

        $reviews = Reviews::getReviewsAnalytics($auth_id);

        return response()->json([
            "Bookings" => $bookings,
            "Revenue" => $revenue,
            "GuestEngagement" => $guestEngagement,
            "Visits" => $visits,
            "PropertyPerformance" => $propertyPerformance,
            "Reviews" => $reviews
        ]);
    }
}
