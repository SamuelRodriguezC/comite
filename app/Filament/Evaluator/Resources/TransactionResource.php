<?php

namespace App\Filament\Evaluator\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\Enabled;
use App\Models\Option;
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
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $modelLabel = "Ticket";
    protected static ?string $pluralModelLabel = "Tickets";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FormSection::make('Ticket')
                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->label('Número de Ticket')
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
                    ->icon('heroicon-m-ticket'),
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
                            ToggleButtons::make('certification')
                                 ->disabled(fn ($get) => $get('certification') == 3) // Se deshabilitan si ya está certificado
                                ->label('Certificación')
                                ->columns(2)
                                ->options([
                                    1 => 'No Certificado',
                                    2 => 'Por Certificar',
                                ])
                                ->colors([
                                    1 => 'danger',
                                    2 => 'warning',
                                ]),
                            Forms\Components\Placeholder::make('certification_notice')
                                ->label('Información Importante')
                                ->content('Debido a que estudiante ya esta CERTIFICADO no puede cambiar el campo de Certificación')
                                ->visible(fn ($get) => $get('certification') == 3),
                            Forms\Components\Toggle::make('enabled')
                                ->label('Habilitado')
                                ->inline(false)
                                ->onColor('success')
                                ->offColor('danger')
                                ->onIcon(Enabled::HABILITADO->getIcon())
                                ->offIcon(Enabled::DESHABILITADO->getIcon())
                                ->disabled()
                                ->dehydrateStateUsing(fn (bool $state) => $state ? 1 : 2) // Al guardar: true => 1, false => 2
                                ->afterStateHydrated(function (Forms\Components\Toggle $component, $state) {
                                    $component->state($state === 1); // Al cargar: 1 => true, 2 => false
                                }),
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
                    // ->searchable(),
                Tables\Columns\TextColumn::make('profileTransactions.role.name')
                    ->label('Roles locales')
                    ->limit(15)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('enabled')
                    ->label('Habilitado')
                    ->icon(fn ($state) => Enabled::from($state)->getIcon())
                    ->color(fn ($state) => Enabled::from($state)->getColor()),
                Tables\Columns\TextColumn::make('certification')
                    ->label("Certificación")
                    ->badge()
                    ->formatStateUsing(fn ($state) => Certification::from($state)->getLabel())
                    ->color(fn ($state) => Certification::from($state)->getColor())
                    ->sortable()
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

                SelectFilter::make('certification')
                    ->label('Certificación')
                    ->options(Certification::class)
                    ->attribute('certification'),

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
