<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\Api\AdminController;
use Modules\Case\Http\Controllers\CaseDegreeController;
use Modules\Case\Http\Controllers\CaseTypeController;

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
    Route::get('getAllCaseType',[CaseTypeController::class,'index']);
    Route::post('editCaseType/{id}',[CaseTypeController::class,'update']);
    Route::post('addCaseType',[CaseTypeController::class,'store']);
    Route::post('deleteCaseType/{id}',[CaseTypeController::class,'destroy']);
    Route::get('getAllCaseDegree',[CaseDegreeController::class,'index']);
    Route::post('editCaseDegree/{id}',[CaseDegreeController::class,'update']);
    Route::post('addCaseDegree',[CaseDegreeController::class,'store']);
    Route::post('deleteCaseDegree/{id}',[CaseDegreeController::class,'destroy']);

});



});

Route::post('login',[\Modules\Admin\Http\Controllers\api\AuthController::class,'login']);
