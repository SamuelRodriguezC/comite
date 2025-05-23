<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use Filament\Tables;
use App\Enums\Status;
use App\Enums\Enabled;
use App\Models\Option;
use App\Models\Profile;
use Filament\Forms\Get;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Group;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group as FormGroup;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use Filament\Forms\Components\Section as FormSection;
use Filament\Infolists\Components\Group as InfolistGroup;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Resources\TransactionResource\RelationManagers;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $modelLabel = "Ticket";
    protected static ?string $pluralModelLabel = "Tickets";
    //protected static ?string $navigationGroup = "Etapas";
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?int $navigationSort = 1;

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
                            ->visibleOn('create')
                            ->enum(Component::class)
                            ->options(Component::class)
                            // Limpiar el campo de opción de grado luego de modificar el componente
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('option_id', null);
                            }),
                        Forms\Components\Select::make('component')
                            ->label("Componente de la opción de grado")
                            ->live()
                            ->preload()
                            ->required()
                            ->visibleOn('edit')
                            ->disabled(fn ($record) => !$record->isEditable())
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
                            ->disabled(fn ($record) => !$record->isEditable())
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
                                ->onColor('success')
                                ->offColor('danger')
                                ->onIcon(Enabled::HABILITADO->getIcon())
                                ->offIcon(Enabled::DESHABILITADO->getIcon())
                                ->dehydrateStateUsing(fn (bool $state) => $state ? 1 : 2) // Al guardar: true => 1, false => 2
                                ->afterStateHydrated(function (Forms\Components\Toggle $component, $state) {
                                    $component->state($state === 1); // Al cargar: 1 => true, 2 => false
                                }),
                            Forms\Components\Select::make('status')
                                ->label("Estado")
                                ->live()
                                ->preload()
                                ->enum(Status::class)
                                ->options(Status::class),
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
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Ticket')
                    ->searchable()
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
                Tables\Columns\TextColumn::make('status')
                    ->label("Estado")
                    ->badge()
                    ->formatStateUsing(fn ($state) => Status::from($state)->getLabel())
                    ->color(fn ($state) => Status::from($state)->getColor())
                    ->sortable()
                     ->tooltip(fn ($state) => Status::from($state)->getTooltip())
                    ->toggleable(isToggledHiddenByDefault: false),
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

                    SelectFilter::make('status')
                        ->label('Estado')
                        ->options(Status::class)
                        ->attribute('status'),

                    SelectFilter::make('enabled')
                        ->label('Habilitado')
                        ->options(Enabled::class)
                        ->attribute('enabled'),
                ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            InfoSection::make(fn ($record) => 'Número de Ticket: ' . $record->id)
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
                        TextEntry::make('status')
                            ->label('Estado')
                            ->badge()
                            ->formatStateUsing(fn ($state) => Status::from($state)->getLabel())
                            ->color(fn ($state) => Status::from($state)->getColor()),
                    ])->columns(2),
                ])->columnSpan(1),
        ])->columns(3);
    }

    // Filtra por solicitudes pendientes por certificar
    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->where('status', '3')->count();
    }

    // Describe el getNavigationBadge
    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Tickets pendientes por generar actas';
    }

    // carga la relación con certificados
    public static function eagerLoadRelationships(): array
    {
        return ['certificate']; // Muy importante
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
