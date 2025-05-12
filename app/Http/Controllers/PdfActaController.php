<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProfileTransaction;

class PdfActaController extends Controller
{
    //
    public function generar($id)
    {
        // Obtener la transacción
        $transaction = Transaction::findOrFail($id);

        // Obtener solo los perfiles con rol 'estudiante'
        $detalles = ProfileTransaction::with('profile.user', 'course', 'role')
            ->where('transaction_id', $id)
            ->whereHas('role', function ($query) {
                $query->where('name', 'Estudiante'); // asegúrate de que así se llama el rol
            })
            ->get();

        // Generar el PDF
        $pdf = Pdf::loadView('pdf.acta', [
            'transaction' => $transaction,
            'detalles' => $detalles,
        ]);

        return $pdf->download('acta-'.$transaction->id.'.pdf');
    }
}
