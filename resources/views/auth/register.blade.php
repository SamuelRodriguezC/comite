<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <div class="relative">
                <x-text-input id="password" class="block w-full mt-1"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />
                <button type="button" id="togglePasswordMain" class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-600 focus:outline-none">
                    <x-heroicon-o-eye id="eyeIconMain" class="w-6 h-6 text-red-800" />
                    <x-heroicon-o-eye-slash id="eyeSlashIconMain" class="hidden w-6 h-6 text-red-800" />
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmación de Contraseña')" />

            <div class="relative">
                <x-text-input id="password_confirmation" class="block w-full mt-1"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-600 focus:outline-none">
                    <x-heroicon-o-eye id="eyeIcon" class="w-6 h-6 text-red-800" />
                    <x-heroicon-o-eye-slash id="eyeSlashIcon" class="hidden w-6 h-6 text-red-800" />
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- -----------Información De Perfil ----------- --}}

        <div class="mt-4">
            <x-input-label for="profile_name" :value="__('Nombre de Perfil')" />
            <x-text-input id="profile_name" class="block w-full mt-1" type="text" name="profile_name" :value="old('profile_name')" required />
            <x-input-error :messages="$errors->get('profile_name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="last_name" :value="__('Apellidos')" />
            <x-text-input id="last_name" class="block w-full mt-1" type="text" name="last_name" :value="old('last_name')" required />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="document_id" :value="__('Tipo de Documento')" />
            <select id="document_id" name="document_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                <option value="">Seleccione una opción</option>
                @foreach ($documents as $document)
                    <option value="{{ $document->id }}" {{ old('document_id') == $document->id ? 'selected' : '' }}>
                        {{ $document->type }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('document_id')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="document_number" :value="__('Número de Documento')" />
            <x-text-input id="document_number" class="block w-full mt-1" type="number" name="document_number" :value="old('document_number')" required />
            <x-input-error :messages="$errors->get('document_number')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="phone_number" :value="__('Número de Telefono')" />
            <x-text-input id="phone_number" class="block w-full mt-1" type="number" name="phone_number" :value="old('phone_number')" required />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="level" :value="__('Nivel Universitario')" />
            <select id="level" name="level" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                    <option disabled selected>Seleccionar</option>
                    <option value="1">Pregrado</option>
                    <option value="2">Posgrado</option>
            </select>
            <x-input-error :messages="$errors->get('level')" class="mt-2" />
        </div>

        <div class="flex justify-between my-5">
            <a class="text-sm text-gray-500 underline rounded-md hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-700" href="{{ route('login') }}" wire:navigate>
                {{ __('Iniciar Sesión') }}
            </a>

            <a class="text-sm text-gray-500 underline rounded-md hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-700" href="{{ route('password.request') }}" wire:navigate>
                {{ __('¿Olvidó su Contraseña?') }}
            </a>
        </div>

        <div class="flex items-center justify-center mt-4">
            <x-primary-button class="justify-center w-full">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
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
