<?php

use App\Modules\Olympiads\Controllers\OlympiadController;
use App\Modules\Olympiads\Controllers\AreaController;

Route::prefix('olympiads')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [OlympiadController::class, 'store']);
    Route::get('/', [OlympiadController::class, 'index']);
    Route::get('/now', [OlympiadController::class, 'upcoming']);
    Route::get('/{year}', [OlympiadController::class, 'getByYear']);
    Route::get('/max-categories', [OlympiadController::class, 'getMaxCategories']);
    Route::get('/{id}/max-categories', [OlympiadController::class, 'maxCategoriesOlympiad']);
    Route::get('/{id}/levels-areas', [OlympiadController::class, 'getAreasWithLevels']);
    Route::get('/{id}/areas', [AreaController::class, 'areasByOlympiad']);
});

//Revise hasta aqui, falta lo de abajo
Route::prefix('levels')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [CategoryLevelController::class, 'index3']);
    Route::post('/', [CategoryLevelController::class, 'newCategory']);
    Route::get('/{id}', [CategoryLevelController::class, 'index4']);
    Route::get('/areas/{id}', [CategoryLevelController::class, 'getByNivelesById']);
});

Route::prefix('areas')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [AreaController::class, 'index']);
    Route::post('/', [AreaController::class, 'store']);
    Route::get('/{id}', [LevelController::class, 'show']); // GET /levels/1
    Route::post('/association', [CategoryLevelController::class, 'associateLevelsByArea']);
});

Route::prefix('grades')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [GradesController::class, 'index']);
    Route::get('/levels/{id}', [CategoryLevelController::class, 'getById']);
    Route::post('/levels', [CategoryLevelController::class,'associateGrades']);
});
