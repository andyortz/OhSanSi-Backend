<?php

use App\Modules\Olympiad\Controllers\OlympiadController;
use App\Modules\Olympiad\Controllers\AreaController;
use App\Modules\Olympiad\Controllers\OlympiadYearController;
use App\Modules\Olympiad\Controllers\OlympiadRegistrationController;
use App\Modules\Olympiad\Controllers\CategoryLevelController;
use App\Modules\Olympiad\Controllers\GradeController;
use App\Modules\Olympiad\Controllers\ExcelImportController;
use App\Modules\Olympiad\Controllers\ExcelDataController;

Route::prefix('olympiads')->middleware('throttle:100,1')->group(function () {
    Route::post('/', [OlympiadController::class, 'store']);
    Route::get('/', [OlympiadController::class, 'index']);
    Route::get('/now', [OlympiadController::class, 'upcoming']);
    Route::get('/max-categories', [OlympiadController::class, 'getMaxCategories']);
    Route::get('/{id}/max-categories', [OlympiadController::class, 'maxCategoriesOlympiad']);
    Route::get('/{id}/levels-areas', [OlympiadController::class, 'getAreasWithLevels']);
    Route::get('/{id}/areas', [AreaController::class, 'areasByOlympiad']);
    Route::get('/{year}', [OlympiadController::class, 'getByYear']);
});
Route::prefix('excel')->middleware('throttle:100,1')->group(function () {
    Route::post('/data', [ExcelImportController::class, 'import']);
    Route::post('/registration', [ExcelDataController::class, 'cleanDates']);
});
Route::prefix('levels')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [CategoryLevelController::class, 'index']);
    Route::post('/', [CategoryLevelController::class, 'store']);
    Route::get('/{id}', [CategoryLevelController::class, 'show']);
    Route::get('/areas/{id}', [CategoryLevelController::class, 'getByNivelesById']);    
});

Route::prefix('areas')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [AreaController::class, 'index']);
    Route::post('/', [AreaController::class, 'store']);
    Route::post('/association', [CategoryLevelController::class, 'associateLevelsByArea']);
});

Route::prefix('grades')->middleware('throttle:100,1')->group(function () {
    Route::get('/', [GradesController::class, 'index']);
    Route::get('/levels/{id}', [CategoryLevelController::class, 'getById']);
    Route::post('/levels', [CategoryLevelController::class,'associateGrades']);
});
