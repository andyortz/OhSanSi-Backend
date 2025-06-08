<?php

use App\Modules\Olympiads\Controllers\OlympiadController;

Route::prefix('olympiads')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [OlympiadController::class, 'store']);
    Route::get('/', [OlympiadController::class, 'index']);
    Route::get('/upcoming', [OlympiadController::class, 'upcoming']);
    Route::get('/{id}/areas', [AreaController::class, 'areasByOlympiad']);
    
    Route::get('/', [OlympiadYearController::class, 'index']);
    Route::get('/now', [OlympiadYearController::class, 'index2']);
    Route::get('/{year}', [OlympiadYearController::class, 'show']);
    
});
Route::post('/olympiad-registration', [OlympiadRegistrationController::class, 'store']);
Route::get('/olympiad-registration', [OlympiadRegistrationController::class, 'index']);

Route::prefix('levels')->middleware('throttle:100,1')->group(function () {
    Route::get('/{id}', [CategoryLevelController::class, 'index4']);
    Route::get('/areas/{id}', [CategoryLevelController::class, 'getByNivelesById']);
    Route::get('/', [CategoryLevelController::class, 'index3']);
});

Route::prefix('areas')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [AreaController::class, 'index']);
    Route::post('/', [AreaController::class, 'store']);
    Route::get('/{id}', [LevelController::class, 'show']); // GET /levels/1
    Route::post('/association', [CategoryLevelController::class, 'associateLevelsByArea']);
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

Route::prefix('grades')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [GradesController::class, 'index']);
    Route::get('/levels/{id}', [CategoryLevelController::class, 'getById']);
    Route::post('/levels', [CategoryLevelController::class,'associateGrades']);
});

Route::get('/olympiads/max-categories', [OlympiadController::class, 'getMaxCategories']);
Route::get('/olympiads/{id}/max-categories', [OlympiadController::class, 'maxCategoriesOlympiad']);
Route::get('/olympiads/{id}/levels-areas', [OlympiadController::class, 'getAreasWithLevels']);
Route::post('/levels', [CategoryLevelController::class, 'newCategory']);