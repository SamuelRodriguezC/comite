<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Enums\State;
use Filament\Tables;
use App\Enums\Enabled;
use App\Models\Process;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Resources\ProcessAplicationResource\Pages;
use App\Filament\Resources\ProcessAplicationResource\RelationManagers;

class ProcessAplicationResource extends Resource
{
    protected static ?string $model = Process::class;
    protected static ?string $modelLabel = "Solicitud";
    protected static ?string $pluralModelLabel = "Solicitudes";
    protected static ?string $navigationGroup = "Etapas";
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?int $navigationSort = 3;


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('stage_id', 1);
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('stage_id')
                ->label("Etapa")
                ->relationship('stage', 'stage')
                ->visibleOn('create')
                ->required(),
            Forms\Components\Select::make('state')
                ->label('Estado')
                ->live()
                ->preload()
                // ->disabled()
                ->enum(state::class)
                ->options(State::class)
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
                    ->toggleable(), //Seleccionada por defecto
                    // ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state')
                    ->label("Estado")
                    ->badge()
                    ->color(fn ($state) => State::from($state)->getColor())
                    ->formatStateUsing(fn ($state) => State::from($state)->getLabel())
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
                //
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


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProcessAplications::route('/'),
            // 'create' => Pages\CreateProcessAplication::route('/create'),
            'view' => Pages\ViewProcessAplication::route('/{record}'),
            'edit' => Pages\EditProcessAplication::route('/{record}/edit'),
        ];
    }
}
