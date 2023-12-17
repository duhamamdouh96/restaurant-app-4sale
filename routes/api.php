<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Customer\ReservationController;
use App\Http\Controllers\Api\Waiter\Auth\AuthController as WaiterAuthController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Customers apis
Route::prefix('customers')->group(function () {
    // Auth
    Route::post('register', [AuthController::class, 'register'])->name('customers.register');
    Route::post('login', [AuthController::class, 'login'])->name('customers.login');

    Route::middleware(['auth:customer', 'customer:customer'])->group(function () {
        // Logout
        Route::post('logout', [AuthController::class, 'logout'])->name('customers.logout');

        // check availability
        Route::post('check_availability', [ReservationController::class, 'checkAvailability'])->name('customers.checlAvailability');

    });

});

// Waiters apis
Route::prefix('waiters')->group(function () {
    // Auth
    Route::post('register', [WaiterAuthController::class, 'register'])->name('waiters.register');
    Route::post('login', [WaiterAuthController::class, 'login'])->name('waiters.login');

    Route::middleware(['auth', 'customer:api'])->group(function () {
        // Logout
        Route::post('logout', [WaiterAuthController::class, 'logout'])->name('waiters.logout');
    });

});
