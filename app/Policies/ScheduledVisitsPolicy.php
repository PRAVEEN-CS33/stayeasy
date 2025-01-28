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
        return auth('user')->check();
    }

    public function create(User $user)
    {
        return $user->hasRole('user') && $user->hasPermissionTo('create');
    }

    public function update(User $user, ScheduledVisits $scheduledVisit)
    {
        return $user->hasRole('user') && $user->hasPermissionTo('update');
    }

    public function delete(User $user, ScheduledVisits $scheduledVisit)
    {
        return $user->hasRole('user') && $user->hasPermissionTo('delete');
    }
    
}
