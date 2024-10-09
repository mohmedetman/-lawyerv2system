<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Case\Http\Controllers\CaseController;
use Modules\Case\Http\Controllers\CaseDegreeController;
use Modules\Case\Http\Controllers\CaseTypeController;
use Modules\Case\Http\Controllers\JudgmentController;
use Modules\Case\Http\Controllers\JudicialAgendasController;
use Modules\Case\Http\Controllers\PowerAttorneyController;
use Modules\Case\Http\Controllers\SessionController;
use Modules\Case\Http\Controllers\SessionResultController;
use Modules\Case\Http\Controllers\WorkDistributionController;

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
        Route::get('judicial-agendas', [\Modules\Case\Http\Controllers\JudicialAgendasController::class, 'index']);       // Get all agendas
        Route::post('judicial-agendas/store', [JudicialAgendasController::class, 'store']);
        Route::post('confirmCaseFile/{id}',[CaseController::class,'confirmCaseFile']);//confirmCaseFile
        Route::get('judicial-agendas/{id}', [JudicialAgendasController::class, 'show']);
        Route::put('judicial-agendas/edit/{id}', [JudicialAgendasController::class, 'update']);
        Route::delete('judicial-agendas/delete/{id}', [JudicialAgendasController::class, 'destroy']);
        Route::get('getCaseFileById/{case_id}',[CaseController::class,'getCaseFileById']);
        Route::get('getAllPendingCaseFileSide',[CaseController::class,'getAllPendingCaseFileSide']);
        Route::post('addCaseFile',[CaseController::class,'addCaseFile']);
        Route::get('getCaseFileForSpecificClient',[CaseController::class,'getCaseFileForSpecificClient']);
        Route::post('editCaseFile/{case_id}',[CaseController::class,'editCaseFile']);//confirmCaseFile
        Route::delete('deleteCaseFile/{case_id}',[CaseController::class,'deleteCaseFile']);
        Route::get('getAllCaseFile',[CaseController::class,'getAllCaseFile']);
        Route::post('rejectCaseFile/{case_id}',[CaseController::class,'rejectCaseFile']);
    Route::middleware(['lawyerToken'])->group(function () {
        Route::post('assignCaseFileToEmployee/{case_id}',[CaseController::class,'assignCaseFileToEmployee']);
        Route::post('getAllEmployeeConfirm/{case_id}',[CaseController::class,'getAllEmployeeConfirm']);//confirmCaseFile

    });

    Route::apiResource('work-distributions', WorkDistributionController::class);
    Route::apiResource('power-attorney', \Modules\Case\Http\Controllers\PowerAttorneyController::class)->except(['update']);
    Route::post('power_attorney/update/{id}', [PowerAttorneyController::class, 'update'])->name('power_attorney.update');

    Route::apiResource('session', \Modules\Case\Http\Controllers\SessionController::class)->except(['update']);
    Route::post('session/update/{id}', [SessionController::class, 'update']);
    Route::get('session-case/{id}', [SessionController::class, 'sessionCase']);


    Route::apiResource('session-result', \Modules\Case\Http\Controllers\SessionResultController::class)->except(['update']);
    Route::post('session-result/update/{id}', [SessionResultController::class, 'update']);

    Route::apiResource('judgments', \Modules\Case\Http\Controllers\JudgmentController::class)->except(['update']);
    Route::get('judgments-case/{id}', [JudgmentController::class, 'judgmentCase']);
    Route::post('judgments/update/{id}', [JudgmentController::class, 'update']);


    Route::apiResource('judgments-reports', \Modules\Case\Http\Controllers\JudicialReportController::class)->except(['update']);
    Route::post('judgments-reports/update/{id}', [\Modules\Case\Http\Controllers\JudicialReportController::class, 'update']);

});
