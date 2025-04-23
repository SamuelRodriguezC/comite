<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Enums\Level;
use Filament\Tables;
use App\Models\Option;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OptionResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OptionResource\RelationManagers;

class OptionResource extends Resource
{
    protected static ?string $model = Option::class;
    protected static ?string $modelLabel = "Opción de grado";
    protected static ?string $pluralModelLabel = "Opciones de grado";
    protected static ?string $navigationGroup = "Administrativo";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('option')
                    ->label("Opción de grado")
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('level')
                    ->label("Nivel Universitario")
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('component')
                    ->label("Componente")
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('description')
                    ->label("Descripción")
                    ->required()
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('option')
                    ->label("Opción de grado")
                    ->searchable(),
                Tables\Columns\TextColumn::make('level')
                    ->label("Nivel Universitario")
                    ->formatStateUsing(fn ($state) => Level::from($state)->getLabel())
                    ->sortable(),
                Tables\Columns\TextColumn::make('component')
                    ->label("Componente")
                    ->formatStateUsing(fn ($state) => Component::from($state)->getLabel())
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label("Descripción")
                    ->searchable(),
                Tables\Columns\TextColumn::make('requirement')
                    ->label("Requisitos")
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOptions::route('/'),
            'create' => Pages\CreateOption::route('/create'),
            'view' => Pages\ViewOption::route('/{record}'),
            'edit' => Pages\EditOption::route('/{record}/edit'),
        ];
    }
}
