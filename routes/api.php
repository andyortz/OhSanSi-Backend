<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OlimpiadaAreaController;
use App\Http\Controllers\NivelCategoriaController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\GradosController;
use App\Http\Controllers\InscripcionAreaController;
use App\Http\Controllers\TutoresControllator;
use App\Http\Controllers\OlympiadRegistrationController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\OlimpiadaGestionController;
// use App\Http\Controllers\AreasFiltroController;
use App\Http\Controllers\colegiosController;
// use App\Http\Controllers\OlimpistaController;
use App\Http\Controllers\VincularController;
use App\Http\Controllers\EstructuraOlimpiadaController;
use App\Http\Controllers\OlimpiadaController;
use App\Http\Controllers\VerificarInscripcionController;
use App\Http\Controllers\InscripcionNivelesController;
use App\Http\Controllers\EnrollmentListController;
use App\Http\Controllers\ExcelImportController;
use App\Http\Controllers\ExcelDataController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PaymentSlipController;;
use App\Http\Controllers\TestPreprocessorController;
use App\Http\Controllers\PagoValidacionController;
use App\Http\Controllers\PaymentInquiryController;
use App\Modules\Olympist\Controllers\OlympistController;


use App\Imports\OlimpistaImport;
use App\Imports\TutoresImport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/pago/verificar', [PagoValidacionController::class, 'verificar']);
//Excel
Route::post('/excel/data', [ExcelImportController::class, 'import']);
Route::post('/excel/registration', [ExcelDataController::class, 'cleanDates']);

// Niveles
Route::post('/niveles', [NivelCategoriaController::class, 'store']);
Route::post('/areas/asociar-niveles', [NivelCategoriaController::class, 'asociarNivelesPorArea']);
Route::get('/niveles/area/{id_area}', [NivelCategoriaController::class, 'nivelesPorArea']);
Route::post('/niveles', [NivelCategoriaController::class, 'store']);
Route::post('/asociar-grados-nivel', [NivelCategoriaController::class,'asociarGrados']);

// Grados
Route::get('/grados', [GradosController::class, 'index']);

Route::get('/grados-niveles/{id}', [NivelCategoriaController::class, 'getById']);

// Áreas por olimpiada
Route::get('/olympiads/{id}/areas', [AreaController::class, 'areasByOlympiad']);

// Niveles por área
Route::get('/areas/{id}/niveles', [NivelCategoriaController::class, 'nivelesPorArea']);

Route::get('/get-niveles/{id}', [NivelCategoriaController::class, 'index4']);
Route::get('/get-niveles-areas/{id}', [NivelCategoriaController::class, 'getByNivelesById']);
Route::get('/get-niveles/', [NivelCategoriaController::class, 'index3']);


Route::get('/olimpiadas/{id}/max-categorias', [OlimpiadaAreaController::class, 'maxCategorias']);

//Route::post('/inscripciones', [AreaRegistrationController::class, 'store']); //REVISAR!!!
// Route::post('/inscripciones', [InscripcionNivelesController::class, 'store']);//REVISAR!!!
// Route::post('/inscripcionesOne', [InscripcionNivelesController::class, 'storeOne']);//REVISAR!!!

// inscrpcion con posible tutor
Route::post('/inscripciones-con-tutor', [InscripcionNivelesController::class, 'storeWithTutor']);

// inscripcion de varios olimpistas con un tutor
Route::post('/registrar-varios-olimpistas', [InscripcionNivelesController::class, 'registrarVarios']);

// inscripcion de varios olimpistas con varios tutores
Route::post('/inscribir-multiples-olimpistas', [InscripcionNivelesController::class, 'registrarMultiplesConTutor']);

// inscripciones por olimpista
Route::get('/olimpista/{ci}/inscripciones', [VerificarInscripcionController::class, 'getInscripcionesPorCI']);
Route::get('/olimpista/{ci}/total-inscripciones', [VerificarInscripcionController::class, 'getTotalInscripciones']);

// Tutores
Route::post('/tutores', [TutoresControllator::class, 'store']);
Route::get('tutores/email/{email}',[TutoresControllator::class,'getByEmail']);
Route::get('/tutores',[TutoresControllator::class,'buscarCi' ]);

Route::get('tutores/cedula/{cedula}',[TutoresControllator::class,'buscarPorCi']);


