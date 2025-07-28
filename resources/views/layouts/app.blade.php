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

        <!-- Livewire Styles -->
        @livewireStyles

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow dark:bg-gray-800">
                    <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Livewire Scripts -->
        @livewireScripts
        <script>
    // Configura el token CSRF para todas las solicitudes AJAX (incluido Livewire)
    window.addEventListener('DOMContentLoaded', function () {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (window.livewire) {
            window.livewire.hook('component.initialized', () => {
                window.livewire.emit('setCsrfToken', token);
            });
        }

        // Como respaldo para otras libs o fetch
        window.axios?.defaults.headers.common['X-CSRF-TOKEN'] = token;
    });

    // Prevención del error 419 (expiración de sesión)
    window.addEventListener('livewire:exception', event => {
        if (event.detail.statusCode === 419) {
            event.preventDefault();
            window.location.reload();
        }
    });
</script>
    </body>
</html>
