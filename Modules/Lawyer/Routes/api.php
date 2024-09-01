<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Lawyer\Http\Controllers\api\EmployeeController;
use Modules\Lawyer\Http\Controllers\api\EmployeeDeatialController;

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
Route::middleware('auth:sanctum')->group(function () {
    Route::middleware(['lawyerToken'])->group(function () {
        Route::post('createNewEmployee',[EmployeeController::class,'createNewEmployee']);
        Route::get('getAllEmployees',[\Modules\Lawyer\Http\Controllers\api\EmployeeController::class,'getAllEmployees']);
        Route::get('employees/{employee_id}',[\Modules\Lawyer\Http\Controllers\api\EmployeeController::class,'getEmployeesById']);
        Route::post('edit_employees/{user_id}',[\Modules\Lawyer\Http\Controllers\api\EmployeeController::class,'editEmployees']);
        Route::delete('delete_employees/{user_id}',[\Modules\Lawyer\Http\Controllers\api\EmployeeController::class,'deleteEmployees']);

        Route::post('add-employee-phone/{customer_id}', [EmployeeDeatialController::class, 'addEmployeePhone']);
        Route::post('add-employee-address/{customer_id}', [EmployeeDeatialController::class, 'addEmployeeAddress']);
        Route::put('edit-employee-phones/{phoneId}', [EmployeeDeatialController::class, 'editPhone']);
        Route::delete('/delete-employee-phones/{phoneId}', [EmployeeDeatialController::class, 'deletePhone']);
        Route::put('/edit-employee-addresses/{addressId}', [EmployeeDeatialController::class, 'editAddress']);
        Route::delete('/delete-employee-addresses/{addressId}', [EmployeeDeatialController::class, 'deleteAddress']);

    });
});

