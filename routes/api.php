<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PartidaController;
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

Route::post('/usuarios', [UsuarioController::class, 'registrar']);
Route::post('/partidas', [PartidaController::class, 'crear']);
Route::get('/partidas', [PartidaController::class, 'obtenerPartidas']);
Route::post('/iniciar-partida', [PartidaController::class, 'iniciarPartida']);
Route::post('/make-move', [PartidaController::class, 'hacerMovimiento']);
