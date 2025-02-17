<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SharingRent extends Model
{
    use HasFactory;
    protected $table = 'sharing_rents';
    protected $primaryKey = 'id';
    protected $fillable = [
        'accommodation_id',
        'sharing_type',
        'rent_amount',
        'available_slots'
    ];
    public static function getAllRents()
    {
        return self::all();
    }

    public static function createRent(array $data)
    {
        return self::insert($data);
    }

    public static function updateRent(SharingRent $rent, array $data)
    {
        $rent->update($data);
        return $rent;
    }

    public static function deleteRent(SharingRent $rent)
    {
        $rent->delete();
    }

    public static function checkAvailability($id, $slots)
    {
        return SharingRent::where('id', $id)
            ->where('available_slots', '>=', $slots)
            ->exists();
    }
    public static function reduceSlot($id, $slots)
    {
        $row =  SharingRent::where('id', $id)->first();
        if($row){
            $row->update([
                'available_slots' => $row->available_slots - $slots
            ]);
        }
    }
    public static function addAvailability($id, $data)
    {
        $booking =  Bookings::where('id', $id)->first();
        if($booking){
            $rent = SharingRent::where('id', $booking->sharing_rent_type_id)->first();
            if($rent){
                $rent->update([
                    'available_slots' => $rent->available_slots + $booking->no_of_slots
                ]);
            }
        }
    }
    public function pgDetails(): BelongsTo
    {
        return $this->belongsTo(AccommodationDetails::class, 'accommodation_id');
    }
}
