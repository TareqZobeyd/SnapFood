<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SellerController;
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
Route::prefix('/seller/register')->name('seller')->controller(SellerController::class)
    ->group(function () {
        Route::get('/', 'create')->name('.register');
        Route::post('/', 'store')->name('.store');
    });
