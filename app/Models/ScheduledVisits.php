<?php

namespace App\Models;

use App\Jobs\SendNotificationJob;
use App\Mail\ScheduledVisitNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class ScheduledVisits extends Model
{
    use HasFactory;

    protected $table = 'scheduled_visits';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $fillable = [
        'accommodation_id',
        'owner_id',
        'user_id',
        'visit_date',
        'status'
    ];

    //Guest analytics
    public static function getGuestAnalytic($auth_id)
    {
        $authData = ScheduledVisits::where('owner_id', '=', $auth_id);

        $accommodationIds = Bookings::where('owner_id', '=', $auth_id)
            ->pluck('accommodation_id')
            ->toArray();

        $accomData = AccommodationDetails::whereIn('accommodation_id', $accommodationIds);

        $mostCommonPreference = $accomData->clone()
            ->select('preferred_by', DB::raw('count(*) as total'))
            ->groupBy('preferred_by')
            ->orderByDesc('total')
            ->first();

        $totalVisits = $authData->clone()->count();

        $confirmedVisits = $authData->whereBetween('visit_date', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])
            ->where(function ($query) {
                $query->where('status', 'accepted')
                    ->orWhere('status', 'confirmed');
            })
            ->count();

        $percentage = $totalVisits > 0 ? ($confirmedVisits / $totalVisits) * 100 : 0;
        $percentage = round($percentage, 2);

        $data = [
            'Most common booking preference' => $mostCommonPreference ? $mostCommonPreference->preferred_by : null,

            'Visits converted into bookings this month' => $confirmedVisits,

            'Percentage of scheduled visits converted to bookings' => $percentage,
        ];

        return $data;
    }

    public static function getVisitAnalytic($auth_id)
    {
        $authData = ScheduledVisits::where('owner_id', $auth_id);

        $data = [
            'Total visits scheduled today'
            => $authData->clone()
                ->whereDate('visit_date', now())
                ->count(),

            'Total visits scheduled this week'
            => $authData->clone()->whereBetween('visit_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
                ->count(),

            'Most visited property'
            => $authData->clone()
                ->selectRaw('accommodation_id, count(*) as total')
                ->groupBy('accommodation_id')
                ->orderByDesc('total')
                ->first(),
            'Least visited property'
            => $authData->clone()
                ->selectRaw('accommodation_id, count(*) as total')
                ->groupBy('accommodation_id')
                ->orderBy('total')
                ->first(),
        ];

        return $data;
    }

    public static function getVisitByUser()
    {
        $visits = self::where('id', auth('user')->id());
        if (!$visits) return false;

        return $visits;
    }

    public static function getVisitsByOwner()
    {
        $visits = self::where('owner_id', auth('owner')->id());
        if (!$visits) return false;

        return $visits;
    }

    public static function createVisit($data)
    {
        self::create($data);

        $owner = Owners::find($data['owner_id']);
        $user = Owners::find($data['user_id']);

        if ($owner) {
            SendNotificationJob::dispatch(
                new ScheduledVisitNotification($data),
                $owner->email
            );
            SendNotificationJob::dispatch(
                new ScheduledVisitNotification($data),
                $user->email
            );
        }

        return true;
    }

    public static function updateVisitsByOwner($id, $data)
    {
        $visits = self::where("owner_id", auth('owner')->id())
            ->where('id', $id)
            ->first();
        if (!$visits) return false;
        $visits->update($data);

        return true;
    }
    public static function updateVisitsByUser($id, $data)
    {
        $visits = self::where("user_id", auth('user')->id())
            ->where('id', $id)
            ->first();
        if (!$visits) return false;
        $visits->update($data);
        return true;
    }

    public static function deleteVisits($id)
    {
        $visits = self::where('user_id', auth('user')->id())
            ->where('id', $id)
            ->first();
        if (!$visits) return false;
        $visits->delete();
        return true;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owners::class, 'id');
    }

    public function AccommodationDetails(): BelongsTo
    {
        return $this->belongsTo(AccommodationDetails::class, 'accommodation_id');
    }
}
