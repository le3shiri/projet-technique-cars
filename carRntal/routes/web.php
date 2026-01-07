<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/cars');
});

use App\Http\Controllers\CarController;

Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/fetch', [CarController::class, 'fetch']);
Route::post('/cars', [CarController::class, 'store']);
Route::get('/cars/{id}', [CarController::class, 'show']);
Route::put('/cars/{id}', [CarController::class, 'update']);
Route::delete('/cars/{id}', [CarController::class, 'destroy']);
