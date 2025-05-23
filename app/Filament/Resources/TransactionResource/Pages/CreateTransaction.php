<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

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

        // Perfil del usuario autenticado
        if (!empty($data['courses_id']) && !empty($data['profile_id'])){
            DB::table('profile_transaction')->insert([
                'profile_id' =>  $data['profile_id'],
                'transaction_id' => $transaction->id,
                'courses_id' => $data['courses_id'],
                'role_id' => $data['role_id'],
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
