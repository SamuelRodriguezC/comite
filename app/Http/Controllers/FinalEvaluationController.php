<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Certificate;
use App\Models\Transaction;
use App\Notifications\TransactionNotifications;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class FinalEvaluationController extends Controller
{
    public function store(Request $request, Transaction $transaction, Profile $profile)
    {
        // -------------------- VALIDAR DATOS DEL FORMULARIO --------------------
        $data = $request->validate([
            // Proyecto y datos básicos
            'signer_id' => ['required', 'integer', 'exists:profiles,id'],
            'project_name' => ['required', 'string', 'max:255'],
            'grade_option' => ['required', 'string', 'max:255'],
            'advisor_id'   => ['required', 'integer', 'exists:profiles,id'],
            'evaluator_name' => ['required', 'string', 'max:255'],
            'jury_1_id'    => ['required', 'integer', 'exists:profiles,id'],
            'jury_2_id'    => ['required', 'integer', 'exists:profiles,id'],

            // Estudiantes
            'students' => ['required', 'array', 'min:1'],
            'students.*.name' => ['required', 'string', 'max:255'],
            'students.*.code' => ['required', 'string', 'max:50'],

            // Reporte final
            'final_report' => ['required', 'array', 'min:1'],
            'final_report.*.name'  => ['required', 'string', 'max:255'],
            'final_report.*.weight'=> ['required', 'numeric'],
            'final_report.*.grade' => ['required', 'numeric'],
            'final_report.*.parameters.*' => ['required', 'string', 'max:255'],
            'final_report.*.text'  => ['nullable', 'string'],

            // Evaluación por la empresa
            'company_approval'     => ['required', 'boolean'],
            'company_verification' => ['required', 'boolean'],

            // Nota final del reporte
            'final_report_grade' => ['required', 'numeric'],

            // Sustentación
            'projects_support' => ['required', 'array', 'min:1'],
            'projects_support.*.name'  => ['required', 'string', 'max:255'],
            'projects_support.*.weight'=> ['required', 'numeric'],
            'projects_support.*.grade' => ['required', 'numeric'],
            'projects_support.*.text'  => ['nullable', 'string'],

            'projects_support_grade' => ['required', 'numeric'],

            // Nota definitiva
            'final_grade' => ['required', 'numeric'],
        ]);


        // -------------------- CARGAR DATOS ADICIONALES DE EVALUADOR Y ASESOR --------------------
        // Buscar el Perfil
        $jury1 = Profile::find($data['jury_1_id']);
        $jury2 = Profile::find($data['jury_2_id']);

        // Nombres jurados
        $data['jury_1_name'] = $jury1?->full_name;
        $data['jury_2_name'] = $jury2?->full_name;

        // Cargar Firma jurados
        $data['jury_1_signature'] = $jury1?->signature->file_path;
        $data['jury_2_signature'] = $jury2?->signature->file_path;

        // Buscar y cargar asesor
        $advisor = Profile::find($data['advisor_id']);
        $data['advisor_name'] = $advisor?->full_name;

        // Cargar Firma Evaluador
        $data['evaluator_signature'] = $profile->signature->file_path;

        // -------------------- GENERAR EL PDF --------------------
        $pdf = Pdf::loadView('pdf.final_evaluation', [
            'data' => $data,
        ])->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'defaultFont' => 'DejaVu Sans']);

        // -------------------- ELIMINAR PDF SI YA EXISTE FISICAMENTE --------------------
        $certificate = Certificate::where([
            'transaction_id' => $transaction->id,
            'profile_id'     => $profile->id,
            'type'           => 3,
        ])->first();
        if ($certificate && $certificate->acta) {
            if (Storage::disk('private')->exists($certificate->acta)) {
                Storage::disk('private')->delete($certificate->acta);
            }
        }

        // -------------------- GUARDAR EL PDF EN STORAGE PRIVADO --------------------
        $fileName = "evaluacion-final-" . Str::slug($profile->full_name, '-') . "-" . Str::random(5) . ".pdf";

        // $fileName = "evaluación_final.pdf";
        $filePath = "final_evaluations/{$fileName}";
        Storage::disk('private')->put($filePath, $pdf->output());


        // -------------------- CREAR/ACTUALIZAR CERTIFICADO BD --------------------
        $certificate = Certificate::updateOrCreate(
            // Buscar con estos parámetros
            [
                'transaction_id' => $transaction->id,
                'type'           => 3,
                'profile_id' => $profile->id,
            ],
            // Actualizar estos campos
            [
                'signer_id'      => $data['signer_id'],
                'acta'       => $filePath,
            ]
        );
        // Generar URL al certificado
        $url = route('final_evaluation.show', ['fileName' => basename($certificate->acta)]);

        $students = $transaction->students;
        TransactionNotifications::sendFinalEvaluationStudents($students, $transaction);

        return $url; // Muy importante para redirigir después
    }

    public function show($filePath)
    {
        // reconstruir ruta completa
        $fullPath = "final_evaluations/{$filePath}";

        if (! Storage::disk('private')->exists($fullPath)) {
            abort(404, 'Archivo no encontrado');
        }

        return response()->file(
            Storage::disk('private')->path($fullPath),
            ['Content-Type' => 'application/pdf']
        );
    }
}
