<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$path = "Ajedrez\\";
Route::get('/usuarios/login', $path.'ConectadosController@login');
Route::get('/usuarios/logout', $path.'ConectadosController@logout');
Route::get('/usuarios/verConectados', $path.'ConectadosController@verConectados');

Route::get('/invitacion/invitar', $path.'InvitacionesController@invitar');
Route::get('/invitacion/ver', $path.'InvitacionesController@ver');
Route::get('/invitacion/responder', $path.'InvitacionesController@responder');

Route::get('/tablero/ver', $path.'TableroController@ver');
Route::get('/tablero/mover', $path.'TableroController@moverFicha');

/*
|--------------------------------------------------------------------------
| Parametros para las rutas:
|--------------------------------------------------------------------------
|	login -> params: email, password
|	logout -> params: token
|	verConectados -> params: token
|
|	invitar -> params: token, name
|	ver -> params: token
|	responder -> params: token, name, respuesta (0,1)
|	
|	PD: el 1 es para aceptar i el 0 para rechazar.
|
|	ver -> params: token, name
|	mover -> params: token, name, toFila, toColumna, fromFila, fromColumna
|
*/