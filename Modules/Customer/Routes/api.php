<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\CustomerController;

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
Route::middleware('auth:sanctum')->group( function () {
    Route::middleware(['lawyerToken'])->group(function () {
        Route::put('edit-customers-phones/{phoneId}', [CustomerController::class, 'editPhone']);
        Route::delete('/delete-customers-phones/{phoneId}', [CustomerController::class, 'deletePhone']);
        Route::put('/edit-customers-addresses/{addressId}', [CustomerController::class, 'editAddress']);
        Route::delete('/delete-customers-addresses/{addressId}', [CustomerController::class, 'deleteAddress']);
        Route::post('createNewCustomer', [CustomerController::class, 'store']);
        Route::get('getAllCustomers',[CustomerController::class,'index']);
        Route::get('getCustomersById/{user_id}',[CustomerController::class,'getCustomerById']);
        Route::post('editCustomers/{user_id}',[CustomerController::class,'update']);
        Route::delete('deleteCustomers/{user_id}',[CustomerController::class,'destroy']);
    });
});


