<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

// Visualizar PDF (se muestra en el navegador)
Route::get('/files/view/{file}', function ($file) {
    $path = storage_path('app/public/processes/requirements/' . $file);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->name('file.view');

// Descargar PDF (se descarga automáticamente)
Route::get('/files/download/{file}', function ($file) {
    $path = storage_path('app/public/processes/requirements/' . $file);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->download($path);
})->name('file.download');
