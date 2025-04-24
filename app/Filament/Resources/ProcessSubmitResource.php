<?php

namespace App\Filament\Resources;

use App\Enums\State;
use App\Filament\Resources\ProcessSubmitResource\Pages;
use App\Filament\Resources\ProcessSubmitResource\RelationManagers;
use App\Models\Process;
use App\Models\ProcessSubmit;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProcessSubmitResource extends Resource
{
    protected static ?string $model = Process::class;
    protected static ?string $modelLabel = "Entrega";
    protected static ?string $pluralModelLabel = "Entregas";
    protected static ?string $navigationGroup = "Etapas";
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';
    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('stage_id', 2);
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('stage_id')
                    ->label("Etapa")
                    ->relationship('stage', 'stage')
                    ->required(),
                Forms\Components\Select::make('state')
                    ->label('Estado')
                    ->live()
                    ->preload()
                    ->enum(State::class)
                    ->options(State::class)
                    ->required(),
                Forms\Components\Textarea::make('comment')
                    ->label("Comentario")
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('transaction_id')
                    ->label("Número transacción")
                    ->relationship('transaction', 'id')
                    ->required(),
                Forms\Components\TextInput::make('requirement')
                    ->label("Requisitos")
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction.id')
                    ->label("Número de Transacción")
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stage.stage')
                    ->label("Etapa")
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->label("Estado")
                    ->formatStateUsing(fn ($state) => State::from($state)->getLabel())
                    ->sortable(),
                Tables\Columns\TextColumn::make('requirement')
                    ->label("requisitos")
                    ->searchable(),
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
            Section::make('')
                ->columnSpan(2)
                ->columns(2)
                ->schema([
                    TextEntry::make('stage.stage')
                        ->label("Etapa"),
                    TextEntry::make('state')
                        ->label("Estado")
                        ->formatStateUsing(fn ($state) => State::from($state)->getLabel()),
                    TextEntry::make('requirement')
                        ->label("requisitos"),
                    TextEntry::make('transaction.id')
                        ->label("Número de Transacción"),
                    TextEntry::make('created_at')
                        ->dateTime()
                        ->label('Creado en'),
                    TextEntry::make('update_at')
                        ->dateTime()
                        ->label('Actualizado en'),
                ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProcessSubmits::route('/'),
            'create' => Pages\CreateProcessSubmit::route('/create'),
            'view' => Pages\ViewProcessSubmit::route('/{record}'),
            'edit' => Pages\EditProcessSubmit::route('/{record}/edit'),
        ];
    }
}
