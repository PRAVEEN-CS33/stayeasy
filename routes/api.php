<?php

use App\Http\Controllers\Auth\OwnerAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\AccommodationDetailsController;
use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\OwnersController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\ScheduledVisitsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SharingRentController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Get accommodation
Route::get('/accommodation', [AccommodationDetailsController::class, 'show']);
Route::post('/filter-accommodation', [AccommodationDetailsController::class, 'filter'])->withoutMiddleware(['auth:sanctum', 'owner']);

// User Authentication
Route::post('/users/register', [UserAuthController::class, 'register']);
Route::post('/users/login', [UserAuthController::class, 'login']);
Route::post('/users/logout', [UserAuthController::class, 'logout'])->middleware('auth:sanctum');

// Email verification for users
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/email/verify', [UserAuthController::class, 'emailVerifyNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [UserAuthController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
    Route::post('/email/resend', [UserAuthController::class, 'resendEmailVerification'])->name('verification.resend');
});

// Owner Authentication
Route::post('/owners/register', [OwnerAuthController::class, 'register']);
Route::post('/owners/login', [OwnerAuthController::class, 'login']);
Route::post('/owners/logout', [OwnerAuthController::class, 'logout'])->middleware('auth:sanctum');

// Email verification for owners
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/email/verify', [OwnerAuthController::class, 'emailVerifyNotice'])->name('owner.verification.notice');
    Route::get('/email/verify/{id}/{hash}', [OwnerAuthController::class, 'verifyEmail'])->middleware(['signed'])->name('owner.verification.verify');
    Route::post('/email/resend', [OwnerAuthController::class, 'resendEmailVerification'])->name('owner.verification.resend');
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Accommodation CRUD
    Route::get('/owners/accommodation', [AccommodationDetailsController::class, 'view']);
    Route::post('/owners/accommodation', [AccommodationDetailsController::class, 'store']);
    Route::put('/owners/accommodation/{id}', [AccommodationDetailsController::class, 'update']);
    Route::delete('/owners/accommodation/{id}', [AccommodationDetailsController::class, 'destroy']);

    // Services & Sharing Rents
    Route::middleware(['auth:owner'])->group(function () {
        Route::apiResource('/owners/sharingrents', SharingRentController::class);
        Route::apiResource('/owners/services', ServicesController::class);
    });

    // Owner Scheduled Visits & Bookings
    Route::get('/owners/scheduledvisit', [OwnersController::class, 'viewScheduledVisit']);
    Route::put('/owners/scheduledvisit/{id}', [OwnersController::class, 'updateScheduledVisit']);
    Route::get('/owners/booking', [OwnersController::class, 'viewBooking']);
    Route::put('/owners/booking/{id}', [OwnersController::class, 'updateBooking']);
    Route::get('/owners/analytics', [AnalyticController::class, 'analytics']);

    // Schedule to Visit CRUD
    Route::middleware('can:user')->group(function () {
        Route::get('/scheduletovisit', [ScheduledVisitsController::class, 'show']);
        Route::post('/scheduletovisit', [ScheduledVisitsController::class, 'store']);
        Route::put('/scheduletovisit/{scheduledVisit}', [ScheduledVisitsController::class, 'update']);
        Route::delete('/scheduletovisit/{scheduledVisit}', [ScheduledVisitsController::class, 'destroy']);
    });

    // Bookings CRUD
    Route::middleware('can:book')->group(function () {
        Route::get('/booking', [BookingsController::class, 'index']);
        Route::post('/booking', [BookingsController::class, 'create']);
        Route::put('/booking/{booking}', [BookingsController::class, 'update']);
        Route::delete('/booking/{booking}', [BookingsController::class, 'destroy']);
    });

    // Reviews CRUD
    Route::middleware('can:review')->group(function () {
        Route::get('/review', [ReviewsController::class, 'index']);
        Route::post('/review', [ReviewsController::class, 'create']);
        Route::put('/review/{review}', [ReviewsController::class, 'update']);
        Route::delete('/review/{review}', [ReviewsController::class, 'destroy']);
    });

    // Payments CRUD
    Route::middleware('can:payment')->group(function () {
        Route::get('/payment', [PaymentsController::class, 'index']);
        Route::post('/payment', [PaymentsController::class, 'create']);
    });

    //wishlist api
    Route::middleware('can:user')->group(function () {
        Route::get('/wishlist', [WishlistController::class, 'index']);
        Route::post('/wishlist', [WishlistController::class, 'createWishList']);
        Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroyWishList']);
    });
});
