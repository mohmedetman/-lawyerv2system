<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Api\CustomerController;
use Modules\Customer\Http\Controllers\api\CustomerDetailController;

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
        Route::post('add-customer-phone/{customer_id}', [CustomerDetailController::class, 'addCustomerPhone']);
        Route::post('add-customer-address/{customer_id}', [CustomerDetailController::class, 'addCustomerAddress']);
        Route::put('edit-customers-phones/{phoneId}', [CustomerDetailController::class, 'editPhone']);
        Route::delete('/delete-customers-phones/{phoneId}', [CustomerDetailController::class, 'deletePhone']);
        Route::put('/edit-customers-addresses/{addressId}', [CustomerDetailController::class, 'editAddress']);
        Route::delete('/delete-customers-addresses/{addressId}', [CustomerDetailController::class, 'deleteAddress']);
        Route::post('createNewCustomer', [CustomerController::class, 'store']);
        Route::get('getAllCustomers',[CustomerController::class,'index']);
        Route::get('getCustomersById/{user_id}',[CustomerController::class,'getCustomerById']);
        Route::post('editCustomers/{user_id}',[CustomerController::class,'update']);
        Route::delete('deleteCustomers/{user_id}',[CustomerController::class,'destroy']);
    });
});


