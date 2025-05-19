<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use App\Enums\Level;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ProfilesRelationManager extends RelationManager
{
    protected static string $relationship = 'profiles';
        protected static ?string $title = 'Perfil del Usuario';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('document_id')
                    ->label('Tipo de documento de identidad')
                    ->relationship('document', 'type')
                    ->required(),
                Forms\Components\TextInput::make('document_number')
                    ->label('Número de documento de identidad')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->label('Nombres')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('last_name')
                    ->label('Apellidos')
                    ->required()
                    ->maxLength(60),
                Forms\Components\TextInput::make('phone_number')
                    ->label('Número de telefono')
                    ->tel()
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('level')
                    ->label("Nivel Universitario")
                    ->label('Nivel universitario')
                    ->live()
                    ->preload()
                    ->enum(Level::class)
                    ->options(Level::class)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('document.type')
                    ->label('Tipo de documento'),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Documento'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre(s)'),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Apellido(s)'),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Telefono'),
                Tables\Columns\TextColumn::make('level')
                    ->label('Nivel Universitario')
                    ->formatStateUsing(fn ($state) => Level::from($state)->getLabel()),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->visible(fn () => $this->getOwnerRecord()->profiles()->count() === 0),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
