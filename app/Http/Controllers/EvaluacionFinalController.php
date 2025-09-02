<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EvaluacionFinalController extends Controller
{
    // Formulario
    public function index()
    {
        return view('evaluacion_final.index');
    }

    // Procesar datos y mostrar resultado
    public function procesar(Request $request)
    {
        $datos = $request->all();
        return view('evaluacion_final.resultado', compact('datos'));
    }

    // Generar PDF
public function generarPdf(Request $request)
{
    $datos = $request->all();
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.evaluacion_final', compact('datos'));

    // -------------------- ARMAR NOMBRE DEL ARCHIVO --------------------
    $nombre1 = \Illuminate\Support\Str::slug($datos['nombre1'] ?? 'sin-nombre', '_');
    $codigo1 = $datos['codigo1'] ?? 'sin-codigo';

    $nombre2 = !empty($datos['nombre2']) ? \Illuminate\Support\Str::slug($datos['nombre2'], '_') : null;
    $codigo2 = !empty($datos['codigo2']) ? $datos['codigo2'] : null;

    // Si hay dos estudiantes, se agregan ambos
    if ($nombre2 && $codigo2) {
        $fileName = "evaluacion_final-{$nombre1}_{$codigo1}-{$nombre2}_{$codigo2}.pdf";
    } else {
        $fileName = "evaluacion_final-{$nombre1}_{$codigo1}.pdf";
    }

    $filePath = "evaluaciones_finales/{$fileName}";

    // -------------------- GUARDAR EL PDF EN STORAGE PRIVADO --------------------
    \Illuminate\Support\Facades\Storage::disk('private')->put($filePath, $pdf->output());

    // -------------------- DESCARGAR EL PDF --------------------
    return $pdf->download($fileName);
}

}
