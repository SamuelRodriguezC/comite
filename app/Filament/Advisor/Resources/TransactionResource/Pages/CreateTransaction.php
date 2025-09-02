<?php

namespace App\Filament\Advisor\Resources\TransactionResource\Pages;

use Filament\Actions;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Notifications\TransactionNotifications;
use App\Filament\Advisor\Resources\TransactionResource;

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
        $transaction = $this->record;
        $data = $this->data;

        // Inserta al estudiante del formulario
        if (!empty($data['courses_id']) && !empty($data['profile_id'])){
            DB::table('profile_transaction')->insert([
                'profile_id' =>  $data['profile_id'],
                'transaction_id' => $transaction->id,
                'courses_id' => $data['courses_id'],
                'role_id' => $data['role_id'],
            ]);
        }

        // Insertar asesor autenticado automáticamente
        $user = Auth::user();
        $profile = $user->profiles;
        if ($profile) {
            DB::table('profile_transaction')->insert([
                'profile_id' => $profile->id,
                'transaction_id' => $transaction->id,
                'courses_id' => $profile->level == 2 ? 7 : 1,
                'role_id' => 2, // asesor
            ]);
        }

        foreach ([1, 2] as $stageId) {
            $transaction->processes()->create([
                'state' => 3,
                'stage_id' => $stageId,
                'completed' => false,
                'requirement' => ' ',
                'comment' => ' ',
            ]);
        }


        // Enviar notificación al usuario del perfil
        $studentProfile = Profile::find($data['profile_id']);
        if ($studentProfile && $studentProfile->user) {
            TransactionNotifications::sendTransactionAssigned($studentProfile->user, $transaction, $data['role_id']);
        }

        Notification::make()
            ->title("¡La Opción ha sido creada exitosamente!")
            ->body('Se han vinculado los perfiles y cursos correctamente.')
            ->icon('heroicon-o-academic-cap')
            ->success()
            ->send();
    }

    // Esto desactiva la notificación por defecto de Filament
    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }
}
