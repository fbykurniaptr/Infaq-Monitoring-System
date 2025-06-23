<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InfaqController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/infaq', [InfaqController::class, 'summary']);
Route::post('/infaq', [InfaqController::class, 'store']);
