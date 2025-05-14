<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('¿Ha olvidado su contraseña? No se preocupe. Indíquenos su dirección de correo electrónico y le enviaremos un enlace para restablecer la contraseña que le permitirá elegir una nueva.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Incorporación de botón iniciar sesion y registrarse -->
        <div class="flex justify-between my-5">
            <a class="text-sm text-gray-500 underline rounded-md hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-700" href="{{ route('login') }}" wire:navigate>
                {{ __('Iniciar Sesión') }}
            </a>
            <a class="text-sm text-gray-500 underline rounded-md hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-700" href="{{ route('register') }}" wire:navigate>
                {{ __('Registrarse') }}
            </a>
        </div>

        <div class="flex items-center justify-center mt-4">
            <x-primary-button class="justify-center w-full">
                {{ __('Restablecer contraseña') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
