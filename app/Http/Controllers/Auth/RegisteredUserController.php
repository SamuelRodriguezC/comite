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
     * Muestra la vista de registro con los tipos de documentos disponibles.
     *
     * @return View
     */
    public function create(): View
    {
        $documents = Document::all(); // Obtén todos los tipos de documentos
        return view('auth.register', compact('documents'));
    }

     /**
     * Procesa una solicitud de registro entrante.
     *
     * Valida los datos, crea un nuevo usuario, asigna el rol de Estudiante,
     * crea el perfil asociado, y autentica al usuario.
     *
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required','string','email','max:100','unique:' . User::class,'bail'],
            'password' => ['required', 'confirmed', Rules\Password::defaults(), 'bail'],
            // Validaciones del perfil
            'profile_name' => ['required', 'string', 'max:50', 'bail'],
            'last_name' => ['required', 'string', 'max:60', 'bail'],
            'document_id' => ['required', 'exists:documents,id', 'bail'],
            'document_number' => ['required', 'string', 'max:10', 'unique:profiles,document_number', 'min:7', 'bail'],
            'phone_number' => ['required', 'string', 'max:10', 'bail'],
            'level' => [
                'required',
                'in:' . implode(',', array_column(\App\Enums\Level::cases(), 'value')),
                'bail'
            ],

        ], [
            // Mensajes personalizados
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.string' => 'El campo correo electrónico debe ser una cadena de texto.',
            'email.email' => 'El campo correo electrónico debe ser una dirección de correo electrónico válida.',
            'email.max' => 'El campo correo electrónico no debe exceder los 100 caracteres.',
            'email.unique' => 'El correo electrónico ya está registrado.',

            'password.required' => 'El campo contraseña es obligatorio.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.', // Ejemplo de mensaje para min
            'password.defaults' => 'La contraseña no cumple con los requisitos de seguridad.',

            'profile_name.required' => 'El campo nombre es obligatorio.',
            'profile_name.string' => 'El campo nombre debe ser una cadena de texto.',
            'profile_name.max' => 'El campo nombre no debe exceder los 50 caracteres.',

            'last_name.required' => 'El campo apellido es obligatorio.',
            'last_name.string' => 'El campo apellido debe ser una cadena de texto.',
            'last_name.max' => 'El campo apellido no debe exceder los 60 caracteres.',

            'document_id.required' => 'El tipo de documento es obligatorio.',
            'document_id.exists' => 'El tipo de documento seleccionado no es válido.',

            'document_number.required' => 'El número de documento es obligatorio.',
            'document_number.string' => 'El número de documento debe ser una cadena de texto.',
            'document_number.max' => 'El número de documento no debe exceder los 10 caracteres.',
            'document_number.min' => 'El número de documento no debe ser menor a los 7 caracteres.',
            'document_number.unique' => 'El número de documento ya está en uso.',

            'phone_number.required' => 'El número de teléfono es obligatorio.',
            'phone_number.string' => 'El número de teléfono debe ser una cadena de texto.',
            'phone_number.max' => 'El número de teléfono no debe exceder los 10 caracteres.',

            'level.required' => 'El nivel es obligatorio.',
            'level.in' => 'El nivel seleccionado no es válido.',
        ]);

        // Crear el usuario
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

        // // Redirigir al crear
        // if ($user->hasRole('Estudiante')) {
        //     return redirect('/student'); // Ajusta según tu panel de admin
        // }

        return redirect(route('login', absolute: false));
    }
}
