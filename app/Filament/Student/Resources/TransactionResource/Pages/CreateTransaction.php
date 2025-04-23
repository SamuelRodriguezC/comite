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

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    // Pasar el campo “enabled” por defecto en 1 = "Habilitado"
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['enabled'] = 1;
        return $data;
    }

    // Muestra la notificación después de crear el registro
    protected function afterCreate(): void
    {
        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->first();

        $transaction = $this->record;
        $courseId = $this->data['courses_id']; // viene del formulario

        if ($profile && $courseId) {
            DB::table('profile_transaction')->insert([
                'profile_id' => $profile->id,
                'transaction_id' => $transaction->id,
                'courses_id' => $courseId,
            ]);
        }

        Notification::make()
            ->title("¡La Transacción ha sido creada exitosamente!")
            ->body('Ahora puedes Realizar y consultar los procesos relacionados a tu transacción.')
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
