<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/contacts', [ContactController::class, 'store']);
Route::get('/all-contacts', [ContactController::class, 'index']);
Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);
Route::put('/contacts/{id}', [ContactController::class, 'update']);
Route::get('/check-phone', [ContactController::class, 'checkPhone']);

