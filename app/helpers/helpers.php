<?php

// ------------------ HACER VARIOS REGISTROS EN UNA LISTA ------------------
if (!function_exists('format_list_html')) {
    // Definir la función 'format_list_html' que recibe un parámetro $state
    function format_list_html($state): string
    {
        // Comenzar a construir una lista HTML (ul)
        return '<ul class="list-disc list-inside pl-8">' .

            // Usar la colección de Laravel para procesar el estado
            collect(is_string($state) ? explode(',', $state) : $state) // Si el estado es una cadena, lo dividimos por comas, de lo contrario, usamos el estado tal cual

                // Transformar cada elemento del array en un <li> (elemento de lista)
                ->map(fn($item) => "<li>$item</li>")

                // Unir todos los elementos de la lista en una cadena
                ->implode('') .

        // Cerrar la lista <ul>
        '</ul>';
    }
}





// ------------------ OBTENER PERFIL DE USUARIO EN SESIÓN ------------------
use Illuminate\Support\Facades\Auth;

if (!function_exists('auth_profile_id')) {
    function auth_profile_id()
    {
        return Auth::user()?->profiles->id;
    }
}

