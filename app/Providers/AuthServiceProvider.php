<?php

namespace App\Providers;

use App\Models\Bookings;
use App\Models\Reviews;
use App\Models\ScheduledVisits;
use App\Policies\BookingsPolicy;
use App\Policies\PaymentsPolicy;
use App\Policies\ReviewsPolicy;
use App\Policies\ScheduledVisitsPolicy;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        ScheduledVisits::class => ScheduledVisitsPolicy::class,
        Bookings::class => BookingsPolicy::class,
        Reviews::class => ReviewsPolicy::class,
        Payment::class => PaymentsPolicy::class,
    ];
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('user', [ScheduledVisitsPolicy::class, 'view']);
        Gate::define('book', [BookingsPolicy::class, 'view']);
        Gate::define('review', [ReviewsPolicy::class, 'view']);
        Gate::define('payment', [PaymentsPolicy::class, 'view']);
        // Gate::define('delete', [ScheduledVisitsPolicy::class, 'delete']);
    }
}
