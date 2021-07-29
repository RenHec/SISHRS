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
    return view('welcome');
});

Route::name('bac_payment.paymentData')->get('bac_payment/{paymentData}', 'V1\Principal\Reservation\BacPaymentController@getPaymentScreen');
Route::name('bac_payment.completeData')->get('bac_payment/{paymentData}/completado', 'V1\Principal\Reservation\BacPaymentController@getPaymentScreen');
Route::name('bac_payment.post')->post('bac_payment', 'V1\Principal\Reservation\BacPaymentController@amexPay');
