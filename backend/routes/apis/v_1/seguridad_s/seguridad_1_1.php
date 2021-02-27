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

//rutas para AuthController
Route::name('me')->get('auth/me', 'Usuario\AuthController@me');
Route::name('login')->post('auth/login', 'Usuario\AuthController@login');
Route::name('logout')->get('auth/logout', 'Usuario\AuthController@logout');

//rutas para UsuarioController
Route::resource('user', 'Usuario\UsuarioController')->except('create', 'edit', 'show');
Route::name('user.password')->post('user_password', 'Usuario\UsuarioController@cambiar_password');

//rutas para UsuarioController
Route::resource('user_rol', 'Usuario\UsuarioRolController')->except('index', 'create', 'edit', 'update');

//rutas para RolController
Route::resource('rol', 'Rol\RolController')->except('create', 'edit', 'show', 'update');

//rutas para RolMenuController
Route::resource('rol_menu', 'Rol\RolMenuController')->except('index', 'create', 'edit', 'show', 'update');
Route::name('rol_menu.eliminar_masivo')->post('rol_menu/eliminar_masivo', 'Rol\RolMenuController@eliminario_masiva');

//rutas para MenuController
Route::resource('menu', 'Menu\MenuController')->only('index');
