<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('packages', PackageController::class);
Route::apiResource('clients', ClientController::class);
Route::apiResource('combos', ComboController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('appointments', AppointmentController::class);
