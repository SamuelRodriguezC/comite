<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Course;
use App\Models\Signer;
use App\Enums\Seccional;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\TransactionNotifications;

class PdfActaController extends Controller
{
    public function generate($id)
    {
        // -------------------- CARGAR RELACIONES NECESARIAS --------------------
        $transaction = Transaction::with([
            'option',
            'profiles.user',
            'profiles.document',
            'studentsCertificate',
        ])->findOrFail($id);

        // -------------------- CACHEAR EL ID DE ROL DE ESTUDIANTE --------------------
        static $studentRoleId;
        $studentRoleId ??= Role::where('name', 'Estudiante')->value('id');

        // -------------------- OBTENER ID DE LOS CURSOS (CARRERAS) --------------------
        $courseIds = $transaction->profiles->pluck('pivot.courses_id')->unique()->filter();
        $courses = Course::whereIn('id', $courseIds)->pluck('course', 'id');


        $studentProfiles = $transaction->profiles->where('pivot.role_id', $studentRoleId);


        // -------------------- MAPEAR ESTUDIANTES CON LA INFORMACIÓN YA CARGADA --------------------
        $students = $transaction->profiles
            ->where('pivot.role_id', $studentRoleId)
            ->map(fn ($profile) => [
                'fullname'        => $profile->full_name,
                'document_type'   => $profile->document?->type,
                'document_number' => $profile->document_number,
                'course'          => $courses[$profile->pivot->courses_id] ?? null,
                'level'           => $profile->level,
            ])->values()->all();

        // -------------------- OBTENER EL FIRMADOR DESDE LA SESIÓN --------------------
        $signer = Signer::find(session('certificate_signer_id'));

        // -------------------- GENERAR EL PDF --------------------
        $pdf = Pdf::loadView('pdf.acta', [
            'transaction'  => $transaction,
            'students'     => $students,
            'signatory'    => $signer ? [
                'fullname'  => $signer->full_name,
                'faculty'   => $signer->faculty,
                'seccional' => $signer->seccional->getLabel(),
                'signature' => $signer->signature,
            ] : null,
            'grade_option' => $transaction->option?->option ?? '',
            'city'         => 'Bogotá',
        ])->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
            'defaultFont'          => 'DejaVu Sans',
        ]);

        // -------------------- ELIMINAR PDF ANTIGUO SI EXISTE --------------------
        $certificate = $transaction->studentsCertificate()
            ->where('type', 1) // 1 = estudiante, 2 = asesor
            ->first();
        if ($certificate && $certificate->acta && Storage::disk('private')->exists($certificate->acta)) {
            Storage::disk('private')->delete($certificate->acta);
        }

        // -------------------- GUARDAR EL PDF EN STORAGE PRIVADO --------------------
        $fileName = "acta-{$transaction->id}-" . Str::random(5) . ".pdf";
        $filePath = "students_certificates/{$fileName}";
        Storage::disk('private')->put($filePath, $pdf->output());

        // Guardar certificado asociado
        $transaction->studentsCertificate()->updateOrCreate(
            [   'transaction_id' => $transaction->id,
                'type' => 1, // Tipo Estudiante
                'profile_id' => Auth::user()->id
            ],
            [
                'acta'      => $filePath,
                'signer_id' => $signer?->id,
            ]
        );

        // -------------------- ACTUALIZAR ESTADO DE LA TRANSACCIÓN (CERTIFICADO) Y RETORNAR --------------------
        $transaction->update(['status' => 4]);

        TransactionNotifications::sendCertificationStudents($studentProfiles, $transaction);

        return $pdf->stream($fileName);
    }

    public function view($file)
    {
        $path = storage_path("app/private/students_certificates/{$file}");
        abort_unless(file_exists($path), 404);

        return response()->file($path, ['Content-Type' => 'application/pdf']);
    }
}
