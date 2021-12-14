<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
Route::get('/donate/bank/{donation}', [App\Http\Controllers\DonationController::class, 'bank'])->name('donate:bank');
Route::get('/donate/receipt/{message}', [App\Http\Controllers\DonationController::class, 'receipt'])->name('donate:receipt');
Route::get('/donate/runBill/{bank}/{donation}', [App\Http\Controllers\DonationController::class, 'runBill'])->name('donate:runbill');
Route::get('/donate/return-url', [App\Http\Controllers\DonationController::class, 'returnUrl'])->name('donate:return-url');
Route::get('/donate/callback-url', [App\Http\Controllers\DonationController::class, 'callbackUrl'])->name('donate:callback-url');

Route::get('/donate/billplz/create_bill/{donation}/{bankCode}', [App\Http\Controllers\DonationController::class, 'billplz_create_bill'])->name('donate:billplz:create:bill');
Route::post('/donate/billplz/create', [App\Http\Controllers\DonationController::class, 'billplz_create'])->name('donate:billplz:create');
Route::get('/donate/billplz/bank/{donation}', [App\Http\Controllers\DonationController::class, 'billplz_bank'])->name('donate:billplz:bank');
Route::post('/donate/billplz/callback-url', function() {
    \info(['from payment gateway' => $request->all()]);
})->name('donate:billplz:callback-url');
Route::get('/donate/billplz/redirect-url', function(Request $request) {

    if($request['billplz']['paid'] != true)
    {
        return "fail";
    } else {
        return "success";
    }
    return $request['billplz'];
})->name('donate:billplz:redirect-url');



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
