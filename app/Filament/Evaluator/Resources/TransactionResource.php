<?php

namespace App\Filament\Evaluator\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\Status;
use App\Enums\Enabled;
use App\Models\Option;
use Filament\Forms\Set;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Components\Group;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group as FormGroup;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section as FormSection;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Evaluator\Resources\TransactionResource\Pages;
use App\Filament\Evaluator\Resources\TransactionResource\RelationManagers;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $modelLabel = "Opción de Grado";
    protected static ?string $pluralModelLabel = "Opciones de Grado";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FormSection::make('Opción')
                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->label('Número de Opción')
                            ->disabled()
                            ->numeric(),
                        Forms\Components\Select::make('component')
                            ->label("Componente de la opción de grado")
                            ->live()
                            ->preload()
                            ->disabled()
                            ->enum(Component::class)
                            ->options(Component::class)
                            ->afterStateUpdated(fn (Set $set) => $set('option_id',null)),
                        Forms\Components\Select::make('option_id')
                            ->label("Opción de Grado")
                            ->disabled()
                            ->relationship('option', 'option')
                            ->required()
                             // Función para filtrar la opción de grado por nivel universitario y componente
                            ->options(function (callable $get) {
                                $user = Auth::user();
                                if (!$user || !$user->profiles) {
                                    return ["Aún no tiene perfil"]; // Si el perfil no está disponible, no se muestran opciones
                                }
                                $userLevel = $user->profiles->level;
                                $selectedComponent = $get('component');
                                if (!$selectedComponent) {
                                    return ["Aún no ha seleccionado componente"]; // Evita mostrar opciones si el componente aún no se ha seleccionado
                                }
                                return Option::where('level', $userLevel)
                                    ->where('component', $selectedComponent)
                                    ->pluck('option', 'id');
                            }),
                    ])
                    ->columnSpan(1)
                    ->icon('heroicon-m-academic-cap'),
                    FormSection::make('Detalles')
                    ->schema([
                        FormGroup::make([
                            DateTimePicker::make('created_at')
                                ->label('Creado en')
                                ->disabled(),
                            DateTimePicker::make('updated_at')
                                ->label('Actualizado en')
                                ->disabled(),
                             //----- BOTONES PARA CAMBIAR CERTIFICACIÓN
                    Forms\Components\Toggle::make('status')
                        ->label('Enviar solicitud de certificación')
                        ->inline(false)
                        ->onColor('success')
                        ->offColor('danger')
                        ->afterStateHydrated(function (Forms\Components\Toggle $component, $state) {
                            $component->state($state == 3);

                            $shouldDisable = in_array($state, [
                                \App\Enums\Status::CERTIFICADO->value,
                                \App\Enums\Status::PORCERTIFICAR->value,
                                \App\Enums\Status::CANCELADO->value,
                            ]);

                            $component->disabled($shouldDisable);

                            if ($state == \App\Enums\Status::CERTIFICADO->value) {
                                $component->helperText('El estudiante ya fue Certificado, No puedes editar este campo');
                            } elseif ($state == \App\Enums\Status::PORCERTIFICAR->value) {
                                $component->helperText('Solicitud de certificación enviada exitosamente');
                            }
                            elseif ($state == \App\Enums\Status::CANCELADO->value) {
                                $component->helperText('La Opción esta cancelada no puedes enviar solicitud de certificación');
                            }
                        })
                        ->dehydrateStateUsing(fn (bool $state) => $state ? 3 : null)
                        ->dehydrated(fn (bool $state) => $state),
                        ])->columns(2),
                    ])
                    ->columnSpan(1)
                    ->icon('heroicon-m-eye')
                    ->visible(fn (string $context) => $context === 'edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label("Opción")
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label("Estado")
                    ->badge()
                    ->formatStateUsing(fn ($state) => Status::from($state)->getLabel())
                    ->color(fn ($state) => Status::from($state)->getColor())
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
                    // ->searchable(),
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
                    ])->attribute('enabled'),

                SelectFilter::make('status')
                        ->label('Estado')
                        ->options(Status::class)
                        ->attribute('status'),

                SelectFilter::make('courses')
                    ->label('Carreras')
                    ->options(\App\Models\Course::pluck('course', 'id'))
                    ->query(function (Builder $query, array $data): Builder {
                        if (!empty($data['value'])) {
                            // Assuming a many-to-many relationship between transactions and courses
                            $query->whereHas('courses', function (Builder $coursesQuery) use ($data) {
                                $coursesQuery->where('courses.id', $data['value']);
                            });
                        }
                        return $query;
                }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    // El botón de edición solo se  muestra si la transacción está habilitada
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
            InfoSection::make(fn ($record) => 'Opción #' . $record->id)
                ->icon('heroicon-m-academic-cap')
                ->schema([
                    TextEntry::make('component')
                        ->label('Componente')
                        ->formatStateUsing(fn ($state) => Component::from($state)->getLabel()),
                    TextEntry::make('Option.option')
                        ->label('Opción de grado'),
                    TextEntry::make('profiles.name')
                        ->label('Integrante(s)')
                        // Usar herlper para formatear la lista de nombres
                        ->formatStateUsing(fn($state) => format_list_html($state))
                        ->html(),
                    TextEntry::make('courses')
                        ->label('Carrera(s)')
                        // Usar herlper para formatear la lista de carreras
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

    // Función para filtrar las transacciones por usuario
    public static function getEloquentQuery(): Builder
    {
        // Obtén el perfil del usuario autenticado
        $profileId = Auth::user()->profiles->id;
        // Realiza la consulta para obtener las transacciones relacionadas con el perfil del usuario
        return Transaction::whereHas('profiles', function (Builder $query) use ($profileId) {
            // Selecciona las transacciones con el perfil autenticado y el rol evaluador para el panel evaluador
            $query->where('profile_id', $profileId)
                ->where('role_id', 3);
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
