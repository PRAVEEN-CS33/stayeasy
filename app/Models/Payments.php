<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Payments extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $fillable = [
        'booking_id', 
        'amount', 
        'payment_date', 
        'payment_status'
    ];

    public static function getPaytmentAnalytics($auth_id)
    {
        $bookingId = Bookings::where("owner_id", $auth_id)->pluck("id")->toArray();
        $ownersData = Payments::whereIn('booking_id', $bookingId);
        
        $data = [
            "Total revenue this month"
            => $ownersData->whereBetween('payment_date', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])->sum('amount'),

            "Average rent per booking"
            => $ownersData->avg("amount") ?? 0,
        ];
        return $data;
    }

    public static function getPayments()
    {
        $user = Auth::user();
        $payments = $user->bookings->flatMap(function ($booking) {
            return $booking->payment;
        });
        
        return $payments;
    }
    public static function makePayment(array $attributes)
    {
        self::create($attributes);
        return true;
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Bookings::class, 'booking_id');
    }
}
