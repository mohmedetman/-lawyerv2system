<?php

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
Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('office',\Modules\Office\Http\Controllers\OfficeLawController::class)->except(['update']);
    Route::post('office/update/{id}', [\Modules\Office\Http\Controllers\OfficeLawController::class, 'update']);

    Route::post('office/getLawyerOffice', [\Modules\Office\Http\Controllers\OfficeLawController::class, 'getLawyerOffice']);



    Route::apiResource('caseHistory',\Modules\Office\Http\Controllers\CaseHistoryController::class)->except(['update']);
    Route::post('caseHistory/update/{id}', [\Modules\Office\Http\Controllers\CaseHistoryController::class, 'update']);


});
