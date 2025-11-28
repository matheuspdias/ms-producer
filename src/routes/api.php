<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/health', [UserController::class, 'healthCheck']);

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'store']);
});
