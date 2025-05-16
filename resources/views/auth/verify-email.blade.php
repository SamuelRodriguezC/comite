<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Gracias por registrarte. Antes de empezar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar? Si no lo has recibido, estaremos encantados de enviarte otro.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
            {{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionó durante el registro.') }}
        </div>
    @endif

    <div class="flex items-center justify-between mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Enviar de Nuevo') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="text-sm text-gray-600 underline rounded-md hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-600">
                {{ __('Cerrar Sesión ') }}
            </button>
        </form>
    </div>
</x-guest-layout>
