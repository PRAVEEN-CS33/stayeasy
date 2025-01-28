<?php

namespace App\Models;

use App\Jobs\SendNotificationJob;
use App\Mail\NewBookingNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class Bookings extends Model
{
    use HasFactory;
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    protected $fillable = ['accommodation_id', 'user_id', 'owner_id','check_in', 'check_out', 'amount', 'status', 'booking_date'];

    public static function getBooking()
    {
        return self::where('user_id', auth()->user()->id);
    } 
    public static function saveBooking($data)
    {
        self::create($data);

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

    }

    public static function updateBooking($data, $id)
    {
        $booking = self::where('user_id', auth()->user()->id)
                        ->where('id', $id)
                        ->first();

        if (!$booking) {
            return null;
        }
     
        $booking->update($data);

        return $booking;
    }
    public static function deleteBooking($id){
        $booking = self::where('user_id', auth()->user()->id)
                        ->where("id", $id)
                        ->first();
        if (!$booking) 
        {
            return false;
        }
        $booking->delete();

        return true;
    }

    public static function getBookingsByOwner()
    {
        return self::where("owner_id", auth('owner')->id());
    }

    public static function updateBookingByUser($data, $id)
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
