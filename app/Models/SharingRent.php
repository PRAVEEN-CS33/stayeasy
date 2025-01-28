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
    // public $timestamps = false;
    protected $fillable = [
        'accommodation_id', 
        'sharing_type', 
        'rent_amount'
    ];
    public static function getAllRents()
    {
        return self::all();
    }

    public static function createRent(array $data)
    {
        return self::create($data);
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
    public function pgDetails(): BelongsTo
    {
        return $this->belongsTo(AccommodationDetails::class, 'accommodation_id');
    }
}
