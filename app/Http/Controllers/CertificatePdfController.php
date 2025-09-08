<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificatePdfController extends Controller
{
    /**
     * Obtiene la ruta del archivo PDF desde la carpeta privada.
     */
    private function getFilePath($filename)
    {
        $filename = urldecode($filename);

        // Evita prefijos duplicados
        $filename = str_replace('certificates/', '', $filename);

        // Ruta fija: privada/certificates
        $path = storage_path("app/private/certificates/{$filename}");

        return file_exists($path) ? $path : null;
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
