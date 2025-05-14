<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900">
    <div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0 dark:bg-white">
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 text-gray-500 fill-current" />
            </a>
        </div>

        <div
            class="w-full px-6 py-4 mt-6 overflow-hidden bg-white shadow-md sm:max-w-3xl dark:bg-gray-100 sm:rounded-lg">
            <form method="POST" action="{{ route('register') }}" class="grid grid-cols-1 gap-6 md:grid-cols-2">
                @csrf
                <div class="p-4 mb-6 border rounded-md shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-700">{{ __('Información de Usuario') }}</h2>
                    <div>
                        <x-text-input id="name" class="block w-full mt-1" type="hidden" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block w-full mt-1" type="email" name="email"
                            :value="old('email')" required autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Contraseña')" />

                        <div class="relative">
                            <x-text-input id="password" class="block w-full mt-1" type="password" name="password"
                                required autocomplete="new-password" />
                            <button type="button" id="togglePasswordMain"
                                class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-600 focus:outline-none">
                                <x-heroicon-o-eye id="eyeIconMain" class="w-6 h-6 text-red-800" />
                                <x-heroicon-o-eye-slash id="eyeSlashIconMain" class="hidden w-6 h-6 text-red-800" />
                            </button>
                        </div>

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirmación de Contraseña')" />

                        <div class="relative">
                            <x-text-input id="password_confirmation" class="block w-full mt-1" type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-600 focus:outline-none">
                                <x-heroicon-o-eye id="eyeIcon" class="w-6 h-6 text-red-800" />
                                <x-heroicon-o-eye-slash id="eyeSlashIcon" class="hidden w-6 h-6 text-red-800" />
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="level" :value="__('Nivel Universitario')" />
                        <select id="level" name="level"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                            <option disabled selected>Seleccionar</option>
                            <option value="1">Pregrado</option>
                            <option value="2">Posgrado</option>
                        </select>
                        <x-input-error :messages="$errors->get('level')" class="mt-2" />
                    </div>
                </div>

                {{-- ----------- Información De Perfil ----------- --}}
                <div class="p-4 mb-6 border rounded-md shadow-sm">
                    <h2 class="mb-4 text-lg font-semibold text-gray-700">{{ __('Información Personal') }}</h2>

                    <div class="mt-4">
                        <x-input-label for="profile_name" :value="__('Nombre')" />
                        <x-text-input id="profile_name" class="block w-full mt-1" type="text" name="profile_name"
                            :value="old('profile_name')" required />
                        <x-input-error :messages="$errors->get('profile_name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="last_name" :value="__('Apellidos')" />
                        <x-text-input id="last_name" class="block w-full mt-1" type="text" name="last_name"
                            :value="old('last_name')" required />
                        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="document_id" :value="__('Tipo de Documento')" />
                        <select id="document_id" name="document_id"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                            <option value="">Seleccione una opción</option>
                            @foreach ($documents as $document)
                            <option value="{{ $document->id }}" {{ old('document_id')==$document->id ? 'selected' : ''
                                }}>
                                {{ $document->type }}
                            </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('document_id')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="document_number" :value="__('Número de Documento')" />
                        <x-text-input id="document_number" class="block w-full mt-1" type="number"
                            name="document_number" :value="old('document_number')" required />
                        <x-input-error :messages="$errors->get('document_number')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="phone_number" :value="__('Número de Telefono')" />
                        <x-text-input id="phone_number" class="block w-full mt-1" type="number" name="phone_number"
                            :value="old('phone_number')" required />
                        <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                    </div>
                </div>

                <div class="flex justify-between my-5 md:col-span-2">
                    <a class="text-sm text-gray-500 underline rounded-md hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-700"
                        href="{{ route('login') }}" wire:navigate>
                        {{ __('Iniciar Sesión') }}
                    </a>

                    <a class="text-sm text-gray-500 underline rounded-md hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-700"
                        href="{{ route('password.request') }}" wire:navigate>
                        {{ __('¿Olvidó su Contraseña?') }}
                    </a>
                </div>

                <div class="flex items-center justify-center mt-4 md:col-span-2">
                    <x-primary-button class="justify-center w-full">
                        {{ __('Registrarse') }}
                    </x-primary-button>
                </div>
            </form>
            <script>
                // Obtiene referencias al botón y al input de la confirmación de contraseña
    const togglePasswordConfirmationButton = document.getElementById('togglePassword');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const eyeIconConfirmation = document.getElementById('eyeIcon');
    const eyeSlashIconConfirmation = document.getElementById('eyeSlashIcon');

    // Obtiene referencias al botón y al input de la contraseña principal
    const togglePasswordButtonMain = document.getElementById('togglePasswordMain');
    const passwordInput = document.getElementById('password');
    const eyeIconMain = document.getElementById('eyeIconMain');
    const eyeSlashIconMain = document.getElementById('eyeSlashIconMain');

    // Función para mostrar/ocultar la contraseña
    function togglePasswordVisibility(inputElement, eyeIconElement, eyeSlashIconElement) {
        const type = inputElement.getAttribute('type') === 'password' ? 'text' : 'password';
        inputElement.setAttribute('type', type);
        eyeIconElement.classList.toggle('hidden');
        eyeSlashIconElement.classList.toggle('hidden');
    }

    // Agrega un "escuchador de eventos" al botón de la confirmación de contraseña
    togglePasswordConfirmationButton.addEventListener('click', () => {
        togglePasswordVisibility(passwordConfirmationInput, eyeIconConfirmation, eyeSlashIconConfirmation);
    });

    // Agrega un "escuchador de eventos" al botón de la contraseña principal
    togglePasswordButtonMain.addEventListener('click', () => {
        togglePasswordVisibility(passwordInput, eyeIconMain, eyeSlashIconMain);
    });
            </script>
        </div>
    </div>
</body>

</html>
