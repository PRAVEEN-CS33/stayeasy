<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reviews extends Model
{
    use HasFactory;
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    protected $fillable = [
        'accommodation_id',
        'user_id',
        'rating',
        'review_text',
        'review_date'
    ];

    public static function getReviewsAnalytics($auth_id)
    {
        $accomData = AccommodationDetails::where('owner_id', $auth_id)->pluck('accommodation_id')->toArray();
        $authReview = Reviews::whereIn('accommodation_id', $accomData);

        $new  = $authReview->whereBetween('review_date', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->count();

        $averageRating = $authReview->avg('rating');

        $highRating = $authReview->selectRaw("accommodation_id, COUNT(rating) as total, AVG(rating) as avg_rating")
            ->groupBy('accommodation_id')
            ->orderByDesc('total')
            ->first();

        $lowRating = $authReview->selectRaw("accommodation_id, COUNT(rating) as total, AVG(rating) as avg_rating")
            ->groupBy('accommodation_id')
            ->orderBy('total')
            ->first();

        return [
            "New reviews received this month" => $new,
            "Average rating across all properties" => $averageRating,
            "Highest-rated property" => $highRating,
            "Lowest-rated property" => $lowRating
        ];
    }

    public static function createReviews($data)
    {
        self::create($data);
        return true;
    }
    public static function getReview()
    {
        return self::where('user_id', auth()->user()->id);
    }
    public static function updateReviews($id, $data)
    {
        $review = self::where('user_id', auth('user')->id())
            ->where('id', $id)
            ->first();

        if (!$review) return false;

        $review->update($data);

        return true;
    }

    public static function deleteReviews($id)
    {
        $review = self::where('user_id', auth('user')->id())
            ->where('id', $id)
            ->first();
        if (!$review) return false;
        $review->delete();

        return true;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function AccommodationDetails(): BelongsTo
    {
        return $this->belongsTo(AccommodationDetails::class, 'accommodation_id');
    }
}
