<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;





    /**
     * @var array
     */
    protected array $profilesData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->profilesData = [
            'name' => $data['profiles_name'],
            'last_name' => $data['profiles_last_name'],
            'document_id' => $data['profiles_document_id'],
            'document_number' => $data['profiles_document_number'],
            'phone_number' => $data['profiles_phone_number'],
            'level' => $data['profiles_level'],
        ];

        $data['name'] = $data['profiles_name'];
        $data['email_verified_at'] = Carbon::now();

        // Remueve del array principal para evitar errores
        unset(
            $data['profiles_name'],
            $data['profiles_last_name'],
            $data['profiles_document_id'],
            $data['profiles_document_number'],
            $data['profiles_phone_number'],
            $data['profiles_level']
        );


        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->profiles()->create($this->profilesData);
    }
}
