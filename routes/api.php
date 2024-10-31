<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ListingController;
use App\Http\Controllers\Api\BidController;

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});


Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/users/{userId}/bidding-history', [AuthController::class, 'biddingHistory']);

    Route::post('/listings', [ListingController::class, 'create']);
    Route::get('/listings', [ListingController::class, 'index']);

    Route::post('/listings/{listingId}/bid', [BidController::class, 'placeBid']);
    Route::get('/listings/{listingId}/bids', [BidController::class, 'getBids']);


});


