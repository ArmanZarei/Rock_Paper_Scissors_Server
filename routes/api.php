<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
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


// -------------------------- Auth -------------------------- //
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


// -------------------------- Game -------------------------- //
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/new_game', [GameController::class, 'new']);
    Route::put('/quit-game', [GameController::class, 'quit'])->middleware('can:quit-queue');
    Route::get('/games/{game}/status', [GameController::class, 'status'])->can('view', 'game');
    Route::put('/games/{game}/choose-move', [GameController::class, 'chooseMove'])->can('choose_move', 'game');
});
