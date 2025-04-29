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

        // // Perfiles adicionales
        // if (!empty($data['profiles'])) {
        //     foreach ($data['profiles'] as $profileData) {
        //         if ($profileData['profile_id'] != $profile->id) { // aseguramos que no se duplique el usuario actual
        //             DB::table('profile_transaction')->insert([
        //                 'profile_id' => $profileData['profile_id'],
        //                 'transaction_id' => $transaction->id,
        //                 'courses_id' => $profileData['courses_id'],
        //             ]);
        //         }
        //     }
        // }

        // Crear proceso en Solicitud a la Transacción recién creada
        $process = $transaction->processes()->create([
            'state' => 3, // Pendiente default
            'stage_id' => 1, // 1 = Solicitud
            'completed' => false,
            'requirement' => ' ',
            'comment' => ' ',
        ]);


        Notification::make()
            ->title("¡La Transacción ha sido creada exitosamente!")
            ->body('Se ha Creado un Proceso de Solicitud para la Transacción por favor completa el Fomulario')
            ->icon('heroicon-o-ticket')
            ->success()
            ->actions([
                Action::make('Ver Proceso')
                    ->button()
                    // Botón para redirigir a la página de vista del proceso de solicitud creado
                    ->url(route('filament.student.resources.processes.edit', ['record' => $process->id]))
            ])
            ->send();
    }

    // Esto desactiva la notificación por defecto de Filament
    protected function getCreatedNotification(): ?Notification
    {
        return null;
    }
}
