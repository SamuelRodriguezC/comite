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

    public function mount(): void
    {
        // Redirige directamente al perfil del usuario
        $profile = Auth::user()->profiles;
        $this->redirect(ProfileResource::getUrl('view', ['record' => $profile]));
    }
}
