<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TutoresControllator;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//tutores
Route::post('/tutores', [TutoresCOntrollator::class, 'store']);
use App\Http\Controllers\AreasController;



//areas
Route::post('/areas', [AreasController::class,'store']);
Route::get('/areas',[AreasController::class,'index']);
