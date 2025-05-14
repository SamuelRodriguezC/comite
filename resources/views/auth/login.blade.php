<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <div class="relative">
                <x-text-input id="password" class="block w-full mt-1"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-600 focus:outline-none">
                    <x-heroicon-o-eye id="eyeIcon" class="w-6 h-6 text-red-800" />
                    <x-heroicon-o-eye-slash id="eyeSlashIcon" class="hidden w-6 h-6 text-red-800" />
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="text-red-600 border-red-500 rounded shadow-sm focus:ring-gray-300" name="remember">
                <span class="text-sm text-gray-500 ms-2">{{ __('Recordarme') }}</span>
            </label>
        </div>

        <div class="flex justify-between my-5">
            <a class="text-sm text-gray-500 underline rounded-md hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-700" href="{{ route('password.request') }}" wire:navigate>
                {{ __('¿Olvidó su Contraseña?') }}
            </a>
            <a class="text-sm text-gray-500 underline rounded-md 0 hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-700" href="{{ route('register') }}" wire:navigate>
                {{ __('Registrarse') }}
            </a>
        </div>

        <div class="flex items-center justify-center mt-4">
            <x-primary-button class="justify-center w-full">
                {{ __('Iniciar sesión') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

<script>
    // Obtiene una referencia al botón que se usará para mostrar/ocultar la contraseña
    const togglePasswordButton = document.getElementById('togglePassword');

    const passwordInput = document.getElementById('password');

    const eyeIcon = document.getElementById('eyeIcon');

    const eyeSlashIcon = document.getElementById('eyeSlashIcon');

    // Agrega un "escuchador de eventos" al botón. Cuando se haga clic en él, la función dentro se ejecutará.
    togglePasswordButton.addEventListener('click', () => {
        // Si es 'password', lo cambia a 'text' para mostrar la contraseña.
        // Si no es 'password' (es decir, es 'text'), lo cambia a 'password' para ocultarla.
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';

        // Establece el atributo 'type' del campo de entrada de la contraseña al nuevo valor ('text' o 'password').
        passwordInput.setAttribute('type', type);

        // Esto hace que el icono del ojo se muestre u oculte.
        eyeIcon.classList.toggle('hidden');

        // Esto asegura que solo el icono apropiado (mostrar u ocultar contraseña) esté visible.
        eyeSlashIcon.classList.toggle('hidden');
    });
</script>
