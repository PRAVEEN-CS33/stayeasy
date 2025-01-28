<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function newRegister(array $data)
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
    public function reviews(): HasMany
    {
        return $this->hasMany(Reviews::class, 'user_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Bookings::class, 'user_id');
    }

    public function scheduledVisits(): HasMany
    {
        return $this->hasMany(ScheduledVisits::class, 'user_id');
    }
}
