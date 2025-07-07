<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
  /**
     * Muestra la vista de verificación de correo si el usuario aún no lo ha verificado.
     * Redirige al login si ya ha verificado su correo.
     *
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('login', absolute: false))
                    : view('auth.verify-email');
    }
}