// Áreas
Route::get('/areas', [AreaController::class, 'index']);
//Store the área
Route::post('/areas', [AreaController::class, 'store']);
// Obtain areas with thier levels and grades,   
Route::get('/areas-niveles-grados', [AreaController::class, 'areasConNivelesYGrados']);//NO SE ESTÁ UTILIZANDO REVISAR!!!

//Colegios
Route::get('/colegios', [ColegiosController::class, 'index']);
Route::get('/colegios/nombres', [ColegiosController::class, 'soloNombres']); 
Route::get('/colegios/{id}', [ColegiosController::class, 'porProvincia']);

//Olimpiadas
Route::get('/olimpiadas', [OlimpiadaGestionController::class, 'index']);
Route::get('/olimpiadas-actuales', [OlimpiadaGestionController::class, 'index2']);

Route::post('/olympiad-registration', [OlympiadRegistrationController::class, 'store']);
Route::get('/olympiad-registration', [OlympiadRegistrationController::class, 'index']);
Route::get('/olympiad/{gestion}', [OlimpiadaGestionController::class, 'show']);

//Olimpista Regitro
//Route::post('/student-registration', [StudentRegistrationController::class, 'store']);
//Route::get('/student-registration', [StudentRegistrationController::class, 'index']);

//Olimpista
Route::get('olimpistas/cedula/{cedula}', [OlympistController::class, 'getByCedula']);
// Route::get('olimpistas/email/{email}', [OlimpistaController::class, 'getByEmail']);
Route::post('/olimpistas', [OlympistController::class, 'store']);


//Departamentos
Route::get('/departaments', [DepartamentoController::class, 'index']);

//Provincias
Route::get('/provinces/{id}', [ProvinciaController::class, 'porDepartamento']);
// Route::get('/olimpistas/{id_olimpista}/olimpiadas/{id_olimpiada}/areas-disponibles', [AreasFiltroController::class, 'obtenerAreasDisponibles']); //REVISAR!!!
// Route::get('/estructura-olimpiada/{id_olimpiada}', [EstructuraOlimpiadaController::class, 'obtenerEstructuraOlimpiada']); //REVISAR!!!
Route::get('/provinces', [ProvinciaController::class, 'index']); 
//new db
// Route::post('/vincular-olimpista-tutor', [VincularController::class, 'registrarConParentesco']);//REVISAR!!!
// Route::get('/olimpiada/abierta', [OlimpiadaController::class, 'verificarOlimpiadaAbierta']); //REVISAR!!!
// Route::get('/verificar-inscripcion', [VerificarInscripcionController::class, 'verificar']); //REVISAR!!!

// Route::post('/registro-olimpista', [OlympistController::class, 'store']);


//Areas de inscripcion para un Olimpista // REVISAR!!!!
// Route::get('/olimpistas/{ci}/areas-niveles', [OlimpistaController::class, 'getAreasNivelesInscripcion']);

//maxima cantidad de categorias
Route::get('/olympiads/max-categories', [OlimpiadaController::class, 'getMaxCategorias']);

Route::get('/olympiads/{id}/levels-areas', [OlimpiadaController::class, 'getAreasConNiveles']);

// Route::get('/olimpiada-data/{id}', [OlimpiadaController::class, 'getAreasYNiveles']);//REVISAR!!!

//get Inscripciones

Route::get('/enrollments/{ci}/{estado}', [EnrollmentListController::class, 'obtenerPorResponsable'])
    ->where('status', 'PENDIENTE|PAGADO|TODOS');
Route::get('/enrollments/pending/{ci}', [EnrollmentListController::class, 'listasPagoPendiente']);

Route::get('/enrollments', [EnrollmentListController::class, 'index']);

Route::get('/receipts/individual/{id}', [EnrollmentListController::class, 'individual']);

Route::get('/receipts/group/{id}', [EnrollmentListController::class, 'grupal']);

Route::get('/enrrolments/participants/{id}',[EnrollmentListController::class, 'getById']);

Route::post('/category-levels', [NivelCategoriaController::class, 'newCategoria']);

Route::get('/person/{ci}', [PersonaController::class, 'getByCi']);

Route::post('/prueba-ocr', [PaymentSlipController::class, 'process']); //probar con cuidado

Route::post('/test-preprocessor', [TestPreprocessorController::class, 'test']);



Route::get('/payment/{ci}', [PaymentInquiryController::class, 'checkByCi']);
