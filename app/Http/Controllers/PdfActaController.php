<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ProfileTransaction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PdfActaController extends Controller
{
    //
    public function generar($id)
    {
        // Obtiene la transacción
        $transaction = Transaction::with('option')->findOrFail($id);
        // Busca estudiantes vinculados y genera vista
        $estudiantes = ProfileTransaction::with(['profile', 'courses', 'role'])
            ->where('transaction_id', $transaction->id)
            ->whereHas('role', fn($q) => $q->where('name', 'Estudiante'))
            ->get();
        $pdf = Pdf::loadView('pdf.acta', compact('transaction', 'estudiantes'));
        // Forzar incrustación de fuentes
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans',
        ]);
        // Guardar en storage/app/public/actas
        $fileName = 'acta-' . $transaction->id . '-' . Str::random(5) . '.pdf';
        $filePath = 'actas/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
        // Registrar en la base de datos
        $transaction->certificate()->updateOrCreate(
            ['transaction_id' => $transaction->id],
            ['acta' => $filePath]
        );
        // Actualizar campo certification a 3
        $transaction->certification = 3;
        $transaction->save();

        // Redirige a una vista del documento
        return $pdf->stream($fileName);
    }

    public function view($file)
    {
        $path = storage_path("app/public/actas/{$file}");
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path, ['Content-Type' => 'application/pdf']);
    }
}
