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
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    //public function store(LoginRequest $request): RedirectResponse
    //{
    //    $request->authenticate();
    //    $request->session()->regenerate();
    //    return redirect()->intended(route('dashboard', absolute: false));
    //}

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => trans('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

         // Si el usuario no está verificado, redirigir al dashboard
        if (! $user->email_verified_at) {
            return redirect()->route('dashboard');
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
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
