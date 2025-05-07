<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

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
    return redirect('admin');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
