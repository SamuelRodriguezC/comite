<?php

namespace App\Filament\Student\Resources\TransactionResource\Pages;

use App\Filament\Student\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use App\Filament\Employer\Resources\TalkResource\Pages\CreateTalk as PagesCreateTalk;
use App\Filament\Student\Resources\ProcessResource\Pages\CreateProcess;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    // Pasar el campo “enabled” por defecto en 1 = "Habilitado"
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['enabled'] = 1;
        $data['certification'] = 1;
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
}
