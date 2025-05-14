<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Document;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $documents = Document::all(); // Obtén todos los tipos de documentos
        return view('auth.register', compact('documents'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Validaciones del perfil
            'profile_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'document_id' => ['required', 'exists:documents,id'],
            'document_number' => ['required', 'string', 'max:20', 'unique:profiles,document_number'],
            'phone_number' => ['required', 'string', 'max:20'],
            'level' => ['required', 'in:' . implode(',', array_column(\App\Enums\Level::cases(), 'value'))],

        ]);

        $user = User::create([
            'name' => $request->profile_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // Asignar Rol al Usuario al Registrarse
        $user->assignRole('Estudiante');

        // Crear Perfil Relacionado con el Usuario
        $user->profiles()->create([
            'name' => $request->profile_name,
            'last_name' => $request->last_name,
            'document_number' => $request->document_number,
            'phone_number' => $request->phone_number,
            'level' => $request->level,
            'document_id' => $request->document_id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirigir al crear
        if ($user->hasRole('Estudiante')) {
            return redirect('/student'); // Ajusta según tu panel de admin
        }

        return redirect(route('dashboard', absolute: false));
    }
}
