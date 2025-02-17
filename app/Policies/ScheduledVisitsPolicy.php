<?php

namespace App\Policies;

use App\Models\ScheduledVisits;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class ScheduledVisitsPolicy
{
    use HandlesAuthorization;
    public function view(User $user)
    {
        return auth()->check();
    }
}
