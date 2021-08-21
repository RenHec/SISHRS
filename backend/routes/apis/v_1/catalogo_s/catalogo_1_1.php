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

//rutas para TypeChargeController
Route::resource('type_charge', 'TypeCharge\TypeChargeController')->except('create', 'show', 'edit');

//rutas para TypeMessageController
Route::resource('type_message', 'TypeMessage\TypeMessageController')->except('create', 'show', 'edit');

//rutas para TypeServiceController
Route::resource('type_service', 'TypeService\TypeServiceController')->only('index');

//rutas para CategoryController
Route::resource('category', 'Category\CategoryController')->except('create', 'show', 'edit');

//rutas para SubCategoryController
Route::resource('sub_category', 'SubCategory\SubCategoryController')->except('create', 'index', 'edit');

//rutas para KardexStatusController
Route::resource('kardex_status', 'KardexStatus\KardexStatusController')->only('index');

//rutas para SupplierController
Route::resource('supplier', 'Supplier\SupplierController')->except('create', 'show', 'edit');
