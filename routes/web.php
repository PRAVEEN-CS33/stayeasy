<?php

use App\Http\Controllers\AccommodationDetailsController;
use App\Models\Accommodation_details;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::fallback(function () {
    return response()->json(['message' => 'Resource path not found.'], 404);
});

Route::get('/accommodation', [AccommodationDetailsController::class, 'show']);

