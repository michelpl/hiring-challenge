<?php

use App\Http\Controllers\ChargeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'V1'], function () {

    //API ROOT
    Route::get('/', function(){
        return "KNST HIRING CHALLENGE";
    });

    Route::controller(ChargeController::class)->group(function () {
        Route::get('/charges', 'index');
        Route::get('/charges/{id}', 'show');
        Route::post('/charges', 'store');
    });
});