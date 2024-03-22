<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|Sme
*/
Route::get('check', function() {

});
Route::get('test', [\App\Http\Controllers\Provider\VibraController::class, 'start']);
Route::get('clear', function() {
    Artisan::command('clear', function () {
        Artisan::call('optimize:clear');
        echo 'Tudo apagado com sucesso';
    });

    dd("LIMPOU");
});

// GAMES PROVIDER
include_once(__DIR__ . '/groups/provider/fivers.php');
include_once(__DIR__ . '/groups/provider/worldslot.php');
include_once(__DIR__ . '/groups/provider/vibra.php');
include_once(__DIR__ . '/groups/provider/kagaming.php');
include_once(__DIR__ . '/groups/provider/salsa.php');

/// SOCIAL
include_once(__DIR__ . '/groups/auth/social.php');

// GATEWAYS
include_once(__DIR__ . '/groups/gateways/bspay.php');
include_once(__DIR__ . '/groups/gateways/stripe.php');
include_once(__DIR__ . '/groups/gateways/suitpay.php');

// APP
include_once(__DIR__ . '/groups/layouts/app.php');

