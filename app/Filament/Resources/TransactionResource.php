<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\Enabled;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use App\Enums\Certification;
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
    protected static ?string $modelLabel = "Transacción";
    protected static ?string $pluralModelLabel = "Transacciones";
    protected static ?string $navigationGroup = "Etapas";
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FormSection::make('Transacción')

                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->label('Ticket #')
                            ->disabled()
                            ->numeric()
                            ->visibleOn('edit'),
                        Forms\Components\Select::make('component')
                            ->label("Componente")
                            ->live()
                            ->preload()
                            ->enum(Component::class)
                            ->options(Component::class),
                        Forms\Components\Select::make('option_id')
                            ->label("Opción de Grado")
                            ->relationship('option', 'option')
                            ->required(),
                    ])
                    ->columnSpan(1)
                    ->icon('heroicon-m-ticket'),

                FormSection::make('Agregar Integrante')
                    ->schema([
                        Forms\Components\Select::make('profile_id')
                            ->label('Documento del Integrante')
                            ->visibleOn('create')
                            ->searchable()
                            ->getSearchResultsUsing(fn (string $search) =>
                                \App\Models\Profile::where('document_number', 'like', "%{$search}%")
                                    ->limit(10)
                                    ->pluck('document_number', 'id')
                            )
                            ->getOptionLabelUsing(fn ($value) =>
                                \App\Models\Profile::find($value)?->document_number
                            )
                            ->required(),
                        Forms\Components\Select::make('courses_id')
                            ->label('Carrera')
                            ->visibleOn('create')
                            ->options(\App\Models\Course::all()->pluck('course', 'id')) // Mostrar los cursos de la tabla
                            ->searchable()
                            ->required(),
                    ])
                    ->columnSpan(1)
                    ->description('Para Crear una Transacción debes seleccionar un Primer Integrante y su Carrera. Luego puedes agregar más integrantes.')
                    ->icon('heroicon-m-user-circle')
                    ->visible(fn (string $context) => $context === 'create'), //Solo es visible al crear (Sección)


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
                            Forms\Components\Select::make('certification')
                                ->label("Certificación")
                                ->live()
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
                ->options([
                    '1' => 'Investigativo',
                    '2' => 'No Investigativo',
                ])
                ->attribute('component')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            InfoSection::make(fn ($record) => 'Transacción #' . $record->id)
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
