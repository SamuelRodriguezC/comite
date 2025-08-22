<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Role;
use App\Models\Signer;
use App\Models\Course;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Enums\Seccional;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PdfActaController extends Controller
{
    public function generate($id)
    {
        // Eager load de relaciones necesarias
        $transaction = Transaction::with([
            'option',
            'profiles.user',
            'profiles.document',
            'certificate',
        ])->findOrFail($id);

        // Cachear el ID del rol estudiante (en config o en propiedad estática si se repite mucho)
        static $studentRoleId;
        $studentRoleId ??= Role::where('name', 'Estudiante')->value('id');

        // Obtener IDs de cursos en lote (evita N+1 queries)
        $courseIds = $transaction->profiles->pluck('pivot.courses_id')->unique()->filter();
        $courses = Course::whereIn('id', $courseIds)->pluck('course', 'id');

        // Mapear estudiantes con la información ya cargada
        $students = $transaction->profiles
            ->where('pivot.role_id', $studentRoleId)
            ->map(fn ($profile) => [
                'fullname'        => $profile->full_name,
                'document_type'   => $profile->document?->type,
                'document_number' => $profile->document_number,
                'course'          => $courses[$profile->pivot->courses_id] ?? null,
                'level'           => $profile->level,
            ])->values()->all();

        // Firmador desde sesión (una sola consulta)
        $signer = Signer::find(session('certificate_signer_id'));

        $pdf = Pdf::loadView('pdf.acta', [
            'transaction'  => $transaction,
            'students'     => $students,
            'signatory'    => $signer ? [
                'fullname'  => $signer->full_name,
                'faculty'   => $signer->faculty,
                'seccional' => Seccional::from($signer->seccional)->getLabel(),
                'signature' => $signer->signature,
            ] : null,
            'grade_option' => $transaction->option?->option ?? '',
            'city'         => 'Bogotá',
        ])->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
            'defaultFont'          => 'DejaVu Sans',
        ]);

        // Eliminar acta anterior si existe
        if ($transaction->certificate?->acta && Storage::disk('private')->exists($transaction->certificate->acta)) {
            Storage::disk('private')->delete($transaction->certificate->acta);
        }

        // Guardar PDF
        $fileName = "acta-{$transaction->id}-" . Str::random(5) . ".pdf";
        $filePath = "students_certificates/{$fileName}";
        Storage::disk('private')->put($filePath, $pdf->output());

        // Guardar certificado asociado
        $transaction->certificate()->updateOrCreate(
            ['transaction_id' => $transaction->id],
            [
                'acta'      => $filePath,
                'signer_id' => $signer?->id,
            ]
        );

        // Actualizar estado
        $transaction->update(['status' => 4]);

        return $pdf->stream($fileName);
    }

    public function view($file)
    {
        $path = storage_path("app/private/students_certificates/{$file}");
        abort_unless(file_exists($path), 404);

        return response()->file($path, ['Content-Type' => 'application/pdf']);
    }
}
