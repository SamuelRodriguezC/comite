<?php

/**
 * Convierte una cadena separada por comas o un array en una lista HTML.
 *
 * @param string|array|null $state Cadena (e.g., "Uno,Dos") o array de elementos.
 * @return string Lista HTML formateada (<ul><li>Elemento</li>...</ul>)
 */
if (!function_exists('format_list_html')) {
    // Definir la función 'format_list_html' que recibe un parámetro $state
    function format_list_html($state): string
    {
        // Comenzar a construir una lista HTML (ul)
        return '<ul class="pl-8 list-disc list-inside">' .

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


/**
 * Obtiene el ID del perfil del usuario autenticado.
 *
 * @return int|null ID del perfil o null si no hay sesión activa.
 */
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

if (!function_exists('auth_profile_id')) {
    function auth_profile_id()
    {
        return Auth::user()?->profiles->id;
    }
}



/**
 * Retorna las carreras asociadas a un nivel de perfil dado.
 *
 * @param int $level Nivel académico (e.g., 1: Pregrado, 2: Posgrado).
 * @return array Arreglo [id => nombre_carrera] de las carreras.
 */
function getCoursesByProfileLevel($level) {
    return \App\Models\Course::where('level', $level)
        ->pluck('course', 'id')
        ->toArray();
}
