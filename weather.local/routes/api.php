<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TemperatureController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('/temperature', [TemperatureController::class, 'getTemperatureByDay'])
        ->middleware(['auth:sanctum', 'ability:GET_TEMPERATURE']);
    Route::post('/auth', [AuthController::class, 'auth']);
});


