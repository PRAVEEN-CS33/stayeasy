<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatereviewsRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewsController extends Controller
{
    public function index()
    {
        $reviews = Reviews::getReview();

        $reviews = ReviewResource::collection($reviews);

        return response()->json($reviews);
    }
    public function create(Request $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;

        Reviews::createReviews($validated);

        return response()->json([
            'message' => 'reviews added successfully'
        ], 200);
    }
    public function update(UpdatereviewsRequest $request, $id)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;
        $review = Reviews::updateReviews($id, $validated);
        if (!$review) {
            return response()->json([
                'message' => 'reviews not found'
            ], 404);
        }
        return response()->json([
            'message' => 'updated successfully'
        ], 200);
    }
    public function destroy($id)
    {
        $review = Reviews::deleteReviews($id);
        if (empty($review)) {
            return response()->json(['message' => 'reviews not found'], 404);
        }
        return response()->json(['message' => 'review deleted'], 200);
    }
}
