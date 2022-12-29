<?php

use App\Http\Controllers\BoletoController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\CsvDataController;
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
Route::group(['prefix' => 'V1'], function () {

    //API ROOT
    Route::get('/', function(){
        return "KNST HIRING CHALLENGE";
    });

    Route::controller(ChargeController::class)->group(function () {
        Route::get('/charges', 'list');
        Route::post('/charges', 'store');
        Route::post('/charges/send', 'sendChargeToCustomer');
    });

    Route::controller(CsvDataController::class)->group(function () {
        Route::post('/csvdata','store');
        Route::post('/charges/csv_data','createChargeFromCSVDatabase');
    });

    Route::controller(BoletoController::class)->group(function () {
        Route::post('/boletos','generate');
    });
});