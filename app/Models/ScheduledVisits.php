<?php

namespace App\Models;

use App\Jobs\SendNotificationJob;
use App\Mail\ScheduledVisitNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public static function getVisitByUser(){
        $visits = self::where('id', auth('user')->id());
        if(!$visits) return false;

        return $visits; 
    }

    public static function getVisitsByOwner()
    {
        $visits = self::where('owner_id', auth('owner')->id());
        if(!$visits) return false;

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
        if(!$visits) return false;
        $visits->update($data);

        return true;
    }
    public static function updateVisitsByUser($id, $data)
    {
        $visits = self::where("user_id", auth('user')->id())
                    ->where('id', $id)
                    ->first();
        if(!$visits) return false;
        $visits->update($data);
        return true;
    }

    public static function deleteVisits($id)
    {
        $visits = self::where('user_id', auth('user')->id())
                        ->where('id', $id)
                        ->first();
        if(!$visits) return false;
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
