<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjustesController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\FavoritosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/favoritos/error-404', [ErrorController::class, 'error_404'])->name('favoritos.error-404');
Route::get('/favoritos/error-500', [ErrorController::class, 'error_500'])->name('favoritos.error-500');

Route::get('/favoritos/ajustes', [AjustesController::class, 'index'])->name('ajustes.index');
Route::put('/favoritos/ajustes/{clienteIP}', [AjustesController::class, 'guardar'])->name('ajustes.guardar');

Route::controller(FavoritosController::class)
        ->group(function () {
    Route::get('/favoritos', 'index')->name('favoritos.index');
    Route::get('/favoritos/crear', 'crear')->name('favoritos.crear');
    Route::post('/favoritos/crear', 'guardar')->name('favoritos.guardar');
    Route::get('/favoritos/{id}', 'editar')->name('favoritos.editar');
    Route::put('/favoritos/{id}', 'modificar')->name('favoritos.modificar');
    Route::delete('/favoritos/{id}', 'eliminar')->name('favoritos.eliminar');
});

Route::fallback(function () {
    return redirect()->route('favoritos.error-404');
});
