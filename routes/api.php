<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreasController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

//areas
Route::post('/areas', [AreasController::class,'store']);

Route::get('/areas',[AreasController::class,'index']);
