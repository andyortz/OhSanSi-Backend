<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use  App\Modules\Olympiad\Controllers\CategoryLevelController;
use  App\Modules\Olympiad\Controllers\AreaController;
use  App\Modules\Olympiad\Controllers\GradesController;
// use App\Http\Controllers\InscripcionAreaController;

use App\Modules\Olympist\Controllers\TutorsController;
use App\Modules\Olympiad\Controllers\OlympiadRegistrationController;
use App\Modules\Olympist\Controllers\DepartamentController;

use App\Http\Controllers\ProvinciaController;
use App\Modules\Olympist\Controllers\ProvinceController;
use App\Modules\Olympiad\Controllers\OlympiadYearController;
// use App\Http\Controllers\AreasFiltroController;
use App\Modules\Olympist\Controllers\SchoolController;
// use App\Http\Controllers\OlimpistaController;
// use App\Http\Controllers\VincularController;
use App\Modules\Olympiad\Controllers\OlympiadStructureController;
use App\Modules\Olympiad\Controllers\OlypimpiadController;
use App\Modules\Olympist\Controllers\EnrollmentController;
use App\Modules\Olympist\Controllers\LevelEnrollmentController;
use App\Modules\Olympist\Controllers\EnrollmentListController;
use App\Modules\Olympiad\Controllers\ExcelImportController;
use App\Modules\Olympiad\Controllers\ExcelDataController;
use App\Modules\Olympist\Controllers\PersonController;
use App\Modules\Olympist\Controllers\PaymentSlipController;;
use App\Http\Controllers\TestPreprocessorController; //ojito con este 
use App\Http\Controllers\PagoValidacionController;  //ojito con este
use App\Modules\Olympist\Controllers\PaymentInquiryController;
use App\Modules\Olympist\Controllers\OlympistController;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::post('/pago/verificar', [PagoValidacionController::class, 'verificar']);
//Excel
Route::post('/excel/data', [ExcelImportController::class, 'import']);
Route::post('/excel/registration', [ExcelDataController::class, 'cleanDates']);

// Niveles
// Route::post('/niveles', [CategoryLevelController::class, 'store']); REVISAR!!!
Route::post('/areas/association', [CategoryLevelController::class, 'associateLevelsByArea']);
// Route::get('/niveles/area/{id_area}', [CategoryLevelController::class, 'nivelesPorArea']); REVISAR!!!
Route::post('/grades/levels', [CategoryLevelController::class,'associateGrades']);

// Grados
Route::get('/grados', [GradesController::class, 'index']);

Route::get('/grades/levels/{id}', [CategoryLevelController::class, 'getById']);

// Áreas por olimpiada
Route::get('/olympiads/{id}/areas', [AreaController::class, 'areasByOlympiad']);

// Niveles por área
// Route::get('/areas/{id}/niveles', [CategoryLevelController::class, 'nivelesPorArea']); REVISAR!!!

Route::get('/levels/{id}', [CategoryLevelController::class, 'index4']);
Route::get('/levesl/areas/{id}', [CategoryLevelController::class, 'getByNivelesById']);
Route::get('/levels', [CategoryLevelController::class, 'index3']);




//Route::post('/inscripciones', [AreaRegistrationController::class, 'store']); //REVISAR!!!
// Route::post('/inscripciones', [LevelEnrollmentController::class, 'store']);//REVISAR!!!
// Route::post('/inscripcionesOne', [LevelEnrollmentController::class, 'storeOne']);//REVISAR!!!

// inscrpcion con posible tutor
Route::post('/enrollments/with-tutor', [LevelEnrollmentController::class, 'storeWithTutor']);

// inscripcion de varios olimpistas con un tutor
// Route::post('/registrar-varios-olimpistas', [LevelEnrollmentController::class, 'registrarVarios']);REVISAR!!!

// inscripcion de varios olimpistas con varios tutores
// Route::post('/inscribir-multiples-olimpistas', [LevelEnrollmentController::class, 'registrarMultiplesConTutor']); REVISAR!!!

// inscripciones por olimpista
Route::get('/olympist/{ci}/enrollments', [EnrollmentController::class, 'getEnrollmentsByCi']);
// Route::get('/olimpista/{ci}/total-inscripciones', [EnrollmentController::class, 'getTotalInscripciones']);     REVISAR!!!

// Tutores
Route::post('/tutors', [TutorsController::class, 'store']);
// Route::get('/tutores/email/{email}',[TutorsController::class,'getByEmail']);  REVISAR!!!
// Route::get('/tutors',[TutorsController::class,'buscarCi' ]); 

