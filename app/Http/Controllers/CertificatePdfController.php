<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificatePdfController extends Controller
{
    /**
     * Obtiene la ruta correcta del archivo, buscando primero en privada y luego en pública.
     */
    private function getFilePath($filename)
    {
        $filename = urldecode($filename);

        // Elimina prefijos duplicados si vienen en $filename
        $filename = str_replace('evaluaciones_finales/', '', $filename);
        $filename = str_replace('certificates/', '', $filename);

        // Ruta privada
        $private = storage_path("app/private/evaluaciones_finales/{$filename}");
        if (file_exists($private)) {
            return $private;
        }

        // Ruta pública
        $public = storage_path("app/public/certificates/{$filename}");
        if (file_exists($public)) {
            return $public;
        }

        return null;
    }

    /**
     * Descargar PDF
     */
    public function download($filename)
    {
        $path = $this->getFilePath($filename);

        if (!$path) {
            abort(404, "Archivo no encontrado");
        }

        return response()->download($path);
    }

    /**
     * Ver PDF en navegador
     */
    public function view($filename)
    {
        $path = $this->getFilePath($filename);

        if (!$path) {
            abort(404, "Archivo no encontrado");
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        ]);
    }
}
