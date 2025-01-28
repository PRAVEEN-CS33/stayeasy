<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduledVisitsRequest;
use App\Http\Requests\UpdateScheduledVisitsRequest;
use App\Http\Resources\ScheduledVisitsResource;
use App\Jobs\SendNotificationJob;
use App\Mail\ScheduledVisitNotification;
use App\Models\Owners;
use App\Models\ScheduledVisits;
use Illuminate\Http\Request;
use Log;

class ScheduledVisitsController extends Controller
{

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $Visits = ScheduledVisits::getVisitByUser();

        $scheduledVisits = ScheduledVisitsResource::collection($Visits);

        return response()->json([
            "visits"=> $scheduledVisits
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScheduledVisitsRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth('user')->id();

        ScheduledVisits::createVisit($validated);

        return response()->json([
            'message' => "Saved successfully"
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScheduledVisitsRequest $request,  $id)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth('user')->id();

        $visit = ScheduledVisits::updateVisitsByUser($id, $validated);
        if (! $visit) {
            return response()->json([
                "message" => "visit not foud"
            ], 404);
        }
        return response()->json([
            'message' => 'Updated successfully'
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $visit = ScheduledVisits::deleteVisits($id);
        if (!$visit) {
            return response()->json([
                "message" => "visit not foud"
            ], 404);
        }
        return response()->json([
            'message' => 'deleted successfully'
        ], 200);
    }
}
