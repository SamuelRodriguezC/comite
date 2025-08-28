<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use Filament\Forms;
use Filament\Tables;
use App\Models\Role;
use Filament\Actions;
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

    // Acciones de Encabezado
    protected function getHeaderActions(): array
    {
        return [
            // Acción para Volver a la edición de la transacción
            Actions\Action::make('back')
                ->label('Volver')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => route(
                    'filament.coordinator.resources.transactions.edit',
                    [
                        'record' => $this->record,
                    ]
                )),
        ];
    }

    // Pasar datos a la vista Blade
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

        // Preparar datos de los estudiantes de la transacción
        $students = $profiles->map(fn($profile) => [
            'nombres' => $profile->full_name,
            'document_type' => $profile->document?->type,
            'document_number' => $profile->document_number,
            'course' => $courses[$profile->pivot->courses_id] ?? null,
            'level' => $profile->level,
        ])->toArray();

        // Obtener el firmante seleccionado desde la sesión
        $signer = optional(\App\Models\Signer::find(session('certificate_signer_id')));

        // Retornar datos a la vista
        return [
            'grade_option' => $this->record->option?->option ?? '',
            'students' => $students,
            'signer'   => $signer,
        ];
    }
}
