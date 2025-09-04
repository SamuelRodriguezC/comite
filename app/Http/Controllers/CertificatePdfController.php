<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificatePdfController extends Controller
{
    public function download($filename)
    {
        // Decodifica caracteres especiales de la URL
        $filename = urldecode($filename);

        // Elimina la carpeta duplicada si viene en $filename
        $filename = str_replace('evaluaciones_finales/', '', $filename);

        // Ruta completa al archivo privado
        $path = storage_path("app/private/evaluaciones_finales/{$filename}");

        // Si el archivo no existe, devuelve error 404
        if (!file_exists($path)) {
            abort(404, "Archivo no encontrado");
        }

        // Devuelve el archivo como descarga
        return response()->download($path);
    }
    public function view($filename)
{
    $filename = urldecode($filename);
    $filename = str_replace('evaluaciones_finales/', '', $filename);

    $path = storage_path("app/private/evaluaciones_finales/{$filename}");

    if (!file_exists($path)) {
        abort(404, "Archivo no encontrado");
    }

    // Devuelve el PDF para que se vea en el navegador
    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.$filename.'"'
    ]);
}
}