Route::get('/tutors/id/{ci}',[TutorsController::class,'searchByCi']);


// Áreas
Route::get('/areas', [AreaController::class, 'index']);
//Store the área
Route::post('/areas', [AreaController::class, 'store']);
// Obtain areas with thier levels and grades,   
// Route::get('/areas-niveles-grados', [AreaController::class, 'areasConNivelesYGrados']);//NO SE ESTÁ UTILIZANDO REVISAR!!!

//Colegios
Route::get('/schools', [SchoolController::class, 'index']);
Route::get('/schools/names', [SchoolController::class, 'justNames']); 
Route::get('/schools/provinces/{id}', [SchoolController::class, 'byProvince']);

//Olimpiadas
Route::get('/olympiads', [OlympiadYearController::class, 'index']);
Route::get('/olympiads/now', [OlympiadYearController::class, 'index2']);

Route::post('/olympiad-registration', [OlympiadRegistrationController::class, 'store']);
Route::get('/olympiad-registration', [OlympiadRegistrationController::class, 'index']);
Route::get('/olympiad/{year}', [OlympiadYearController::class, 'show']);

//Olimpista Regitro
//Route::post('/student-registration', [StudentRegistrationController::class, 'store']);
//Route::get('/student-registration', [StudentRegistrationController::class, 'index']);

//Olimpista
Route::get('/olympist/id/{ci_olympist}', [OlympistController::class, 'getByCedula']);
// Route::get('olimpistas/email/{email}', [OlimpistaController::class, 'getByEmail']);
Route::post('/olympist', [OlympistController::class, 'store']);


//Departamentos
Route::get('/departaments', [DepartamentController::class, 'index']);

//Provincias
Route::get('/provinces/{id}', [ProvinciaController::class, 'porDepartamento']);
// Route::get('/olimpistas/{id_olimpista}/olimpiadas/{id_olimpiada}/areas-disponibles', [AreasFiltroController::class, 'obtenerAreasDisponibles']); //REVISAR!!!
// Route::get('/estructura-olimpiada/{id_olimpiada}', [EstructuraOlimpiadaController::class, 'obtenerEstructuraOlimpiada']); //REVISAR!!!
Route::get('/provinces', [ProvinceController::class, 'index']); 
//new db
// Route::post('/vincular-olimpista-tutor', [VincularController::class, 'registrarConParentesco']);//REVISAR!!!
// Route::get('/olimpiada/abierta', [OlimpiadaController::class, 'verificarOlimpiadaAbierta']); //REVISAR!!!
// Route::get('/verificar-inscripcion', [EnrollmentController::class, 'verificar']); //REVISAR!!!

// Route::post('/registro-olimpista', [OlympistController::class, 'store']);


//Areas de inscripcion para un Olimpista // REVISAR!!!!
// Route::get('/olimpistas/{ci}/areas-niveles', [OlimpistaController::class, 'getAreasNivelesInscripcion']);

//maxima cantidad de categorias
Route::get('/olympiads/max-categories', [OlympiadController::class, 'getMaxCategories']);
Route::get('/olympiads/{id}/max-categories', [OlympiadController::class, 'maxCategoriesOlympiad']);
Route::get('/olympiads/{id}/levels-areas', [OlympiadController::class, 'getAreasWithLevels']);

// Route::get('/olimpiada-data/{id}', [OlimpiadaController::class, 'getAreasYNiveles']);//REVISAR!!!

//get Inscripciones

Route::get('/enrollments/{ci}/{estado}', [EnrollmentListController::class, 'obtenerPorResponsable'])
    ->where('status', 'PENDIENTE|PAGADO|TODOS');
Route::get('/enrollments/pending/{ci}', [EnrollmentListController::class, 'listasPagoPendiente']);

Route::get('/enrollments', [EnrollmentListController::class, 'index']);

Route::get('/receipts/individual/{id}', [EnrollmentListController::class, 'individual']);

Route::get('/receipts/group/{id}', [EnrollmentListController::class, 'grupal']);

Route::get('/enrrolments/participants/{id}',[EnrollmentListController::class, 'getById']);

Route::post('/levels', [CategoryLevelController::class, 'newCategory']);

Route::get('/person/{ci}', [PersonController::class, 'getByCi']);

Route::post('/ocr', [PaymentSlipController::class, 'process']); //probar con cuidado

// Route::post('/test-preprocessor', [TestPreprocessorController::class, 'test']); REVISAR!!!



Route::get('/payment/{ci}', [PaymentInquiryController::class, 'checkByCi']);
