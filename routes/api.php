<?php

use App\Http\Controllers\Api\PeopleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Dating app routes
Route::prefix('people')->group(function () {
    Route::get('/', [PeopleController::class, 'index']); // Get recommended people
    Route::get('/liked', [PeopleController::class, 'likedList']); // Get liked people list
    Route::get('/disliked', [PeopleController::class, 'dislikedList']); // Get disliked people list
    Route::get('/liked-opponents', [PeopleController::class, 'likedOpponents']); // Get people who liked you
    Route::get('/matches', [PeopleController::class, 'matches']); // Get mutual matches
    Route::post('/{id}/like', [PeopleController::class, 'like']); // Like a person
    Route::post('/{id}/dislike', [PeopleController::class, 'dislike']); // Dislike a person
    Route::post('/check-popular', [PeopleController::class, 'checkPopularPeople']); // Check and notify popular people
});
