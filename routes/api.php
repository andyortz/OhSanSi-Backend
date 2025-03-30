<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\NivelCategoriaController;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\GradosController;
use App\Http\Controllers\TutoresController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Niveles
Route::post('/niveles', [NivelCategoriaController::class, 'store']);
Route::get('/niveles/area/{id_area}', [NivelCategoriaController::class, 'nivelesPorArea']);

// Grados
Route::get('/grados', [GradosController::class, 'index']);

// Tutores
Route::post('/tutores', [TutoresController::class, 'store']);

// Áreas
Route::get('/areas', [AreasController::class, 'index']);
Route::post('/areas', [AreasController::class, 'store']);
