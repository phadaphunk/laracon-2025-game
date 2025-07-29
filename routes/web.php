<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

Route::get('/', [GameController::class, 'index']);
Route::get('/maze/{level}', [GameController::class, 'generateMaze']);
Route::post('/score', [GameController::class, 'submitScore']);
Route::get('/leaderboard', [GameController::class, 'getLeaderboard']);
Route::get('/villains/count', [GameController::class, 'getAvailableVillains']);
