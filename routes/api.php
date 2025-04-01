<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OlimpiadaAreaController;
use App\Http\Controllers\NivelCategoriaController;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\GradosController;
use App\Http\Controllers\InscripcionAreaController;
use App\Http\Controllers\TutoresControllator;
use App\Http\Controllers\OlympiadRegistrationController;
use App\Http\Controllers\StudentRegistrationController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\ProvinciaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Niveles
Route::post('/niveles', [NivelCategoriaController::class, 'store']);
Route::get('/niveles/area/{id_area}', [NivelCategoriaController::class, 'nivelesPorArea']);

// Grados
Route::get('/grados', [GradosController::class, 'index']);

Route::get('/niveles/area/{id_area}', [NivelCategoriaController::class, 'nivelesPorArea']);

// Áreas por olimpiada
Route::get('/olimpiada/{id}/areas', [AreasController::class, 'areasPorOlimpiada']);

// Niveles por área
Route::get('/areas/{id}/niveles', [NivelCategoriaController::class, 'nivelesPorArea']);

Route::get('/olimpiadas/{id}/max-categorias', [OlimpiadaAreaController::class, 'maxCategorias']);

Route::post('/inscripciones', [InscripcionAreaController::class, 'store']);

// Tutores
Route::post('/tutores', [TutoresControllator::class, 'store']);
Route::get('/tutores',[TutoresControllator::class,'buscarCi' ]);

// Áreas
Route::get('/areas', [AreasController::class, 'index']);
Route::post('/areas', [AreasController::class, 'store']);
//Olimpiadas
Route::post('/olympiad-registration', [OlympiadRegistrationController::class, 'store']);
Route::get('/olympiad-registration', [OlympiadRegistrationController::class, 'index']);
//Olimpistas
Route::post('/student-registration', [StudentRegistrationController::class, 'store']);
Route::get('/student-registration', [StudentRegistrationController::class, 'index']);

//Departamentos
Route::get('/departamentos', [DepartamentoController::class, 'index']);

//Provincias
Route::get('/provincias/{id}', [ProvinciaController::class, 'porDepartamento']);

Route::get('/areas-niveles-grados', [AreasController::class, 'areasConNivelesYGrados']);
//tutores
Route::post('/tutores', [TutoresControllator::class, 'store']);
Route::get('/tutores',[TutoresControllator::class,'buscarCi' ]);



//areas
Route::post('/areas', [AreasController::class,'store']);
Route::post('/areas',[AreasController::class,'index']);
