<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class FormularioController extends Controller
{
    public function index($transaction_id)
    {
        // Traer transaction si existe, o fallar
        $transaction = Transaction::findOrFail($transaction_id);
        $profile_id = Auth::user()->profiles->id;
        


        return view('index', compact('transaction_id', 'profile_id', 'transaction'));
    }

    // Generar PDF usando el mismo transaction_id
       public function generarPdf(Request $request, $transaction_id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'nombre1' => 'required|string|max:255',
            'codigo1' => 'required|string|max:50',
            'concepto' => 'required|string',
            'firma' => 'nullable|string|max:255',
        ]);

        $transaction = Transaction::findOrFail($transaction_id);
        $profile_id = Auth::user()->profiles->id; // Profile del usuario logueado

        // Generar PDF
        $pdf = Pdf::loadView('pdf.formato', [
            'datos' => $request->all(),
            'transaction' => $transaction,
            'profile_id' => $profile_id
        ]);

        // Nombre del archivo
        $nombreArchivo = 'evaluacion_anteproyecto_' . $transaction->id . '.pdf';
        $ruta = 'certificates/' . $nombreArchivo;

        // Guardar PDF físicamente
        Storage::disk('public')->put($ruta, $pdf->output());

        // Guardar o actualizar en certificates
        Certificate::updateOrCreate(
            ['transaction_id' => $transaction->id],
            [
                'acta' => $ruta,
                'type' => 4,
                'profile_id' => $profile_id,
            ]
        );

        return $pdf->download($nombreArchivo);
    }

}
