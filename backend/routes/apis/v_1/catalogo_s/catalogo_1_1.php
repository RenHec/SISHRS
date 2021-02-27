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

//rutas para DepartamentoController
Route::resource('departamento', 'Departamento\DepartamentoController')->only('index');

//rutas para MunicipioController
Route::resource('municipio', 'Municipio\MunicipioController')->only('index');

//rutas para StatusController
Route::resource('status', 'Status\StatusController')->only('index');

//rutas para TypeBedController
Route::resource('typebed', 'TypeBed\TypeBedController')->except('create', 'show', 'edit');

//rutas para TypeRoomController
Route::resource('typeroom', 'TypeRoom\TypeRoomController')->except('create', 'show', 'edit');

//rutas para MovementController
Route::resource('movement', 'Movement\MovementController')->only('index');

//rutas para CoinController
Route::resource('coin', 'Coin\CoinController')->except('create', 'show', 'edit');
