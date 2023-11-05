<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RestaurantController;
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
Route::prefix('/user')->name('user.')->controller(UserController::class)->group(function () {
    Route::get('/register', 'create')->name('register');
    Route::post('/register', 'store')->name('store');
    Route::get('/login', 'showLogin')->name('show-login');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/restaurants', 'index')->name('restaurants');
});

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
Route::get('restaurants/create', [RestaurantController::class, 'create'])->middleware('auth')->name('restaurants.create');
Route::post('restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');

Route::middleware(['role:super-admin'])->group(function () {
Route::get('admin/restaurants', [RestaurantController::class, 'index'])->name('admin.restaurants.index');
Route::get('admin/restaurants/{restaurant}/edit', [RestaurantController::class, 'edit'])->name('admin.restaurants.edit');
Route::put('admin/restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('admin.restaurants.update');
Route::delete('admin/restaurants/{restaurant}', [RestaurantController::class, 'destroy'])->name('admin.restaurants.destroy');
});
