<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\PdfActaController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// ------- Asigna un panel a cada rol -------
Route::get('/login', function () {
    $user = Auth::user();
    if ($user->hasRole('Coordinador')) {
        return redirect('coordinator');
    }
    if ($user->hasRole('Super administrador')) {
        return redirect('coordinator');
    }
    if ($user->hasRole('Estudiante')) {
        return redirect('student');
    }
    if ($user->hasRole('Asesor')) {
        return redirect('advisor');
    }
    if ($user->hasRole('Evaluador')) {
        return redirect('evaluator');
    }
    // En caso de no tener rol asignado
    abort(403, 'No tienes un rol asignado');
    return redirect('/');
})->middleware(['auth', 'verified'])->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

//---------- ruta para ver requerimientos -------------
Route::get('/secure/view/{file}', function ($file) {
    $path = storage_path('app/private/secure/requirements/' . $file);
    // Verifica que el usuario esté autenticado y tenga permisos
    if (!Auth::check()) {
        abort(403, 'No autorizado.');
    }
    // Aquí puedes agregar lógica adicional de permisos con Gate o roles:
    // if (!Auth::user()->hasRole('Coordinador')) abort(403);
    if (!file_exists($path)) {
        abort(404, 'No hay archivo');
    }
    return Response::file($path); // Muestra el archivo en el navegador
})->middleware(['auth'])->name('file.view');

//--------- ruta para descargar requerimientos ---------
Route::get('/secure/download/{file}', function ($file) {
    $path = storage_path('app/private/secure/requirements/' . $file);
    if (!Auth::check()) {
        abort(403, 'No autorizado.');
    }
    if (!file_exists($path)) {
        abort(404, 'No hay archivo');
    }
    return Response::download($path); // Descarga el archivo
})->middleware(['auth'])->name('file.download');

//---------- ruta para ver actas -------------
Route::get('/actas/view/{file}', function ($file) {
    $path = storage_path('app/public/actas/' . $file);
    // Verifica que el usuario esté autenticado y tenga permisos
    if (!Auth::check()) {
        abort(403, 'No autorizado.');
    }
    // Aquí puedes agregar lógica adicional de permisos con Gate o roles:
    // if (!Auth::user()->hasRole('Coordinador')) abort(403);
    if (!file_exists($path)) {
        abort(404, 'No hay archivo');
    }
    return Response::file($path); // Muestra el archivo en el navegador
})->middleware(['auth'])->name('certificate.view');

//--------- ruta para descargar actas ---------
Route::get('/actas/download/{file}', function ($file) {
    $path = storage_path('app/public/actas/' . $file);
    if (!Auth::check()) {
        abort(403, 'No autorizado.');
    }
    if (!file_exists($path)) {
        abort(404, 'No hay archivo');
    }
    return Response::download($path); // Descarga el archivo
})->middleware(['auth'])->name('certificate.download');

Route::get('/certificate/pdf/{id}', [PdfActaController::class, 'generar'])->name('certificate.pdf');


require __DIR__.'/auth.php';
