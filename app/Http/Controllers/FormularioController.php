<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Usaremos DomPDF

class FormularioController extends Controller
{
    // Mostrar formulario
    public function index()
    {
        return view('index'); // resources/views/index.blade.php
    }

    // Procesar formulario y mostrar resultado
    public function procesar(Request $request)
    {
        $datos = $request->all();
        return view('resultado', compact('datos'));
    }

    
// Generar PDF desde los datos
public function generarPdf(Request $request)
{
    $datos = $request->all();
    $pdf = Pdf::loadView('pdf.formato', compact('datos'));

    // -------------------- ARMAR NOMBRE DEL ARCHIVO --------------------
    // Normalizamos (quita espacios raros y acentos)
    $nombre1 = \Illuminate\Support\Str::slug($datos['nombre1'] ?? 'sin-nombre', '_');
    $codigo1 = $datos['codigo1'] ?? 'sin-codigo';

    $nombre2 = !empty($datos['nombre2']) ? \Illuminate\Support\Str::slug($datos['nombre2'], '_') : null;
    $codigo2 = !empty($datos['codigo2']) ? $datos['codigo2'] : null;

    // Si hay dos estudiantes, se agregan ambos
    if ($nombre2 && $codigo2) {
        $fileName = "anteproyecto-{$nombre1}_{$codigo1}-{$nombre2}_{$codigo2}.pdf";
    } else {
        $fileName = "anteproyecto-{$nombre1}_{$codigo1}.pdf";
    }

    $filePath = "anteproyectos/{$fileName}";

    // -------------------- GUARDAR EL PDF EN STORAGE PRIVADO --------------------
    \Illuminate\Support\Facades\Storage::disk('private')->put($filePath, $pdf->output());

    // -------------------- DESCARGAR EL PDF --------------------
    return $pdf->download($fileName);
}

}
