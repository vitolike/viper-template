<?php


use App\Http\Controllers\Gateway\SuitPayController;
use Illuminate\Support\Facades\Route;

Route::any('suitpay/callback', [SuitPayController::class, 'callbackMethod']);
Route::any('suitpay/payment', [SuitPayController::class, 'callbackMethodPayment']);
