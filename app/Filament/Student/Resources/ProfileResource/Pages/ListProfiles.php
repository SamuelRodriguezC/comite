<?php

namespace App\Filament\Student\Resources\ProfileResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Student\Resources\ProfileResource;

class ListProfiles extends ListRecords
{
    protected static string $resource = ProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // Función que redirige directamente al perfil del usuario
    public function mount(): void
    {
        $profile = Auth::user()->profiles;
        $this->redirect(ProfileResource::getUrl('view', ['record' => $profile]));
    }
}
