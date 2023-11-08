<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\RestaurantController;
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
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('auth/register', [AuthController::class, 'register'])->name('api.auth.register');
Route::post('auth/login', [AuthController::class, 'logIn'])->name('api.auth.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::put('auth/edit', [AuthController::class, 'edit'])->name('api.auth.edit');
    Route::patch('auth/edit', [AuthController::class, 'edit'])->name('api.auth.edit');
    Route::get('/addresses', [AddressController::class, 'index'])->name('api.addresses');
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::post('/addresses/{id}', [AddressController::class, 'update']);
    Route::get('/restaurants/{id}', [RestaurantController::class, 'show']);
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('api/restaurants');
    Route::get('/restaurants/{id}/foods', [RestaurantController::class, 'food'])->name('api/restaurants/foods');
    Route::get('/carts', [OrderController::class, 'getAllCards']);
    Route::post('carts/add', [OrderController::class, 'add']);
    Route::put('carts/add', [OrderController::class, 'update']);
    Route::patch('carts/add', [OrderController::class, 'update']);
    Route::get('carts/{cartId}', [OrderController::class, 'getCard'])->whereNumber('cartId');
    Route::post('carts/{cartId}/pay', [OrderController::class, 'payCard'])->whereNumber('cartId');
});
