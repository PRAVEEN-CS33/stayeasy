<?php

namespace App\Policies;

use App\Models\Bookings;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class BookingsPolicy
{
    public function view(User $user): bool
    {
        return auth('user')->check() && auth('user')->user()->id === $user->id;
    }
}
