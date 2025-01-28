<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Owners extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'owners';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name', 
        'contact_number', 
        'email', 
        'password',
        'address'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public static function registerNewOwner(array $data)
    {
        $data['password'] = bcrypt($data['password']);

        $owner = self::create($data);

        $owner->sendEmailVerificationNotification();

        $owner->token = $owner->createToken($data['name'])->plainTextToken;

        return $owner->token;
    }

    public static function authenticate(string $email, string $password){
        $owner = self::where('email',$email)->first();
        if(!$owner || !Hash::check($password, $owner->password)){
            return null;
        }
        $token = $owner->createToken('OwnerToken')->plainTextToken;
        return $token;
    }

    public function AccommodationDetails(): hasMany
    {
        return $this->hasMany(AccommodationDetails::class, 'id');
    }

    public function scheduledVisits(): HasMany
    {
        return $this->hasMany(ScheduledVisits::class, 'id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Bookings::class,'id');
    }
}
