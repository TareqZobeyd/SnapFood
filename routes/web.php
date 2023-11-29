<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\FoodDiscountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SellerReportsController;
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
Route::get('categories/index', [CategoryController::class, 'index'])->middleware('role:super-admin')->name('categories.index');
Route::middleware(['role:super-admin'])->group(function () {
    Route::resource('categories', CategoryController::class)->names([
        'create' => 'categories.create',
        'index' => 'categories.index',
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

Route::get('/food/list', [FoodController::class, 'list'])->middleware('role:super-admin')->name('food.list');
Route::resource('food', FoodController::class)->middleware('role:super-admin|seller')->names([
    'index' => 'food.index',
    'create' => 'food.create',
    'store' => 'food.store',
    'show' => 'food.show',
    'edit' => 'food.edit',
    'update' => 'food.update',
    'destroy' => 'food.destroy',
]);
Route::get('restaurants/create', [RestaurantController::class, 'create'])->middleware('auth')->name('restaurants.create');
Route::post('restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');

Route::middleware(['role:super-admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/users', [AdminController::class, 'showUsers'])->name('admin.users');
    Route::get('admin/restaurants', [RestaurantController::class, 'index'])->name('admin.restaurants.index');
    Route::get('/admin/comments', [AdminController::class, 'showComments'])->name('admin.comments');
    Route::delete('/admin/comments/{id}/soft-delete', [AdminController::class, 'softDeleteComment'])
        ->name('admin.comments.softDelete');
    Route::resource('admin/banners', BannerController::class)->except(['show']);
});

Route::middleware(['role:seller|super-admin'])->group(function () {
    Route::get('/seller/dashboard', [SellerController::class, 'index'])->name('seller.dashboard.index');
    Route::get('/seller/restaurant', [SellerController::class, 'showRestaurant'])->name('seller.restaurant');
    Route::get('admin/restaurants/{restaurant}/edit', [RestaurantController::class, 'edit'])->name('admin.restaurants.edit');
    Route::put('admin/restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('admin.restaurants.update');
    Route::delete('admin/restaurants/{restaurant}', [RestaurantController::class, 'destroy'])->name('admin.restaurants.destroy');
    Route::get('/seller/orders', [SellerController::class, 'getOrders'])->name('seller.orders');
    Route::patch('/orders/{id}/update-seller-status', [OrderController::class, 'updateSellerStatus'])->name('orders.update-seller-status');
    Route::get('/seller/archive', [OrderController::class, 'archive'])->name('seller.archive');
    Route::get('/seller/comments', [SellerController::class, 'showComments'])->name('seller.comments');
    Route::post('/comments/respond/{comment}', [SellerController::class, 'respond'])->name('comments.respond');
    Route::get('seller/reports', [SellerReportsController::class, 'index'])->name('seller.reports.index');
    Route::post('seller/reports/filter', [SellerReportsController::class, 'filter'])->name('seller.reports.filter');
    Route::get('/seller/comments/filter', [SellerController::class, 'showComments'])->name('seller.comments.filter');

});

Route::middleware(['role:super-admin'])->group(function () {
    Route::resource('food_discounts', FoodDiscountController::class)->names([
        'index' => 'food_discounts.index',
        'create' => 'food_discounts.create',
        'store' => 'food_discounts.store',
        'show' => 'food_discounts.show',
        'edit' => 'food_discounts.edit',
        'update' => 'food_discounts.update',
        'destroy' => 'food_discounts.destroy',
    ]);
});




