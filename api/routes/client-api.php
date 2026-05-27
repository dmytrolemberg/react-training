<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Route;
use App\Client\Controllers\Api\V1\AuthController;
use App\Client\Controllers\Api\V1\PingController;

Route::get('/ping', PingController::class);

Route::prefix('auth')->group(function (): void {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});
