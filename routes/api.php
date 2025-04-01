<?php

use App\Http\Controllers\CarBrandController;
use App\Http\Controllers\CarModelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('car-brands', CarBrandController::class);
Route::apiResource('car-models', CarModelController::class);
