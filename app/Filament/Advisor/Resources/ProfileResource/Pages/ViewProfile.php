<?php

namespace App\Filament\Advisor\Resources\ProfileResource\Pages;

use Illuminate\Support\Facades\Auth;
use App\Filament\Advisor\Resources\ProfileResource;
use Filament\Resources\Pages\ViewRecord;

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
            \Filament\Actions\EditAction::make(),
        ];
    }
}
