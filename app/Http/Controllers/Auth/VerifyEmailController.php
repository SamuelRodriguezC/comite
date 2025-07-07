<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

/**
 * Controlador responsable de verificar la dirección de correo electrónico del usuario autenticado.
 */
class VerifyEmailController extends Controller
{
    /**
     * Marca la dirección de correo electrónico del usuario autenticado como verificada.
     *
     * Este método se invoca automáticamente cuando el usuario hace clic en el enlace de verificación enviado por correo.
     * Si el correo ya está verificado, redirige inmediatamente.
     * Si no, lo marca como verificado y dispara el evento Verified.
     *
     * @param  EmailVerificationRequest  $request
     * @return RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('login', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('login', absolute: false).'?verified=1');
    }
}
