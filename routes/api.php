<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NivelCategoriaController;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\GradosController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/niveles', [NivelCategoriaController::class, 'store']);

Route::get('/areas', [AreasController::class, 'index']);

Route::get('/grados', [GradosController::class, 'index']);

Route::get('/niveles/area/{id_area}', [NivelCategoriaController::class, 'nivelesPorArea']);