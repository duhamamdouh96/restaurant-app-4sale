<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Customer\CheckoutController;
use App\Http\Controllers\Api\Customer\MealController;
use App\Http\Controllers\Api\Customer\OrderController;
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

    // Check availability
    Route::post('check_availability', [ReservationController::class, 'checkAvailability'])->name('customers.checkAvailability');

    // List menu items
    Route::get('list_menu_items', [MealController::class, 'index'])->name('customers.listMenuItems');

    Route::middleware(['auth:sanctum', 'customer:customer'])->group(function () {
        // Logout
        Route::post('logout', [AuthController::class, 'logout'])->name('customers.logout');

        // Reserve table
        Route::post('reserve_table', [ReservationController::class, 'store'])->name('customers.reserveTable');

        // Order
        Route::post('order', [OrderController::class, 'store'])->name('customers.order');

        // Checkout
        Route::post('checkout', [CheckoutController::class, 'index'])->name('customers.checkout');
    });

});

// Waiters apis
Route::prefix('waiters')->group(function () {
    // Auth
    Route::post('register', [WaiterAuthController::class, 'register'])->name('waiters.register');
    Route::post('login', [WaiterAuthController::class, 'login'])->name('waiters.login');

    Route::middleware(['auth:sanctum', 'customer:api'])->group(function () {
        // Logout
        Route::post('logout', [WaiterAuthController::class, 'logout'])->name('waiters.logout');
    });

});
