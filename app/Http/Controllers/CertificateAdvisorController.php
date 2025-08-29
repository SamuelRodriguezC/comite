<?php


namespace App\Http\Controllers;

use App\Models\Signer;
use App\Models\Profile;
use App\Models\Certificate;
use App\Models\Transaction;
use App\Notifications\TransactionNotifications;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CertificateAdvisorController extends Controller

{
    public function storeAdvisor(Request $request, Transaction $transaction, Profile $profile)
    {
        // -------------------- VALIDAR DATOS DEL FORMULARIO --------------------
        $data = $request->validate([
            'signer_id'    => 'required|exists:signers,id',
            'thesis_title' => 'required|string|max:255',
            'defense_date' => 'required|date',
            'advisor_role' => 'required|in:Director,Codirector',
            'distinction'  => 'nullable|string|max:255',
            'academic_title'  => 'nullable|string|max:255',
        ]);

        // -------------------- OBTENER EL FIRMADOR --------------------
        $signer = Signer::find($data['signer_id']);

        // -------------------- ELIMINAR CERTIFICADO SI YA EXISTE FISICAMENTE --------------------
        $certificate = Certificate::where([
            'transaction_id' => $transaction->id,
            'profile_id'     => $profile->id,
            'type'           => 2,
        ])->first();
        if ($certificate && $certificate->acta) {
            if (Storage::disk('private')->exists($certificate->acta)) {
                Storage::disk('private')->delete($certificate->acta);
            }
        }

        // -------------------- OBTENER LOS ESTUDIANTES DE LA TRANSACCIÓN --------------------
        $students = $transaction->profiles->filter(fn($p) => $p->pivot->role_id == 1);

        // -------------------- GENERAR EL PDF --------------------
        $pdf = Pdf::loadView('pdf.advisor', [
            'advisor'       => $profile,
            'advisor_course'=> $profile->courseInTransaction($transaction)?->course ?? 'Curso no asignado',
            'thesis_title'  => $data['thesis_title'],
            'defense_date'  => Carbon::parse($data['defense_date']),
            'advisor_role'  => $data['advisor_role'],
            'distinction'   => $data['distinction'] ?? null,
            'academic_title'=> $data['academic_title'],
            'students'       => $students,
            'date'           => Carbon::now()->locale('es'),
            'city'           => 'Bogotá D.C.',
            'signatory'     => [
                'fullname'  => $signer->full_name,
                'faculty'   => $signer->faculty,
                'seccional' => $signer->seccional->getLabel(),
                'signature' => $signer->signature,
            ],
        ])->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultFont' => 'DejaVu Sans']);

        // -------------------- GUARDAR EL PDF EN STORAGE PRIVADO --------------------
        $fileName = "advisor-{$transaction->id}-" . Str::random(5) . ".pdf";
        $filePath = "advisors_certificates/{$fileName}";
        Storage::disk('private')->put($filePath, $pdf->output());

        // -------------------- CREAR/ACTUALIZAR CERTIFICADO BD --------------------
        $certificate = Certificate::updateOrCreate(
            // Buscar con estos parámetros
            [
                'transaction_id' => $transaction->id,
                'type'           => 2, // Asesor
                'profile_id' => $profile->id,
            ],
            // Actualizar estos campos
            [
                'signer_id'      => $data['signer_id'],
                'acta'       => $filePath,
            ]
        );

        // -------------------- ENVIAR NOTIFICACIÓN AL ASESOR --------------------
        TransactionNotifications::sendAdvisorCertification($profile->user, $transaction);

        // -------------------- RETORNAR LA REDIRECCIÓN --------------------
        return redirect()
            ->route('filament.coordinator.resources.transactions.certify-advisors', [
                'transaction' => $transaction->id,
                'profile'     => $profile->id,
                'signer'      => $data['signer_id'],
            ])
            ->with('success', 'Certificado generado correctamente');
    }

    public function view($file)
    {
        $path = storage_path("app/private/advisors_certificates/{$file}");
        abort_unless(file_exists($path), 404);

        return response()->file($path, ['Content-Type' => 'application/pdf']);
    }

}
