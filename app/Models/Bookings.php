<?php

namespace App\Models;

use App\Jobs\SendNotificationJob;
use App\Mail\NewBookingNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Decimal;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class Bookings extends Model
{
    use HasFactory;
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'accommodation_id',
        'owner_id',
        'check_in',
        'check_out',
        'amount',
        'status',
        'booking_date',
        'sharing_rent_type_id',
        'no_of_slots'
    ];

    public static function getBookingAnalytics($auth_id)
    {
        $OwnerBookingData = Bookings::where("owner_id", $auth_id);

        $data = [
            "Total Bookings Today"
            => $OwnerBookingData->clone()->where('booking_date', today())
                ->count(),

            "Total bookings this week"
            => $OwnerBookingData->clone()->whereBetween("booking_date", [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),

            "Total bookings this month"
            => $OwnerBookingData->clone()->whereBetween("booking_date", [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])->count(),

            "Total canceled bookings this month"
            => $OwnerBookingData->clone()->where("status", "canceled")
                ->whereBetween("booking_date", [
                    now()->startOfMonth(),
                    now()->endOfMonth()
                ])->count(),

            "Most booked property this month"
            => $OwnerBookingData->clone()->whereBetween("booking_date", [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])
                ->select("accommodation_id", DB::raw('count(*) as total'))
                ->groupBy("accommodation_id")
                ->orderByDesc("total")
                ->first(),

            "Least booked property this month"
            => $OwnerBookingData->clone()->whereBetween("booking_date", [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])
                ->select("accommodation_id", DB::raw('count(*) as total'))
                ->groupBy("accommodation_id")
                ->orderBy("total")
                ->first(),

            "Average stay days per booking"
            => $OwnerBookingData->clone()->selectRaw('AVG(DATEDIFF(check_out, check_in)) as avg')
                ->value('avg'),

            "Longest stay booking this month"
            => $OwnerBookingData->clone()->whereMonth('check_in', now()->month)
                ->selectRaw('id as booking_id, accommodation_id, user_id, DATEDIFF(check_out, check_in) as max')
                ->orderByDesc('max')
                ->first(),

            "Most common check-in day"
            => $OwnerBookingData->clone()->selectRaw('DAY(check_in) as day, COUNT(*) as count')
                ->groupBy(DB::raw('DAY(check_in)'))
                ->orderByDesc('count')
                ->first()
                ->day ?? null,
        ];
        return $data;
    }

    public static function getPropertyPerformance($auth_id)
    {
        $accomData = AccommodationDetails::where('owner_id', $auth_id);

        $authBooking = Bookings::whereIn('accommodation_id', $accomData->pluck('accommodation_id'));

        $total = $accomData->count();
        $occupancy = $authBooking->distinct('accommodation_id')->count();

        $highest = $authBooking->selectRaw('accommodation_id, COUNT(*) as total')
            ->groupBy('accommodation_id')
            ->orderByRaw('COUNT(*) DESC')
            ->first();

        $lowest = $authBooking->selectRaw('accommodation_id, COUNT(*) as total')
            ->groupBy('accommodation_id')
            ->orderByRaw('COUNT(*) ASC')
            ->first();

        $Average = $authBooking->selectRaw('AVG(DATEDIFF(check_in, booking_date)) as avgtime')
            ->value('avgtime');

        $fullBooked = Bookings::whereBetween('booking_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->selectRaw('accommodation_id, SUM(DATEDIFF(check_out, check_in)) as total_days')
            ->groupBy('accommodation_id')
            ->havingRaw('SUM(DATEDIFF(check_out, check_in)) >= ?', [28])
            ->pluck('accommodation_id');

        return [
            "Overall occupancy rate" => $total > 0 ? round(($occupancy / $total) * 100, 2) : 0,

            "Highest occupancy rate property" => $highest ? [
                "rate" => $total > 0 ? round(($highest->total / $total) * 100, 2) : 0,
                "Accommodation" => $highest->accommodation_id
            ] : null,

            "Lowest occupancy rate property" => $lowest ? [
                "rate" => $total > 0 ? round(($lowest->total / $total) * 100, 2) : 0,
                "Accommodation" => $lowest->accommodation_id
            ] : null,

            "Average booking lead days" => round($Average, 2),

            "Properties fully booked at least once per month" => $fullBooked
        ];
    }


    public static function getBooking()
    {
        return Bookings::with(['accommodationDetails', 'user', 'payment'])
            ->where("user_id", auth()->user()->id)
            ->orderByDesc('status')
            ->orderByDesc('updated_at')
            ->get();
    }
    public static function saveBooking($data)
    {
        $data['user_id'] = auth('user')->id();
        $data['booking_date'] = now();

        $data = self::create($data);

        $userEmail = User::find(auth()->user()->id)->eamil;

        SendNotificationJob::dispatch(
            new NewBookingNotification($data),
            $userEmail
        );

        $ownerEmail = Owners::find(auth()->user()->id)->eamil;
        SendNotificationJob::dispatch(
            new NewBookingNotification($data),
            $ownerEmail
        );

        return $data->id;
    }

    public static function updateBooking($data, $id)
    {
        $booking = self::where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->first();

        if (!$booking) return null;
        $booking->update($data);
        return $booking;
    }
    public static function deleteBooking($id)
    {
        $booking = self::where('user_id', auth()->user()->id)
            ->where("id", $id)
            ->first();
        if (!$booking) {
            return false;
        }
        $booking->delete();

        return true;
    }

    public static function getBookingsByOwner()
    {
        $bookings = self::with(['accommodationDetails', 'user', 'payment'])
            ->where("owner_id", auth('owner')->id())
            ->get();
        return $bookings;
    }

    public static function updateBookingsByOwner($data, $id)
    {
        $booking  = self::where('owner_id', auth('owner')->id())
            ->where('id', $id)
            ->first();
        if (!$booking) return false;
        $booking->update($data);
        return $booking;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function AccommodationDetails(): BelongsTo
    {
        return $this->belongsTo(AccommodationDetails::class, 'accommodation_id');
    }

    public function payment(): HasMany
    {
        return $this->hasMany(Payments::class, 'id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owners::class, 'owner_id');
    }
}
