<?php

namespace App\Filament\Advisor\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\Enabled;
use App\Models\Option;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use App\Enums\Certification;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Group;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group as FormGroup;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section as FormSection;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Advisor\Resources\TransactionResource\Pages;
use App\Filament\Advisor\Resources\TransactionResource\RelationManagers;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $modelLabel = "Ticket";
    protected static ?string $pluralModelLabel = "Tickets";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FormSection::make('Vincular integrante')
                    ->schema([
                        Forms\Components\Select::make('profile_id')
                            ->label('Número de documento del integrante')
                            ->visibleOn('create')
                            ->searchable()
                            ->live() // Esto permite que al cambiar se activen otros campos
                            ->required()
                            // Consulta a los perfiles de acuerdo al número de documento
                            ->getSearchResultsUsing(function (string $search) {
                                return \App\Models\Profile::where('document_number', 'like', "%{$search}%")
                                    //->orWhere('name', 'like', "%{$search}%")
                                    //->orWhere('last_name', 'like', "%{$search}%")
                                    ->limit(10)
                                    ->get()
                                    // A partir del id muestra número de documento y nombre completo
                                    ->mapWithKeys(fn ($profile) => [$profile->id => "{$profile->document_number} - {$profile->name} {$profile->last_name}"]);
                            })
                            ->getOptionLabelUsing(fn ($value) =>
                                optional(\App\Models\Profile::find($value))->document_number . ' - ' . optional(\App\Models\Profile::find($value))->name
                            )
                            // Al seleccionar un perfil actualiza la carrera, la opción de grado y guarda el nivel en un campo oculto
                            ->afterStateUpdated(function ($state, callable $set) {
                                $profile = \App\Models\Profile::find($state);
                                if ($profile) {
                                    $set('courses_id', null);
                                    $set('option_id', null);
                                    $set('role_id', null);
                                    $set('level', $profile->level); // guardar temporalmente el nivel
                                } else {
                                    $set('courses_id', null);
                                    $set('option_id', null);
                                    $set('role_id', null);
                                    $set('level', null);
                                }
                            }),
                        // Agrega un campo oculto para guardar el nivel universitario del perfil seleccionado
                        Forms\Components\Hidden::make('level')
                            ->default(fn (?Transaction $record) => $record?->profile?->level),
                        Forms\Components\Select::make('courses_id')
                            ->label('Carrera universitaria de la persona vinculada')
                            ->visibleOn('create')
                            // La carrera se filtra con la información del perfil seleccionado
                            ->options(function (callable $get) {
                                $level = $get('level');
                                if ($level !== null) {
                                    return \App\Models\Course::where('level', $level)->pluck('course', 'id');
                                }
                                else {
                                    return ["Aún no ha vinculado a un integrante"];
                                }
                            })
                            ->searchable()
                            ->required()
                            ->live(),
                        Forms\Components\Select::make('role_id')
                            ->label('Función del integrante')
                            ->options(function (Get $get) {
                                $profileId = $get('profile_id');
                                if (!$profileId) return ["No hay perfil seleccionado"];
                                $profile = \App\Models\Profile::find($profileId);
                                if (!$profile || !$profile->user) return ["El perfil no existe o no tiene usuario asociado"];
                                // Retorna los roles como array
                                return $profile->user->roles->pluck('name', 'id');
                            })
                            ->searchable()
                            ->required()
                            // Solo se muestra cuando se ha seleccionado un perfil
                            //->visible(fn (Get $get) => !is_null($get('profile_id')))
                    ])
                    ->columnSpan(1)
                    ->description('Ingresa el número de documento del primer integrante y su carrera. (Puedes agregar más integrantes en el modo de edición).')
                    ->icon('heroicon-m-user-circle')
                    ->visible(fn (string $context) => $context === 'create'), //Solo es visible al crear (Sección)

                FormSection::make('Opción de grado')
                    ->schema([
                        //Forms\Components\TextInput::make('profile_id')
                        //    ->label('Estudiante vinculado')
                        //    ->default(fn (?Transaction $record) =>
                        //        $record?->profile
                        //        ? "{$record->profile->document_number} - {$record->profile->name} {$record->profile->last_name}"
                        //        : 'No asignado')
                        //    ->disabled()
                        //    ->visibleOn('edit'),
                        // calcula el level del modelo si esta en edición
                        //Forms\Components\TextInput::make('level')
                        //    ->label('Nivel')
                        //    ->disabled()
                        //    ->default(fn (?Profile $record) => $record?->profile?->level)
                        //    ->visibleOn('edit'),
                        Forms\Components\TextInput::make('id')
                            ->label('Número de Ticket')
                            ->disabled()
                            ->numeric()
                            ->visibleOn('edit'),
                        Forms\Components\Select::make('component')
                            ->label("Componente de la opción de grado")
                            ->live()
                            ->preload()
                            ->required()
                            ->disabledOn('edit')
                            ->enum(Component::class)
                            ->options(Component::class)
                            // Limpiar el campo de opción de grado luego de modificar el componente
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('option_id', null);
                            }),
                        Forms\Components\Select::make('option_id')
                            ->label("Opción de grado")
                            ->options(function (callable $get) {
                                // Toma el nivel guardado en el campo oculto level y el componente elegido
                                $level = $get('level');
                                $component = $get('component');
                                // Si no hay nivel o componente, entonces no se puede buscar la opción de grado
                                $query = \App\Models\Option::query();
                                    if ($level) {
                                        $query->where('level', $level);
                                    }
                                    else {
                                        return ["Aún no ha vinculado a un integrante"];
                                    }
                                    if ($component !== null) {
                                        $query->where('component', $component);
                                    }
                                    else {
                                        return ["Aún no ha seleccionado el componente"];
                                    }
                                // Muestra las opciones de grado de acuerdo a la información anterior
                                return $query->pluck('option', 'id');
                            })
                            ->required()
                            ->visibleOn('create')
                            ->searchable()
                            ->live(), // Para reaccionar a cambios del componente
                        Forms\Components\Select::make('option_id')
                            ->label("Opción de grado")
                            ->visibleOn('edit')
                            ->options(function (callable $get) {
                                // Toma el nivel guardado en el campo oculto level y el componente elegido
                                $component = $get('component');
                                // Si no hay nivel o componente, entonces no se puede buscar la opción de grado
                                $query = \App\Models\Option::query();
                                    if ($component !== null) {
                                        $query->where('component', $component);
                                    }
                                    else {
                                        return ["Aún no ha seleccionado el componente"];
                                    }
                                // Muestra las opciones de grado de acuerdo a la información anterior
                                return $query->pluck('option', 'id');
                            })
                            ->required()
                            ->disabledOn('edit')
                            ->searchable()
                            ->live(), // Para reaccionar a cambios del componente
                    ])
                    ->columnSpan(1)
                    ->description('Debes ingresar el componente y la opción de grado del integrante vinculado.')
                    ->icon('heroicon-m-ticket'),
                    // ---------------- solamente es visible en edición --------------------
                    FormSection::make('Detalles')
                    ->schema([
                        FormGroup::make([
                            DateTimePicker::make('created_at')
                                ->label('Creado en')
                                ->disabled(),
                            DateTimePicker::make('updated_at')
                                ->label('Actualizado en')
                                ->disabled(),
                            Forms\Components\Toggle::make('enabled')
                                ->label('Habilitado')
                                ->inline(false)
                                ->disabledOn('edit')
                                ->onColor('success')
                                ->offColor('danger')
                                ->onIcon(Enabled::HABILITADO->getIcon())
                                ->offIcon(Enabled::DESHABILITADO->getIcon())
                                ->dehydrateStateUsing(fn (bool $state) => $state ? 1 : 2) // Al guardar: true => 1, false => 2
                                ->afterStateHydrated(function (Forms\Components\Toggle $component, $state) {
                                    $component->state($state === 1); // Al cargar: 1 => true, 2 => false
                                }),
                            Forms\Components\Select::make('certification')
                                ->label("Certificación")
                                ->live()
                                ->disabledOn('edit')
                                ->preload()
                                ->enum(Certification::class)
                                ->options(Certification::class),
                        ])->columns(2),
                    ])
                    ->columnSpan(1)
                    ->icon('heroicon-m-eye')
                    ->visible(fn (string $context) => $context === 'edit'), //Solo es visible al crear (Sección)
            ])->Columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label("Ticket")
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('component')
                    ->label("Componente")
                    ->formatStateUsing(fn ($state) => Component::from($state)->getLabel())
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('option.option')
                    ->label("Opción de grado")
                    ->words(3)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('courses')
                    ->label('Carreras')
                    ->sortable()
                    ->words(4),
                Tables\Columns\IconColumn::make('enabled')
                    ->label('Habilitado')
                    ->icon(fn ($state) => Enabled::from($state)->getIcon())
                    ->color(fn ($state) => Enabled::from($state)->getColor()),
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Creado en")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label("Actualizado en")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('component')
                ->label('Componente')
                ->options([
                    '1' => 'Investigativo',
                    '2' => 'No Investigativo',
                ])->attribute('component'),
                SelectFilter::make('enabled')
                ->label('Habilitado')
                ->options([
                    '1' => 'Habilitado',
                    '2' => 'Deshabilitado',
                ])->attribute('enabled')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->enabled !== 2),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            InfoSection::make(fn ($record) => 'Ticket #' . $record->id)
                ->icon('heroicon-m-ticket')
                ->schema([
                    TextEntry::make('component')
                        ->label('Componente')
                        ->formatStateUsing(fn ($state) => Component::from($state)->getLabel()),
                    TextEntry::make('Option.option')
                        ->label('Opción de grado'),
                    TextEntry::make('profiles.name')
                        ->label('Integrante(s)')
                        ->formatStateUsing(fn($state) => format_list_html($state))
                        ->html(),
                    TextEntry::make('courses')
                        ->label('Carrera(s)')
                        ->formatStateUsing(fn($state) => format_list_html($state))
                        ->html(),
                ])
                ->columns(2)->columnSpan(2),

            InfoSection::make('Detalles')
                ->icon('heroicon-m-eye')
                ->schema([
                    Group::make([
                        TextEntry::make('created_at')
                            ->label('Creado en')
                            ->dateTime()
                            ->dateTimeTooltip(),
                        TextEntry::make('updated_at')
                            ->label('Actualizado en')
                            ->dateTime()
                            ->dateTimeTooltip(),
                        IconEntry::make('enabled')
                            ->label('Habilitado')
                            ->icon(fn ($state) => Enabled::from($state)->getIcon())
                            ->color(fn ($state) => Enabled::from($state)->getColor()),
                        TextEntry::make('certification')
                            ->label('Certificación')
                            ->badge()
                            ->formatStateUsing(fn ($state) => Certification::from($state)->getLabel())
                            ->color(fn ($state) => Certification::from($state)->getColor()),
                    ])->columns(2),
                ])->columnSpan(1),
        ])->columns(3);
    }

    // Función para filtrar las transacciones por usuario
    public static function getEloquentQuery(): Builder
    {
        // Obtén el perfil del usuario autenticado
        $profileId = Auth::user()->profiles->id;
        // Realiza la consulta para obtener las transacciones relacionadas con el perfil del usuario
        return Transaction::whereHas('profiles', function (Builder $query) use ($profileId) {
            // Filtra transacción por perfil autenticado y rol asesor en panel asesor
            $query->where('profile_id', $profileId)
                ->where('role_id', 2);
        });
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProcessesRelationManager::class,
            RelationManagers\ProfilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/{record}'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
