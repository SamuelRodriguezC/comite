<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Signer;
use App\Enums\Seccional;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SignerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SignerResource\RelationManagers;

class SignerResource extends Resource
{
    protected static ?string $model = Signer::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $modelLabel = "Director Centro de Investigación";
    protected static ?string $pluralModelLabel = "Directores de Investigación";


   public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('first_name')
                ->label('Nombres')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('last_name')
                ->label('Apellidos')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('faculty')
                ->label('Facultad')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('seccional')
                ->label('Seccional')
                ->required()
                ->options(Seccional::class),

            Forms\Components\FileUpload::make('signature')
                ->label('Firma')
                ->disk('private')
                ->directory('signatures')
                ->image()
                ->imageEditor()
                ->required()
                ->previewable(true)
                ->downloadable(true),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Nombres')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Apellidos')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('faculty')
                    ->label('Facultad')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('seccional')
                    ->label('Seccional')
                    ->searchable()
                    // ->formatStateUsing()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('signature')
                    ->label('Firma')
                    ->height(70)
                    ->width(150)
                    ->getStateUsing(function ($record) {
                        if (!$record->signature) {
                            return null;
                        }
                        // Solo el nombre del archivo (quitar el '/signeatures')
                        $filename = basename($record->signature);
                        // Retornar la URL para mostrar la imagen
                        return route('signatures.show', $filename);
                    }),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSigners::route('/'),
            'create' => Pages\CreateSigner::route('/create'),
            'edit' => Pages\EditSigner::route('/{record}/edit'),
        ];
    }
}
