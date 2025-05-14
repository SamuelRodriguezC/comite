<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email"
                :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <div class="relative">
                <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required
                    autocomplete="new-password" />
                <button type="button" id="togglePasswordMain"
                    class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-600 focus:outline-none">
                    <x-heroicon-o-eye id="eyeIconMain" class="w-6 h-6 text-red-800" />
                    <x-heroicon-o-eye-slash id="eyeSlashIconMain" class="hidden w-6 h-6 text-red-800" />
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />

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

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Cambiar Contraseña') }}
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
</x-guest-layout>
