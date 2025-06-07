<?php

// Rutas principales (ej: página de inicio)
Route::get('/', function () {
    return view('welcome');
});

// Cargar rutas modulares
require __DIR__ . '/modules/olympiads.php';
require __DIR__ . '/modules/olympists.php';