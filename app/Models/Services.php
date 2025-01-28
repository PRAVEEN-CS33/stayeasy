<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Services extends Model
{
    use HasFactory;
    protected $table = 'services';
    protected $primaryKey = 'accommodation_id';
    // public $timestamps = false;
    protected $fillable = [
        'meals', 
        'power_backup', 
        'workout_zone', 
        'housekeeping', 
        'refrigerator', 
        'washing_machine', 
        'hot_water', 
        'water_purifier', 
        'television', 
        'biometric_entry'
    ];

    public static function getAllServices()
    {
        return self::all();
    }

    public static function createService(array $data)
    {
        return self::create($data);
    }

    public static function updateService(Services $service, array $data)
    {
        $service->update($data);
        return $service;
    }

    public static function deleteService(Services $service)
    {
        $service->delete();
    }

    public function pgDetails(): BelongsTo
    {
        return $this->belongsTo(AccommodationDetails::class, 'accommodation_id');
    }
}
