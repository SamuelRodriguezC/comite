<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Muestra la vista de inicio de sesión.
     *
     * @return View
     */
    public function create(): View
    {
        return view('auth.login');
    }


    /**
     * Maneja la autenticación del usuario.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar campos de login
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Validar campos de login
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => trans('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        session(['just_logged_in' => true]);

        // Verificar si el correo fue confirmado
        if (! $user->email_verified_at) {
            Auth::logout(); // Opcional si no quieres que acceda a nada
            return redirect()->route('verification.notice');
        }

        // Redirección personalizada según el rol
        if ($user->hasRole('Coordinador')) {
            return redirect('coordinator'); // Cambia 'admin' por el ID del panel de coordinador
        }

        if ($user->hasRole('Asesor')) {
            return redirect('advisor'); // Cambia 'admin' por el ID del panel de asesor
        }

        if ($user->hasRole('Evaluador')) {
            return redirect('evaluator'); // Cambia 'admin' por el ID del panel de evaluador
        }

        if ($user->hasRole('Estudiante')) {
            return redirect('student'); // Cambia 'admin' por el ID del panel de estudiante
        }

        // Redirección por defecto si no tiene roles válidos
        return redirect('/');
    }

    /**
     * Cierra la sesión del usuario autenticado.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
