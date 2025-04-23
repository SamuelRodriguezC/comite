<?php

namespace App\Filament\Student\Resources\ProfileResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Student\Resources\ProfileResource;

class ViewProfile extends ViewRecord
{
    protected static string $resource = ProfileResource::class;

    // Método mount() corregido
    public function mount(int|string $record): void
    {
        // Obtiene el perfil del usuario actual
        $profile = Auth::user()->profiles;

        // Si se accede directamente con un ID, verifica que sea el propio perfil
        if ($profile->id != $record) {
            abort(403, 'No tienes permiso para ver este perfil');
        }

            parent::mount($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
