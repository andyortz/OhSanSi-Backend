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
use App\Http\Controllers\OlimpiadaGestionController;
use App\Http\Controllers\AreasFiltroController;
use App\Http\Controllers\colegiosController;
use App\Http\Controllers\OlimpistaController;
use App\Http\Controllers\VincularController;
use App\Http\Controllers\EstructuraOlimpiadaController;
use App\Http\Controllers\OlimpiadaController;
use App\Http\Controllers\VerificarInscripcionController;
use App\Http\Controllers\InscripcionNivelesController;
use App\Http\Controllers\ListaInscripcionController;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\DatosExcelController;
use App\Http\Controllers\PersonaController;
use App\Imports\OlimpistaImport;
use App\Imports\TutoresImport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/olimpistas/excel', [ExcelImportController::class, 'import']);
Route::post('/registro/excel', [DatosExcelController::class, 'cleanDates']);

//texto
//olimpiada
Route::get('/olimpiadas', [OlimpiadaGestionController::class, 'index']);
Route::get('/olimpiada/max-categorias', [OlimpiadaController::class, 'getMaxCategorias']);
Route::get('/olympiad-registration', [OlympiadRegistrationController::class, 'index']);
Route::post('/olympiad-registration', [OlympiadRegistrationController::class, 'store']);
Route::get('/olimpiadas/{id}/areas-niveles', [OlimpiadaController::class, 'getAreasConNiveles']);

//departamentos
Route::get('/departamentos', [DepartamentoController::class, 'index']);

//provincia
Route::get('/provincias/{id}', [ProvinciaController::class, 'porDepartamento']);

//colegio
Route::get('/colegios/{id}', [ColegiosController::class, 'porProvincia']);

//grados
Route::get('/grados', [GradosController::class, 'index']);
Route::get('/grados-niveles', [NivelCategoriaController::class, 'index']);
Route::post('/asociar-grados-nivel', [NivelCategoriaController::class,'asociarGrados']);

//niveles
Route::get('/get-niveles', [NivelCategoriaController::class, 'index2']);

//areas
Route::get('/areas', [AreasController::class, 'index']);
Route::post('/areas', [AreasController::class, 'store']);
Route::post('/areas/asociar-niveles', [NivelCategoriaController::class, 'asociarNivelesPorArea']);

//olimpista
Route::post('/olimpistas', [OlimpistaController::class, 'store']);
Route::get('olimpistas/cedula/{cedula}', [OlimpistaController::class, 'getByCedula']);
Route::get('/olimpistas/{ci}/areas-niveles', [OlimpistaController::class, 'getAreasNivelesInscripcion']);
Route::get('/olimpista/{ci}/inscripciones', [VerificarInscripcionController::class, 'getInscripcionesPorCI']);

//tutores/responsables
Route::post('/tutores', [TutoresControllator::class, 'store']);
Route::get('tutores/cedula/{cedula}',[TutoresControllator::class,'buscarPorCi']);
Route::get('/tutores',[TutoresControllator::class,'buscarCi']);

//personas
Route::get('/persona/{ci}', [PersonaController::class, 'getByCi']);

//inscripciones
Route::post('/inscripciones-con-tutor', [InscripcionNivelesController::class, 'storeWithTutor']);
Route::get('/inscripciones/{ci}', [ListaInscripcionController::class, 'obtenerPorResponsable']);
Route::get('/inscripciones', [ListaInscripcionController::class, 'index']);
Route::post('/inscripcionesOne', [InscripcionNivelesController::class, 'storeOne']);
Route::get('/verificar-inscripcion', [VerificarInscripcionController::class, 'verificar']);

//pagos
Route::get('/boleta-de-pago-individual/{id}', [ListaInscripcionController::class, 'individual']);
Route::get('/boleta-de-pago-grupal/{id}', [ListaInscripcionController::class, 'grupal']);

//Revisar
Route::post('/niveles', [NivelCategoriaController::class, 'store']);
Route::get('/olimpiadas/{id}/max-categorias', [OlimpiadaAreaController::class, 'maxCategorias']);
Route::post('/inscripciones', [InscripcionNivelesController::class, 'store']);
Route::post('/registrar-varios-olimpistas', [InscripcionNivelesController::class, 'registrarVarios']);
Route::post('/inscribir-multiples-olimpistas', [InscripcionNivelesController::class, 'registrarMultiplesConTutor']);
Route::get('/olimpista/{ci}/total-inscripciones', [VerificarInscripcionController::class, 'getTotalInscripciones']);
Route::get('/colegios', [ColegiosController::class, 'index']);
Route::get('/olympiad/{gestion}', [OlimpiadaGestionController::class, 'show']);
Route::get('/olimpiada/abierta', [OlimpiadaController::class, 'verificarOlimpiadaAbierta']);
Route::get('/estructura-olimpiada/{id_olimpiada}', [EstructuraOlimpiadaController::class, 'obtenerEstructuraOlimpiada']);
Route::post('/registro-olimpista', [OlimpistaController::class, 'store']);
Route::get('/olimpiada-data/{id}', [OlimpiadaController::class, 'getAreasYNiveles']);

//Posible Borrar
Route::get('/olimpiada/{id}/areas', [AreasController::class, 'areasPorOlimpiada']);
Route::get('/niveles/area/{id_area}', [NivelCategoriaController::class, 'nivelesPorArea']);
Route::get('/areas/{id}/niveles', [NivelCategoriaController::class, 'nivelesPorArea']);
Route::post('/asociar-tutor', [ParentescoController::class, 'asociarTutor']);
Route::get('tutores/email/{email}',[TutoresControllator::class,'getByEmail']);
Route::get('olimpistas/email/{email}', [OlimpistaController::class, 'getByEmail']);
Route::get('/areas-niveles-grados', [AreasController::class, 'areasConNivelesYGrados']);
Route::post('/vincular-olimpista-tutor', [VincularController::class, 'registrarConParentesco']);

//nose
Route::get('/olimpistas/{id_olimpista}/olimpiadas/{id_olimpiada}/areas-disponibles', [AreasFiltroController::class, 'obtenerAreasDisponibles']);