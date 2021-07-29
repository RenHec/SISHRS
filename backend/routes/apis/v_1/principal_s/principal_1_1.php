<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//rutas para BinnacleReservationController
Route::resource('binnacle_reservation', 'BinnacleReservation\BinnacleReservationController')->except('index', 'store', 'create', 'edit');

//rutas para ClientController
Route::resource('client', 'Client\ClientController')->except('create', 'edit');

//rutas para ClientPhoneController
Route::resource('client_phone', 'Client\ClientPhoneController')->only('show', 'update', 'destroy');

//rutas para RoomController
Route::resource('room', 'Room\RoomController')->except('create', 'show', 'edit');

//rutas para RoomMassageController
Route::resource('massage', 'Room\RoomMassageController')->only('update');

//rutas para RoomPriceController
Route::resource('price', 'Room\RoomPriceController')->only('update', 'destroy');

//rutas para PictureRoomController
Route::resource('picture_room', 'Room\PictureRoomController')->only('store', 'show', 'update', 'destroy');
Route::name('picture_room.view')->get('picture_room/view/{picture_room}', 'Room\PictureRoomController@view');
Route::name('picture_room.up')->get('picture_room/up/{picture_room}', 'Room\PictureRoomController@up');
Route::name('picture_room.down')->get('picture_room/down/{picture_room}', 'Room\PictureRoomController@down');

//rutas para OfertRoomController
Route::resource('ofert_room', 'Room\OfertRoomController')->except('create', 'show', 'edit');

//rutas para ReservationController
Route::resource('reservation', 'Reservation\ReservationController')->except('create', 'edit');
Route::name('reservation.confirmado')->get('reservation_confirmado', 'Reservation\ReservationController@confirmado');
Route::name('reservation.pendiente')->get('reservation_pendiente', 'Reservation\ReservationController@pendiente');
Route::name('reservation.promocion')->get('reservation_promocion/{room}', 'Reservation\ReservationController@promocion');
Route::name('reservation.calendario')->get('reservation_calendario/{status}', 'Reservation\ReservationController@calendario');
Route::name('reservation.precios')->get('reservation_precios/{room}', 'Reservation\ReservationController@precios');
Route::name('reservation.buscar_habitaciones')->post('reservation_buscar_habitaciones', 'Reservation\ReservationController@buscar_habitaciones');

//rutas para ReservationDetailController
Route::resource('reservation_detail', 'Reservation\ReservationDetailController')->only('show', 'update', 'destroy');

//rutas para ReservationServiceController
Route::resource('reservation_service', 'Reservation\ReservationServiceController')->only('show', 'update', 'destroy');

//rutas para IncomesController
Route::resource('income', 'Kardex\IncomesController')->only('index', 'store');

//rutas para SaleController
Route::resource('sale', 'Kardex\SaleController')->only('index');

//rutas para KardexController
Route::resource('kardex', 'Kardex\KardexController')->only('index');

//rutas para KardexController
//Route::resource('change_price', 'ChangePriceController\ChangePriceController')->only('index');

//rutas para ProductController
Route::resource('product', 'Product\ProductController')->except('create', 'show', 'edit');

//rutas para PictureProductController
Route::resource('picture_product', 'Product\PictureProductController')->only('store', 'show', 'update', 'destroy');
Route::name('picture_product.view')->get('picture_product/view/{picture_product}', 'Product\PictureProductController@view');
Route::name('picture_product.up')->get('picture_product/up/{picture_product}', 'Product\PictureProductController@up');
Route::name('picture_product.down')->get('picture_product/down/{picture_product}', 'Product\PictureProductController@down');

//rutas para ReservationProductController
Route::resource('reservation_product', 'Reservation\ReservationProductController')->only('show', 'update', 'destroy');

//rutas para ContractController
Route::get('contract/{link}', 'Reservation\ContractController@validarLink')->name('contract.validarLink');
Route::put('anticipo/contract/{contract}/reservation/{reservation}', 'Reservation\ContractController@firmaContrato')->name('contract.firmaContrato');
Route::put('metodo/pago/{contract}/reservation/{reservation}', 'Reservation\ContractController@metodoPago')->name('contract.metodoPago');
Route::get('metodo/pago/{link}', 'Reservation\ContractController@verficiarLinkBoleta')->name('metodo_pago.verficiarLinkBoleta');
Route::put('metodo/pago/adjuntar/boleta/{contract}/advance/{advance}', 'Reservation\ContractController@adjuntarBoleta')->name('metodo_pago.adjuntarBoleta');
