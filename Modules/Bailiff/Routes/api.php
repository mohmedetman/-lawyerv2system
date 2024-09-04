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
    Route::apiResource('bailiffs', \Modules\Bailiff\Http\Controllers\api\BailiffController::class)->except(['update']);
    Route::post('bailiffs/update/{bailiff}', [\Modules\Bailiff\Http\Controllers\api\BailiffController::class,'update']);
    Route::apiResource('documents', \Modules\Bailiff\Http\Controllers\api\DocumentController::class)->except(['update']);
    Route::post('documents/update/{document}', [\Modules\Bailiff\Http\Controllers\api\DocumentController::class,'update']);

    Route::apiResource('movements', \Modules\Bailiff\Http\Controllers\api\MovementController::class)->except(['update']);
    Route::post('movements/update/{document}', [\Modules\Bailiff\Http\Controllers\api\MovementController::class,'update']);

    Route::get('movements-case/{case_id}', [\Modules\Bailiff\Http\Controllers\api\MovementController::class,'getMovementsByCaseId']);



});
