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