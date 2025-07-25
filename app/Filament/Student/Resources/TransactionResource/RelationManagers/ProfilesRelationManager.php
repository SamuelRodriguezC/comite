<?php

namespace App\Filament\Student\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use App\Enums\Level;
use App\Enums\State;
use Filament\Tables;
use App\Enums\Enabled;
use App\Models\Course;
use App\Models\Profile;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Notifications\TransactionNotifications;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ProfilesRelationManager extends RelationManager
{
    protected static string $relationship = 'profiles';
    protected static ?string $title = 'Integrante(s) vinculados a la Opción';

    // ---------- OBTENER LA TRANSACCIÓN A LA QUE PERTENECEN LOS PERFILES ----------
    protected function getTransaction(): \App\Models\Transaction
    {
        return $this->ownerRecord; // $this->ownerRecord es el modelo Transaction al que pertenece este RelationManager
    }

    public static function getEloquentQuery(): Builder
    {
        // Obtén el perfil del usuario autenticado
        $profileId = Auth::user()->profiles->id;
        // Realiza la consulta para obtener las transacciones relacionadas con el perfil del usuario
        return Transaction::whereNHas('profiles', function (Builder $query) use ($profileId) {
            $query->where('profile_id', $profileId);
        });
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('document_number') //Atributo de busqueda
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Documento de identidad'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombres')
                    ->formatStateUsing(function ($state, $record) {
                        $userProfileId = Auth::user()?->profiles?->id;
                        // Mostrar en la columna nombre Tú en caso de que sea el perfil autenticado
                        return $state . ($record->profile_id === $userProfileId ? ' (Tú)' : '');
                    }),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Apellidos'),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Telefono'),
                Tables\Columns\TextColumn::make('pivot.courses_id')
                    ->label('Carrera')
                    ->words(3)
                    // Transformar el ID del curso a su nombre
                    ->formatStateUsing(function ($state) {
                        return \App\Models\Course::find($state)?->course ?? 'Curso no encontrado';
                    }),
                Tables\Columns\TextColumn::make('pivot.role_id')
                    ->label('Rol')
                    // Transformar el ID del curso a su nombre
                    ->formatStateUsing(function ($state) {
                        return \App\Models\Role::find($state)?->name ?? 'Rol no encontrado';
                    }),
            ])
            ->filters([
                //
            ])
            // No se puede filtrar estudiantes con mi mismo nivel universitario, porque el método getRecordSelect está en la carpeta vendor
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->modalHeading('Ingrese el número del documento de identidad de la persona que quiere vincular')
                    ->modalDescription('Solo podrá vincular integrantes dentro de las 12 horas siguientes a la creación de la Opción. Durante este periodo, únicamente será posible agregar un estudiante adicional y un asesor. Los perfiles vinculados serán posteriormente revisados por el coordinador y se asignará comité evaluador si la opción lo requiere.')
                    ->after(function ($record, $data) {
                        // Obtener el usuario del perfil vinculado y enviar notificación
                        if ($record->user) {
                            TransactionNotifications::sendTransactionAssigned($record->user, $this->getTransaction());
                        }
                    })
                    ->form(fn(AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Perfil')
                            ->reactive()
                            ->afterStateUpdated(function (Set $set) {
                                // Limpiar los campos al cambiar el perfil
                                $set('courses_id', null);
                                $set('role_id', null);
                            }),

                        Select::make('courses_id')
                            ->label('Ingrese la carrera de la persona vinculada')
                            ->options(function (Get $get) {
                                $recordId = $get('recordId');
                                if (!$recordId) return [];
                                $profile = \App\Models\Profile::find($recordId);
                                if (!$profile) return [];
                                return getCoursesByProfileLevel($profile->level);
                            })
                            ->required(),
                        Select::make('role_id')
                            ->label('Función del integrante')
                            ->options(function (Get $get) {
                                $recordId = $get('recordId');
                                if (!$recordId) return ["No hay perfil seleccionado"];
                                $profile = \App\Models\Profile::find($recordId);
                                if (!$profile || !$profile->user) return ["El perfil no existe o no tiene usuario asociado"];
                                return $profile->user->roles->pluck('name', 'id');
                            })
                            ->required()
                              ->rule(function (Get $get) {
                                return function ($attribute, $value, $fail) use ($get) {
                                    $transaction = $this->getTransaction();

                                    $studentRoleId = \App\Models\Role::where('name', 'Estudiante')->first()?->id;
                                    $advisorRoleId = \App\Models\Role::where('name', 'Asesor')->first()?->id;
                                    $evaluatorRoleId = \App\Models\Role::where('name', 'Evaluador')->first()?->id;

                                    $profiles = $transaction->profiles()->withPivot('role_id')->get();

                                    $studentCount = $profiles->where('pivot.role_id', $studentRoleId)->count();
                                    $advisorCount = $profiles->where('pivot.role_id', $advisorRoleId)->count();
                                    $evaluatorCount = $profiles->where('pivot.role_id', $evaluatorRoleId)->count();

                                    if ($value == $studentRoleId && $studentCount >= 2) {
                                        $fail('Ya hay 2 estudiantes vinculados a esta transacción.');
                                    }

                                    if ($value == $advisorRoleId && $advisorCount >= 1) {
                                        $fail('Ya hay 1 asesor vinculado a esta transacción.');
                                    }

                                    if ($value == $evaluatorRoleId && $evaluatorCount >= 0) {
                                        $fail('Usted no tiene permisos para asignar Evaluadores');
                                    }
                                };
                            }),
                    ])->attachAnother(false)
                    ->visible(fn() => $this->getTransaction()->isEditable() && $this->canAttachMoreProfiles())

            ])
            ->actions([

                // --------------------------- VER DETALLES ---------------------------
                Tables\Actions\ViewAction::make()
                    ->label('Ver')
                    ->infolist(function ($record) {
                        return [
                            Section::make('Información Personal')
                                ->schema([
                                    TextEntry::make('name')
                                        ->label('Nombre'),
                                    TextEntry::make('last_name')
                                        ->label('Apellido'),
                                    TextEntry::make('User.email')
                                        ->label('Email'),
                                    TextEntry::make('phone_number')
                                        ->label('Número de Teléfono'),
                                ])->columns(1),

                            Section::make('Información Institucional')
                                ->schema([
                                    TextEntry::make('level')
                                        ->label('Nivel Universitario')
                                        ->formatStateUsing(fn($state) => Level::from($state)->getLabel()),
                                    TextEntry::make('pivot.courses_id')
                                        ->label('Carrera')
                                        ->formatStateUsing(function ($state) {
                                            return \App\Models\Course::find($state)?->course ?? 'Curso no encontrado';
                                        }),
                                    TextEntry::make('pivot.role_id')
                                        ->label('Rol')
                                        ->formatStateUsing(function ($state) {
                                            return \App\Models\Role::find($state)?->name ?? 'Rol no encontrado';
                                        }),
                                ])->columns(1),
                        ];
                    }),

                // Solo la persona en sesión puede cambiar su carrera y editarla antes del tiempo determinado
                // Tables\Actions\EditAction::make()
                //     ->visible(
                //         fn($record) =>
                //         $record->id === auth_profile_id() &&
                //             $this->getTransaction()->isEditable()
                //     ),
                // La persona en sesión no puede desvincularse y puede desvincular a otros antes del tiempo determinado
                Tables\Actions\DetachAction::make()
                    ->visible(
                        fn($record) =>
                        $record->id !== auth_profile_id() &&
                            $this->getTransaction()->isEditable()
                    ),
            ])
            ->emptyStateActions([
                Tables\Actions\AttachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }


    // Función Auxiliar para determinar si el estudiante puede vincular mas personas
    protected function canAttachMoreProfiles(): bool
    {
        $transaction = $this->getTransaction();

        // Cuenta los perfiles existentes con roles específicos
        $profiles = $transaction->profiles()->withPivot('role_id')->get();

        $studentRoleId = \App\Models\Role::where('id', 1)->first()?->id;
        $advisorRoleId = \App\Models\Role::where('id', 2)->first()?->id;

        $studentCount = $profiles->where('pivot.role_id', $studentRoleId)->count();
        $advisorCount = $profiles->where('pivot.role_id', $advisorRoleId)->count();

        return $studentCount < 2 || $advisorCount < 1;
    }
}
