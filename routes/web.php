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
Route::get('/seller/register', [SellerController::class, 'create'])->name('seller.register');
Route::post('/seller/register', [SellerController::class, 'store'])->name('seller.store');
