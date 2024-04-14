<?php

use App\Http\Controllers\AjustesController;
use App\Http\Controllers\FavoritosController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::controller(FavoritosController::class)
        ->group(function () {
    Route::post('/favoritos', 'guardar');
    Route::get('/favoritos', 'buscar');
    Route::get('/favoritos/{id}', 'buscar_favorito');
    Route::get('/favoritos/cliente/{clienteIP}', 'buscar_cliente');
    Route::put('/favoritos/{id}', 'modificar');
    Route::put('/favoritos/actualizarOrden/{idCambiado1}/{idCambiado2}/{clienteIP}', 'actualizarOrden');
    Route::delete('/favoritos/{id}', 'eliminar');
});

Route::controller(AjustesController::class)
        ->group(function () {
    Route::get('/ajustes', 'index');
    Route::post('/ajustes', 'guardar');
    Route::get('/ajustes/{clienteIP}', 'buscar');
    Route::put('/ajustes/{clienteIP}', 'modificar');
});
