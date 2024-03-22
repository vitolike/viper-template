<?php

use App\Http\Controllers\Api\Gateways\StripeController;
use Illuminate\Support\Facades\Route;

Route::post('webhooks/stripe', [StripeController::class, 'webhooks']);
