<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\UserController;
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

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::middleware(['role:super-admin'])->group(function () {
    Route::resource('categories', CategoryController::class)->names([
        'index' => 'categories.index',
        'create' => 'categories.create',
        'store' => 'categories.store',
        'show' => 'categories.show',
        'edit' => 'categories.edit',
        'update' => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);
});
Route::prefix('/seller')->name('seller.')->controller(SellerController::class)->group(function () {
    Route::get('/register', 'create')->name('register');
    Route::post('/register', 'store')->name('store');
    Route::get('/login', 'showLogin')->name('show-login');
    Route::post('/login', 'login')->name('login');
});
Route::middleware(['role:super-admin'])->group(function () {
    Route::get('/foods/list', [FoodController::class, 'list'])->name('foods.list');
    Route::resource('foods', FoodController::class)->names([
        'index' => 'foods.index',
        'create' => 'foods.create',
        'store' => 'foods.store',
        'show' => 'foods.show',
        'edit' => 'foods.edit',
        'update' => 'foods.update',
        'destroy' => 'foods.destroy',
    ]);
    Route::resource('restaurants', RestaurantController::class)->names([
        'index' => 'restaurants.index',
        'create' => 'restaurants.create',
        'store' => 'restaurants.store',
        'show' => 'restaurants.show',
        'edit' => 'restaurants.edit',
        'update' => 'restaurants.update',
        'destroy' => 'restaurants.destroy',
    ]);
});
