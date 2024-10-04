<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;


Route::apiResource('appointments', AppointmentController::class);
Route::apiResource('branches', BranchController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('combos', ComboController::class);
Route::apiResource('packages', PackageController::class);
Route::apiResource('payments', PaymentController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('users', UserController::class);




Route::post('login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group( function () {
    Route::post('logout', [UserController::class, 'logout']);

    Route::group(['prefix' => 'product'], function () {
        Route::group(['prefix' => 'avail'], function () {
            Route::post('combo', [ProductsController::class, 'avail_combo']);
            Route::post('package', [ProductsController::class, 'avail_package']);
            Route::post('service', [ProductsController::class, 'avail_service']);
        });
    });
    Route::group(['prefix' => 'staffs'], function () {
        Route::post('search', [UserController::class, 'search_staff']);
        Route::get('roles', function () {
            return \App\Models\Role::get();
        });
    });
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
