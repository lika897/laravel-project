<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('auth', AuthController::class)->name('auth');

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('v1')
        ->as('api.v1.')
        ->group(function () {
            require __DIR__ . '/versions/v1.php';
        });

    Route::prefix('v2')
        ->as('api.v2.')
        ->group(function () {
            require __DIR__ . '/versions/v2.php';
        });

});
