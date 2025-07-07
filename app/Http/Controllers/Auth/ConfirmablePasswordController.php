<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Muestra la vista para confirmar la contraseña del usuario.
     *
     * @return View
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Verifica que la contraseña ingresada por el usuario sea correcta.
     * Si es válida, almacena el tiempo de confirmación en sesión y redirige.
     *
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws ValidationException Si la contraseña no coincide.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
