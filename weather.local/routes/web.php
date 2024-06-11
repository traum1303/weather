<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get('/', function () {
    return response()->json(['message' => 'Access denied'], Response::HTTP_FORBIDDEN);
})->name('login');
