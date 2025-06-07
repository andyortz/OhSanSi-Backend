<?php

use App\Modules\Olympiads\Controllers\OlympiadController;

Route::prefix('olympiads')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [OlympiadController::class, 'index']); // GET /olympiads
    Route::get('/{id}', [OlympiadController::class, 'show']); // GET /olympiads/1
});

Route::prefix('levels')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [LevelController::class, 'index']);    // GET /levels
    Route::post('/', [LevelController::class, 'store']);   // POST /levels
    Route::get('/{id}', [LevelController::class, 'show']); // GET /levels/1
});

Route::prefix('areas')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [LevelController::class, 'index']);    // GET /levels
    Route::post('/', [LevelController::class, 'store']);   // POST /levels
    Route::get('/{id}', [LevelController::class, 'show']); // GET /levels/1
});

Route::prefix('areas-level-olympiad')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [LevelController::class, 'index']);    // GET /levels
    Route::post('/', [LevelController::class, 'store']);   // POST /levels
    Route::get('/{id}', [LevelController::class, 'show']); // GET /levels/1
});

Route::prefix('category-level')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [LevelController::class, 'index']);    // GET /levels
    Route::post('/', [LevelController::class, 'store']);   // POST /levels
    Route::get('/{id}', [LevelController::class, 'show']); // GET /levels/1
});