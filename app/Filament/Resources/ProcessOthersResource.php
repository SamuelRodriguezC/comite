<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Enums\State;
use Filament\Tables;
use App\Enums\Enabled;
use App\Models\Process;
use App\Enums\Completed;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\ProcessOthers;
use Filament\Actions\ViewAction;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProcessOthersResource\Pages;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Resources\ProcessOthersResource\RelationManagers;

class ProcessOthersResource extends Resource
{
    protected static ?string $model = Process::class;
    protected static ?string $modelLabel = "Otros";
    protected static ?string $pluralModelLabel = "Otros";
    protected static ?string $navigationGroup = "Etapas";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('stage_id')
                ->label("Etapa")
                ->relationship('stage', 'stage')
                //->visibleOn('create')
                ->required(),
            Forms\Components\Select::make('state')
                ->label('Estado')
                ->live()
                // ->preload()
                //->disabled()
                ->enum(State::class)
                ->options(State::class)
                ->required(),
            Forms\Components\Select::make('completed')
                ->label('Finalizado')
                ->live()
                // ->preload()
                //->disabled()
                ->enum(Completed::class)
                ->options(Completed::class)
                ->required(),
            Forms\Components\Select::make('transaction_id')
                ->label("Número transacción")
                ->relationship('transaction', 'id')
                ->visibleOn('create')
                ->required(),
            Forms\Components\TextInput::make('requirement')
                ->disabled()
                ->label("Requisitos")
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('comment')
                ->label("Comentario del Estudiante")
                ->required(),
                // ->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction.id')
                    ->label("Transacción")
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stage.stage')
                    ->label("Etapa")
                    ->sortable()
                    ->toggleable(),
                    // ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state')
                    ->label("Estado")
                    ->badge()
                    ->color(fn ($state) => State::from($state)->getColor())
                    ->formatStateUsing(fn ($state) => State::from($state)->getLabel())
                    ->sortable(),
                Tables\Columns\IconColumn::make('completed')
                    ->label("Finalizado")
                    ->icon(fn ($state) => Completed::from($state)->getIcon())
                    ->color(fn ($state) => Completed::from($state)->getColor())
                    ->sortable(),
                Tables\Columns\TextColumn::make('requirement')
                    ->label("Requisitos")
                    ->formatStateUsing(function ($state){
                        return Str::limit($state, 20);
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction.Option.option')
                    ->label("Opción")
                    ->words(5)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction.component')
                    ->label("Componente")
                    ->formatStateUsing(fn ($state) => Component::from($state)->getLabel())
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('transaction.enabled')
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
                SelectFilter::make('state')
                ->label('Estado')
                ->options([
                    1 => 'Aprobado',
                    2 => 'Improbado',
                    3 => 'Pendiente',
                ]),
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
            InfoSection::make('Detalles del Proceso')
            ->schema([
                TextEntry::make('stage.stage')
                    ->label("Etapa"),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->label('Creado en'),
                TextEntry::make('state')
                    ->label("Estado")
                    ->badge()
                    ->formatStateUsing(fn ($state) => State::from($state)->getLabel())
                    ->color(fn ($state) => State::from($state)->getColor()),
                TextEntry::make('completed')
                    ->label("Finalizado")
                    ->formatStateUsing(fn ($state) => Completed::from($state)->getLabel()),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->label('Actualizado en'),
                TextEntry::make('requirement')
                    ->label("Requisitos"),
            ])->columns(2)->columnSpan(1),

            InfoSection::make('Transacción')
            ->schema([
                TextEntry::make('transaction.id')
                    ->label("Número de Transacción"),
                IconEntry::make('transaction.enabled')
                        ->label('Habilitado')
                        ->icon(fn ($state) => Enabled::from($state)->getIcon())
                        ->color(fn ($state) => Enabled::from($state)->getColor()),
                TextEntry::make('transaction.Option.option')
                        ->label('Opción de grado'),
                TextEntry::make('transaction.component')
                        ->label('Componente')
                        ->formatStateUsing(fn ($state) => Component::from($state)->getLabel()),
                TextEntry::make('transaction.profiles.name')
                        ->label('Integrante(s)')
                        ->formatStateUsing(fn($state) => format_list_html($state))
                        ->html(),
                TextEntry::make('transaction.courses')
                        ->label('Carrera(s)')
                        ->formatStateUsing(fn($state) => format_list_html($state))
                        ->html(),
            ])
            ->columns(2)->columnSpan(1),

        ])->columns(2);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
        ];
    }

    // Filtra por etapa finalizado, cancelado y aplazado
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereIn('stage_id', [5, 6, 7]);
    }

    // Filtra por solicitudes pendientes
    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->where('state', '3')->count();
    }

    // Describe el getNavigationBadge
    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Finalizados, cancelados y aplazados pendientes';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProcessOthers::route('/'),
            // 'create' => Pages\CreateProcessOthers::route('/create'),
            'view' => Pages\ViewProcessOthers::route('/{record}'),
            'edit' => Pages\EditProcessOthers::route('/{record}/edit'),
        ];
    }
}
