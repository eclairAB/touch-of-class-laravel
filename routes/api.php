<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;


Route::post('login', [UserController::class, 'login']);
Route::group(['prefix' => 'reports'], function () {
    Route::get('branch/{branch_id}', [PdfController::class, 'branch_report']);
    Route::get('staff/{staff_id}', [PdfController::class, 'staff_report']);
});

Route::middleware('auth:sanctum')->group( function () {
    Route::apiResource('appointments', AppointmentController::class);
    Route::apiResource('branches', BranchController::class);
    Route::apiResource('clients', ClientController::class);
    Route::apiResource('combos', ComboController::class);
    Route::apiResource('packages', PackageController::class);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('discounts', DiscountController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('employees', EmployeeController::class);


    Route::post('logout', [UserController::class, 'logout']);
    Route::post('redeems', [AppointmentController::class, 'redeems']);

    Route::group(['prefix' => 'product'], function () {
        Route::post('upload_loyalty_cards', [AppointmentController::class, 'upload_loyalty_cards']);
        Route::get('fetch_loyalty_card/{client_id}', [AppointmentController::class, 'fetch_loyalty_card']);
        Route::post('upload_free_services', [AppointmentController::class, 'upload_free_services']);
        Route::get('fetch_free_services/{client_id}', [AppointmentController::class, 'fetch_free_service']);

        Route::group(['prefix' => 'avail'], function () {
            Route::post('combo', [ProductsController::class, 'avail_combo']);
            Route::post('package', [ProductsController::class, 'avail_package']);
            Route::post('service', [ProductsController::class, 'avail_service']);
        });

        Route::group(['prefix' => 'payment'], function () {
            Route::post('create', [PaymentController::class, 'make_payment']);
        });
    });
    Route::group(['prefix' => 'staffs'], function () {
        Route::post('search', [UserController::class, 'search_staff']);
        Route::post('/stylist/commissions', [UserController::class, 'stylist_commissions']);
        Route::get('roles', function () {
            return \App\Models\Role::get();
        });
    });
    Route::group(['prefix' => 'employees'], function () {
        Route::post('search', [EmployeeController::class, 'search_employee']);
        Route::post('deduction', [EmployeeController::class, 'emp_deduction']);
    });
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
