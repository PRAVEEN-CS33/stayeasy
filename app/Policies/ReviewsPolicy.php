<?php

namespace App\Policies;

use App\Models\User;
use App\Models\reviews;
use Illuminate\Auth\Access\Response;

class ReviewsPolicy
{
    public function view(User $user): bool
    {
        return auth('user')->check() && auth('user')->user()->id === $user->id;
    }
}
