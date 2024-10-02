<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartidaController;

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

// Ruta principal, que puede ser el inicio de la aplicación
Route::get('/', function () {
    return view('welcome');
});

// Ruta para iniciar la partida
Route::post('/api/iniciar-partida', [PartidaController::class, 'iniciarPartida']);

// Ruta para hacer un movimiento
Route::post('/api/make-move', [PartidaController::class, 'hacerMovimiento']);
