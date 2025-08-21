<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Forms;
use Filament\Tables;
use App\Models\Role;
use App\Models\Transaction;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;


class CertifyStudents extends Page implements Forms\Contracts\HasForms
{
    use InteractsWithForms;

    protected static string $resource = \App\Filament\Resources\TransactionResource::class;
    protected static string $view = 'filament.resources.transaction-resource.pages.certify-students';
    protected static ?string $title = 'Previsualización de Certificado';

    public Transaction $record;

    // Datos para la vista Blade de la previsualización
public function getViewData(): array
{
    // Cachear role_id en una sola consulta
    static $roleId = null;
    if ($roleId === null) {
        $roleId = Role::where('name', 'Estudiante')->value('id');
    }

    // Obtener IDs de cursos de los estudiantes en esta transacción
    $profiles = $this->record->profiles()
        ->wherePivot('role_id', $roleId)
        ->with('document:id,type') // eager loading del documento
        ->get();

    $courseIds = $profiles->pluck('pivot.courses_id')->filter()->unique();

    // Cargar todos los cursos en un solo query
    $courses = \App\Models\Course::whereIn('id', $courseIds)
        ->pluck('course', 'id');

    $students = $profiles->map(fn($profile) => [
        'nombres' => $profile->full_name,
        'document_type' => $profile->document?->type,
        'document_number' => $profile->document_number,
        'course' => $courses[$profile->pivot->courses_id] ?? null,
        'level' => $profile->level,
    ])->toArray();

    $signer = optional(\App\Models\Signer::find(session('certificate_signer_id')));

    return [
        'grade_option' => $this->record->option?->option ?? '',
        'students' => $students,
        'signer'   => $signer,
    ];
}
}
