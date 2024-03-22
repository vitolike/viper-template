<?php

use App\Http\Controllers\Api\Games\GameController;
use Illuminate\Support\Facades\Route;

Route::post('worldslot/gold_api/user_balance', [GameController::class, 'WorldslotUserBalance'])
  ->withoutMiddleware(['web', 'csrf']);

Route::post('worldslot/gold_api/game_callback', [GameController::class, 'WorldslotTransaction'])
  ->withoutMiddleware(['web', 'csrf']);

Route::post('worldslot/gold_api/game_start', [GameController::class, 'WorldslotGameStart'])
  ->withoutMiddleware(['web', 'csrf']);