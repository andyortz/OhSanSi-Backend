<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TutoresControllator;
use App\Http\Controllers\AreasController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//tutores
Route::post('/tutores', [TutoresControllator::class, 'store']);
Route::get('/tutores',[TutoresControllator::class,'buscarCi' ]);



//areas
Route::post('/areas', [AreasController::class,'store']);
Route::post('/areas',[AreasController::class,'index']);
