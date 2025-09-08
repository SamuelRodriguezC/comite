<?php

namespace App\Filament\Advisor\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use App\Enums\Level;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ActionGroup;
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

    // ---------- OPTENER LA TRANSACCIÓN A LA QUE PERTENECEN LOS PERFILES ----------
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
            Forms\Components\TextInput::make('document_number')
                ->required()
                ->maxLength(255)
                ->visibleOn('create'),

            Select::make('courses_id')
                ->label('Carrera universitaria')
                ->options(function (Get $get) {
                    $documentNumber = $get('document_number');
                    if (!$documentNumber) {
                        return [];
                    }
                    // Buscar el perfil usando el número de documento
                    $profile = \App\Models\Profile::where('document_number', $documentNumber)->first();
                    if (!$profile) {
                        return [];
                    }
                    $userLevel = $profile->level;
                    return \App\Models\Course::where('level', $userLevel)
                        ->pluck('course', 'id');
                })
                ->searchable()
                ->required()
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('document_number') //Atributo de busqueda
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Documento'),
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
                    })
                    ->tooltip(fn ($record, $livewire) => $record->hasCertificate($this->ownerRecord)
                        ? 'Certificado/Evaluación Generado'
                        : ''
                    )
                    ->color(fn ($record, $livewire) =>
                        $record->user?->hasRole('Asesor') && $record->hasCertificate($livewire->ownerRecord)
                            ? 'success'
                            : null
                    )
                    ->icon(fn ($record, $livewire) =>
                        $record->user?->hasRole('Asesor') && $record->hasCertificate($livewire->ownerRecord)
                            ? 'heroicon-o-check-badge'
                            : ''
                    )
            ])
            ->filters([
                //
            ])
            // No se puede filtrar estudiantes con mi mismo nivel universitario, porque el método getRecordSelect está en la carpeta vendor
            ->headerActions([
                Tables\Actions\AttachAction::make()
                ->modalHeading('Ingrese el número del documento de identidad de la persona que quiere vincular')
                ->modalDescription('Solo podrá vincular integrantes dentro de las 12 horas siguientes a la creación de la Opción.')
                ->form(fn (AttachAction $action): array => [
                    $action->getRecordSelect()
                        ->reactive(), // Necesario para que al seleccionar cambien las carreras
                    Select::make('courses_id')
                        ->label('Ingrese la carrera de la persona vinculada')
                        ->options(function (Get $get) {
                            $recordId = $get('recordId'); // 'recordId' es el ID de la persona seleccionada
                            if (!$recordId) {
                                return [];
                            }
                            $profile = \App\Models\Profile::find($recordId);
                            if (!$profile) {
                                return [];
                            }
                            return getCoursesByProfileLevel($profile->level);
                        })
                        ->searchable()
                        ->required(),
                    Select::make('role_id')
                        ->label('Función del integrante')
                        ->options(function (Get $get) {
                            $recordId = $get('recordId');
                            if (!$recordId) return ["No hay perfil seleccionado"];
                            $profile = \App\Models\Profile::find($recordId);
                            if (!$profile || !$profile->user) return ["El perfil no existe o no tiene usuario asociado"];
                            // Retorna los roles como array
                            return $profile->user->roles->pluck('name', 'id');
                        })
                        ->searchable()
                        ->required(),
                ])->visible(fn () => $this->getTransaction()->isEditable())
            ])
            ->actions([
                ActionGroup::make([
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
                                ]) ->columns(1),  // Esto asegura que cada sección ocupe una columna

                            Section::make('Información Institucional')
                                ->schema([
                                    TextEntry::make('level')->label('Nivel Universitario')
                                        ->formatStateUsing(fn ($state) => Level::from($state)->getLabel()),
                                    TextEntry::make('pivot.courses_id')
                                        ->label('Carrera')
                                        ->formatStateUsing(function ($state) {return \App\Models\Course::find($state)?->course ?? 'Curso no encontrado';}),
                                    TextEntry::make('pivot.role_id')
                                        ->label('Rol')
                                        ->formatStateUsing(function ($state) {return \App\Models\Role::find($state)?->name ?? 'Rol no encontrado';}),
                            ])->columns(1),  // Esto asegura que cada sección ocupe una columna
                        ];
                    }),

                // Solo la persona en sesión puede cambiar su carrera y editarla antes del tiempo determinado
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) =>
                        $record->id === auth_profile_id() &&
                        $this->getTransaction()->isEditable()
                    ),
                // -------------------------- FUNCIONALIDAD PARA DESIVINCULAR PERSONAS  ----------------------------
                // La persona en sesión no puede desvincularse y puede desvincular a otros antes del tiempo determinado
                Tables\Actions\DetachAction::make()
                    ->visible(fn ($record) =>
                    $record->id !== auth_profile_id() &&
                    $this->getTransaction()->isEditable()
                ),
            ]),
            ])
            ->emptyStateActions([
                Tables\Actions\AttachAction::make(),
            ])
            ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([

                    ]),
                ]);
    }
}
