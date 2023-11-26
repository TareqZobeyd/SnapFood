<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\RestaurantController;
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
    Route::get('auth/logout', [AuthController::class, 'logout']);
    Route::put('auth/edit', [AuthController::class, 'edit']);
    Route::patch('auth/edit', [AuthController::class, 'edit']);
    Route::get('addresses', [AddressController::class, 'index']);
    Route::post('addresses', [AddressController::class, 'store']);
    Route::post('addresses/{id}', [AddressController::class, 'setActiveAddress']);
    Route::get('restaurants/{id}', [RestaurantController::class, 'show']);
    Route::get('restaurants', [RestaurantController::class, 'index']);
    Route::get('restaurants/{id}/food', [RestaurantController::class, 'food']);
    Route::get('carts', [CartController::class, 'getAllCarts']);
    Route::post('carts/add', [CartController::class, 'addToCart']);
    Route::put('carts/add', [CartController::class, 'update']);
    Route::patch('carts/add', [CartController::class, 'update']);
    Route::get('carts/{cartId}', [CartController::class, 'getCart'])->whereNumber('cartId');
    Route::post('carts/{cartId}/pay', [CartController::class, 'payCart'])->whereNumber('cartId');
    Route::get('comments', [CommentController::class, 'index']);
    Route::post('comments', [CommentController::class, 'store']);
});
