<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/documentation', function () {
    return view('docs');
})->name('docs');

Route::get('/places', function () {
    return view('places.index');
})->name('places');

Route::get('/donate', [App\Http\Controllers\DonationController::class, 'index'])->name('donate:index');
Route::post('/donate/create', [App\Http\Controllers\DonationController::class, 'create'])->name('donate:create');
Route::get('/donate/return-url', [App\Http\Controllers\DonationController::class, 'returnUrl'])->name('donate:return-url');
Route::get('/donate/callback-url', [App\Http\Controllers\DonationController::class, 'callbackUrl'])->name('donate:callback-url');



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
