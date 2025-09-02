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
    $pdf = Pdf::loadView('pdf.formato', compact('datos')); // usa la plantilla del Word
    return $pdf->download('evaluacion_anteproyecto.pdf');
}
}
