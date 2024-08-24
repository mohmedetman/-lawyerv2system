<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\PowerAttorney\Http\Controllers\PowerAttorneyController;

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
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('power-attorney', [PowerAttorneyController::class, 'index']);
    Route::post('power-attorney/store', [PowerAttorneyController::class, 'store']);
    Route::get('powerattorney', [PowerAttorneyController::class, 'index']);
    Route::get('powerattorney', [PowerAttorneyController::class, 'index']);

});
