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

//rutas para PictureRoomController
Route::resource('picture_room', 'Room\PictureRoomController')->only('store', 'show', 'update', 'destroy');
Route::name('picture_room.view')->get('picture_room/view/{picture_room}', 'Room\PictureRoomController@view');
Route::name('picture_room.up')->get('picture_room/up/{picture_room}', 'Room\PictureRoomController@up');
Route::name('picture_room.down')->get('picture_room/down/{picture_room}', 'Room\PictureRoomController@down');

//rutas para OfertRoomController
Route::resource('ofert_room', 'Room\OfertRoomController')->except('create', 'show', 'edit');

//rutas para ReservationController
Route::resource('reservation', 'Reservation\ReservationController')->except('create', 'edit');
Route::name('reservation.pendiente')->get('reservation_pendiente', 'Reservation\ReservationController@pendiente');
Route::name('reservation.promocion')->get('reservation_promocion/{room}', 'Reservation\ReservationController@promocion');
Route::name('reservation.calendario')->get('reservation_calendario', 'Reservation\ReservationController@calendario');
Route::name('reservation.buscar_habitaciones')->post('reservation_buscar_habitaciones', 'Reservation\ReservationController@buscar_habitaciones');

//rutas para ReservationDetailController
Route::resource('reservation_detail', 'Reservation\ReservationDetailController')->only('show', 'update', 'destroy');

//rutas para ReservationServiceController
Route::resource('reservation_service', 'Reservation\ReservationServiceController')->only('show', 'update', 'destroy');
