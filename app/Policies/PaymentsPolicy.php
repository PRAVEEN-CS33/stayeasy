<?php

namespace App\Policies;

use App\Models\Payments;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentsPolicy
{
    public function view(User $user): bool
    {
        return auth('user')->check() && auth('user')->user()->id === $user->id;
    }
}
