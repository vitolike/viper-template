<?php

use App\Http\Controllers\Api\Games\GameController;
use Illuminate\Support\Facades\Route;

Route::post('gold_api', [GameController::class, 'webhookFiversMethod']);
