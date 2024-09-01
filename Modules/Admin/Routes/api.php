<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\Api\AdminController;

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

Route::middleware(['adminToken'])->group(function () {
    Route::post('/addDepartment',[AdminController::class,'addDepartment']);
    Route::get('/showAllDepartment',[AdminController::class,'showAllDepartment']);
    Route::post('/add-lawyer',[AdminController::class,'addLawyer']);
    Route::get('/show-all-lawyer',[AdminController::class,'showAllLawyer']);
    Route::post('/add-subscribe-lawyer/{id}',[AdminController::class,'addSubscribeLawyer']);

});
});

Route::post('login',[\Modules\Admin\Http\Controllers\api\AuthController::class,'login']);
