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
        $pdf = Pdf::loadView('pdf.evaluacion_final', compact('datos'));
        return $pdf->download('evaluacion_final.pdf');
    }
}
