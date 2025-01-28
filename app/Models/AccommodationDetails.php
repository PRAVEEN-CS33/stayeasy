<?php

namespace App\Models;

use App\Jobs\SendNotificationJob;
use App\Mail\NewAccommodationNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AccommodationDetails extends Model
{
    use HasFactory;
    protected $table = "accommodation_details";

    protected $primaryKey = 'accommodation_id';

    protected $fillable = [
        'owner_id',
        'accommodation_name',
        'accommodation_types',
        'description',
        'address',
        'city',
        'state',
        'pincode',
        'image',
        'gender_types',
        'preferred_by',
    ];

    public static function getAccommodationDetails()
    {
        return self::with(['services', 'reviews', 'sharingRents'])->get();
    }
    public static function getAccommodationDetailsById($id){
        $data = self::with(['services', 'reviews', 'sharingRents'])
                    ->where('owner_id',$id)
                    ->get();
        return $data;
    }

    public static function createNewAccommodation(array $data){
        $accommodation_details =  self::create($data);

        SendNotificationJob::dispatch(
            new NewAccommodationNotification($accommodation_details), 
            $accommodation_details->email
        );
        return $accommodation_details;
    }

    public static function updateAccommodationDetails($id, array $data){
        $accommodation_details = self::where('accommodation_id', $id)
                                    ->where('owner_id', $data['owner_id'])
                                    ->first();

        if (!$accommodation_details) {
            return null; 
        }
        $accommodation_details = $accommodation_details->update($data);

        return $accommodation_details;
    }

    public static function deleteAccommodationDetails($id){

        $accommodation_details = self::where('accommodation_id', $id)
                                    ->where('owner_id', auth('owner')->id())
                                    ->first();
        if(!$accommodation_details){
            return null;
        }
        $accommodation_details->delete();
        
        return true;
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owners::class, 'owner_id');
    }

    public function services(): HasOne
    {
        return $this->hasOne(Services::class, 'accommodation_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Reviews::class, 'accommodation_id');
    }

    public function sharingRents(): HasMany
    {
        return $this->hasMany(SharingRent::class, 'accommodation_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Bookings::class, 'accommodation_id');
    }

    public function scheduledVisits(): HasMany
    {
        return $this->hasMany(ScheduledVisits::class, 'accommodation_id');
    }

}
