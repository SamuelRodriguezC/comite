<?php

use App\Models\Process;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\PdfActaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CertificateAdvisorController;
use App\Models\Certificate;

Route::get('/', function () {
    return view('welcome');
});

// ------- Asigna un panel a cada rol -------
Route::get('/dashboard', function () {
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
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});



//----------------------------------- RUTA PARA VER REQUERIMIENTOS -----------------------------------
Route::get('/secure/view/{file}', function ($file) {

    // Buscar el proceso que contiene el archivo
    $process = Process::where('requirement', "secure/requirements/{$file}")
        ->with('transaction') //Traer la transacción asociada
        ->firstOrFail(); //Si no existe el registro lanza error

    // Obtener la transacción asociada al proceso
    $transaction = $process->transaction;

    // Validar si el usuario en sesión tiene acceso a la transacción
    if (!$transaction->userHasAccess()) {
        abort(403, 'No tienes permisos para acceder a este archivo.');
    }

    // Contruir ruta completa del archivo
    $path = storage_path('app/private/secure/requirements/' . $file);

    // Verificar si hay archivo fisico
    if (!file_exists($path)) {
        abort(404, 'No hay archivo');
    }

    return Response::file($path); // Muestra el archivo en el navegador
})->middleware(['auth'])->name('file.view');



//----------------------------------- RUTA PARA DESCARGAR REQUERIMIENTOS -----------------------------------
Route::get('/secure/download/{file}', function ($file) {

    // Buscar el proceso que contiene el archivo
    $process = Process::where('requirement', "secure/requirements/{$file}")
        ->with('transaction') //Traer la transacción asociada
        ->firstOrFail(); //Si no existe el registro lanza error

    // Buscar transacción asociada al proceso
    $transaction = $process->transaction;

    // Verificar si el usuario en sesión tiene acceso a la transacción
    if (!$transaction->userHasAccess()) {
        abort(403, 'No tienes permisos para descargar este requisito.');
    }

    // Construir la ruta completa del archivo
    $path = storage_path('app/private/secure/requirements/' . $file);
    if (!file_exists($path)) {
        abort(404, 'No hay archivo');
    }

    return Response::download($path); // Descarga el archivo
})->middleware(['auth'])->name('file.download');



//----------------------------------- RUTA PARA GENERAR ACTAS -----------------------------------
Route::get('/certificate/pdf/{id}', [PdfActaController::class, 'generate'])
    ->middleware(['auth', 'role:Coordinador|Super administrador'])
    ->name('certificate.pdf');


//----------------------------------- RUTA PARA VER CERTIFICADOS DE ESTUDIANTES -----------------------------------
Route::get('/certificate_students/view/{file}', function ($file) {
    $transaction = Transaction::whereHas('studentsCertificate', function ($q) use ($file) {
        $q->where('acta', "students_certificates/{$file}");
    })->firstOrFail();

    if (!$transaction->userHasAccess()) {
        abort(403);
    }

    $path = storage_path("app/private/students_certificates/{$file}");

    if (!file_exists($path)) {
        abort(404, 'Archivo no encontrado.');
    }

    return response()->file($path);
})->middleware(['auth'])->name('certificate.view');



//----------------------------------- RUTA PARA DESCARGAR CERTIFICADOS DE ESTUDIANTES -----------------------------------
Route::get('/certificate_students/download/{file}', function ($file) {
    $transaction = Transaction::whereHas('studentsCertificate', function ($q) use ($file) {
        $q->where('acta', "students_certificates/{$file}");
    })->firstOrFail();

    if (!$transaction->userHasAccess()) {
        abort(403);
    }

    $path = storage_path("app/private/students_certificates/{$file}");

    if (!file_exists($path)) {
        abort(404, 'Archivo no encontrado.');
    }

    return response()->download($path);
})->middleware(['auth'])->name('certificate.download');



//----------------------------------- RUTA PARA ACCEDER A LAS FIRMAS -----------------------------------
Route::get('/signatures/{filename}', function ($filename) {
    $user = Auth::user();

    // Solo coordinadores o superadministradores
    if (!$user || !($user->hasRole('Coordinador') || $user->hasRole('Super administrador'))) {

        // Guardar el intento de acceso no autorizado en el log
        Log::warning('Intento de acceso NO AUTORIZADO a firma', [
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'filename' => $filename,
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
        ]);

        abort(403, 'Acceso denegado. Este recurso es confidencial. El intento de acceso ha sido considerado GRAVE y ha sido registrado para su monitoreo.');
    }

    $path = 'signatures/' . $filename;

    if (!Storage::disk('private')->exists($path)) {
        abort(404, 'Archivo no encontrado.');
    }

    return response()->file(Storage::disk('private')->path($path));
})->name('signatures.show');

//----------------------------------- RUTA PARA GENERAR CERTIFICADOS DE ASESORES -----------------------------------
Route::post(
    '/transactions/{transaction}/profiles/{profile}/certify-advisor',
    [CertificateAdvisorController::class, 'storeAdvisor']
)->name('certificates.storeAdvisor');


//----------------------------------- RUTA PARA VER CERTIFICADOS DE ASESORES -----------------------------------
Route::get('/certificate_advisors/view/{file}', function ($file) {
    $user = Auth::user();

    // Buscar el certificado por su acta
    $certificate = Certificate::where('acta', "advisors_certificates/{$file}")
        ->where('type', 2) // 2 = asesor
        ->firstOrFail();

    $profile = $certificate->profile; // Perfil dueño del certificado

    // Validar acceso
    if (
        !$user->hasRole('Coordinador') &&
        !$user->hasRole('Super administrador') &&
        $profile->user_id !== $user->id
    ) {
        abort(403, 'No tienes permisos para acceder a este certificado.');
    }

    $path = storage_path("app/private/advisors_certificates/{$file}");
    abort_unless(file_exists($path), 404);

    return response()->file($path);
})->middleware(['auth'])->name('certificate_advisor.view');


//----------------------------------- RUTA PARA DESCARGAR CERTIFICADOS DE ASESORES -----------------------------------
Route::get('/certificate_advisors/download/{file}', function ($file) {
    $user = Auth::user();

    // Buscar el certificado por su acta
    $certificate = Certificate::where('acta', "advisors_certificates/{$file}")
        ->where('type', 2) // 2 = asesor
        ->firstOrFail();

    $profile = $certificate->profile; // Perfil dueño del certificado

    // Validar acceso
    if (
        !$user->hasRole('Coordinador') &&
        !$user->hasRole('Super administrador') &&
        $profile->user_id !== $user->id
    ) {
        abort(403, 'No tienes permisos para acceder a este certificado.');
    }
    $path = storage_path("app/private/advisors_certificates/{$file}");
    abort_unless(file_exists($path), 404);
    return response()->download($path);
})->middleware(['auth'])->name('certificate_advisor.download');



require __DIR__.'/auth.php';
