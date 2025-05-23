<?php

namespace App\Filament\Student\Resources\TransactionResource\Pages;

use Filament\Actions;
use App\Models\Profile;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Student\Resources\TransactionResource;
use App\Filament\Student\Resources\ProcessResource\Pages\CreateProcess;
use App\Filament\Employer\Resources\TalkResource\Pages\CreateTalk as PagesCreateTalk;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    // Pasar el campo “enabled” por defecto en 1 = "Habilitado"
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['enabled'] = 1;
        $data['status'] = 1;
        return $data;
    }

    // Hacer luego de Crear
    protected function afterCreate(): void
    {
        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->first();
        $transaction = $this->record;
        $data = $this->data;

        // Perfil del usuario autenticado
        if ($profile && !empty($data['courses_id'])) {
            DB::table('profile_transaction')->insert([
                'profile_id' => $profile->id,
                'transaction_id' => $transaction->id,
                'courses_id' => $data['courses_id'],
                'role_id' => 1, // estudiante
            ]);
        }

        // Crear Procesos con 3 etapas (1=solicitud 2=entrega 3=1°corrección) relacionados con la transacción
        foreach ([1, 2, 3] as $stageId) {
            $transaction->processes()->create([
                'state' => 3,
                'stage_id' => $stageId,
                'completed' => false,
                'requirement' => ' ',
                'comment' => ' ',
            ]);
        }

        Notification::make()
            ->title("¡La Transacción ha sido creada exitosamente!")
            ->body('Se han Creado los Procesos Correspondientes a la Transacción por Favor Completa el Fomulario de Solicitud')
            ->icon('heroicon-o-ticket')
            ->success()
            ->send();
    }

    // Esto desactiva la notificación por defecto de Filament
    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }

    // Evita la visualización del registro si está deshabilitado
    protected function authorizeAccess(): void
    {
        if (!Transaction::canCreate()) {
            abort(403, 'Acceso denegado, usted tiene tickets activos no puede crear más.');
        }
    }



}
