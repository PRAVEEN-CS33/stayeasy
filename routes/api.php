<?php

use App\Http\Controllers\Auth\OwnerAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\AccommodationDetailsController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\OwnersController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\ScheduledVisitsController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\SharingRentController;
use App\Models\Bookings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//User
Route::prefix('users')->controller(UserAuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');

    //email verification for users
    Route::prefix('email')->middleware('auth:sanctum')->group(function () {
        Route::get('/verify', [UserAuthController::class, 'emailVerifyNotice'])
            ->name('verification.notice');
        Route::get('/verify/{id}/{hash}', [UserAuthController::class, 'verifyEmail'])
            ->middleware(['signed'])
            ->name('verification.verify');
        Route::post('/resend', [UserAuthController::class, 'resendEmailVerification'])
            ->name('verification.resend');
    });
});
//owners
Route::prefix('owners')->controller(OwnerAuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');

    // email verification for owners
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/email/verify', [OwnerAuthController::class, 'emailVerifyNotice'])
            ->name('owner.verification.notice');
        Route::get('/email/verify/{id}/{hash}', [OwnerAuthController::class, 'verifyEmail'])
            ->middleware(['signed'])
            ->name('owner.verification.verify');
        Route::post('/email/resend', [OwnerAuthController::class, 'resendEmailVerification'])
            ->name('owner.verification.resend');
    });
}); 
Route::middleware('auth:sanctum')->group( function () {
    //accommodation CRUD
    Route::middleware('owner')->prefix('owners')->group(function () {
        
        Route::prefix('accommodation')->group(function () {
            Route::get('/', [AccommodationDetailsController::class, 'view']);
            Route::post('/', [AccommodationDetailsController::class, 'store']);      
            Route::put('/{id}', [AccommodationDetailsController::class, 'update']);  
            Route::delete('/{id}', [AccommodationDetailsController::class, 'destroy']);
        });
        // adding service and rent details by the owner
        Route::middleware(['auth:owner'])->group(function () {
            Route::apiResource('sharingrents', SharingRentController::class);
            Route::apiResource('services', ServicesController::class);
        });

        //owener view, update
        Route::get('/scheduledvisit', [OwnersController::class,'viewScheduledVisit']);
        Route::put('/scheduledvisit/{id}',[OwnersController::class,'updateScheduledVisit']);
        
        Route::get('/booking', [OwnersController::class,'viewBooking']);
        Route::put('/booking/{id}', [OwnersController::class,'updatebooking']);
        
    });
    //schedule to visit CRUD
    Route::middleware('can:user')->prefix('scheduletovisit')->group(function () {
        Route::get('/', [ScheduledVisitsController::class, 'show']);
        Route::post('/', [ScheduledVisitsController::class, 'store']);
        Route::put('/{scheduledVisit}', [ScheduledVisitsController::class, 'update']);
        Route::delete('/{scheduledVisit}', [ScheduledVisitsController::class, 'destroy']);
    });
    // Bookings CRUD
    Route::middleware('can:book')->prefix('booking')->group(function () {
        Route::get('/', [BookingsController::class,'index']);
        Route::post('/', [BookingsController::class,'create']);
        Route::put('/{booking}', [BookingsController::class,'update']);
        Route::delete('/{booking}', [BookingsController::class,'destroy']);
    });
    //review CRUD
    Route::middleware('can:review')->prefix('review')->group(function () {
        Route::get('/', [ReviewsController::class,'index']);
        Route::post('/', [ReviewsController::class,'create']);
        Route::put('/{review}', [ReviewsController::class,'update']);
        Route::delete('/{review}', [ReviewsController::class,'destroy']);
    });
    //payment CRUD
    Route::middleware('can:payment')->prefix('payment')->group(function () {
        Route::get('/', [PaymentsController::class,'index']);
        Route::post('/', [PaymentsController::class,'create']);
    });

});

