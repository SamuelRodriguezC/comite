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

        Notification::make()
            ->title("¡La Transacción ha sido creada exitosamente!")
            ->body('Se han vinculado los perfiles y cursos correctamente.')
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
