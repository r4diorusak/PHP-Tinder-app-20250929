<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PersonController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/people', [PersonController::class, 'index']);
    Route::post('/people/{id}/like', [PersonController::class, 'like']);
    Route::post('/people/{id}/dislike', [PersonController::class, 'dislike']);
    Route::get('/likes', [PersonController::class, 'likedList']);
});
