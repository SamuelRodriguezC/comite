<?php

namespace App\Filament\Evaluator\Resources;

use Filament\Forms;
use App\Enums\Level;
use Filament\Tables;
use App\Models\Option;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Group;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Evaluator\Resources\OptionResource\Pages;
use App\Filament\Evaluator\Resources\OptionResource\RelationManagers;

class OptionResource extends Resource
{
    protected static ?string $model = Option::class;
    protected static ?string $modelLabel = "Descripción Opción de grado";
    protected static ?string $pluralModelLabel = "Descripción Opciones de grado";
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('option')
                    ->label("Opción de grado")
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('level')
                    ->label('Nivel universitario')
                    ->live()
                    ->preload()
                    ->enum(Level::class)
                    ->options(Level::class)
                    ->required(),
                Forms\Components\Select::make('component')
                    ->label('Nivel universitario')
                    ->live()
                    ->preload()
                    ->enum(Component::class)
                    ->options(Component::class)
                    ->required(),
                Forms\Components\TextInput::make('description')
                    ->label("Descripción")
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('requirement')
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
                    ->formatStateUsing(function ($state){
                        return Str::limit($state, 25);
                    })
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
                    ->formatStateUsing(function ($state){
                        return Str::limit($state, 25);
                    })
                    ->label("Descripción")
                    ->searchable(),
                Tables\Columns\TextColumn::make('requirement')
                    ->label("Requisitos")
                    ->limit(25)
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

                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            InfoSection::make([
                TextEntry::make('option')
                    ->label('Opción de grado'),
                TextEntry::make('description')
                    ->label('Descripción'),
                TextEntry::make('requirement')
                    ->label('Requerimientos')
                    ->formatStateUsing(fn($state) =>
                        '<ul class="pl-8 list-disc list-inside">' .
                            collect(is_string($state) ? explode(',', $state) : $state) // Convierte string en array
                            ->map(fn($item) => "<li>$item</li>") // Pone cada elemento en un <li>
                            ->implode('') .
                        '</ul>'
                    )->html() // Permite HTML en la salida
                    ->columnSpan('full'), // Ocupa todo el ancho
            ])->columns(2)->columnSpan(2),
            InfoSection::make([
                Group::make([
                    TextEntry::make('level')
                        ->label('Nivel')
                        ->formatStateUsing(fn ($state) => Level::from($state)->getLabel()),
                    TextEntry::make('component')
                        ->label('Componente')
                        ->formatStateUsing(fn ($state) => Component::from($state)->getLabel()),
                    TextEntry::make('update_at')
                        ->dateTime()
                        ->label('Actualizado en')
                        ->columnSpan('full'),
                ])->columns(2),
            ])->columnSpan(1),
        ])->columns(3);
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
            // Url para crear deshabilitada (Puerta Trasera)
            //'create' => Pages\CreateOption::route('/create'),
            'view' => Pages\ViewOption::route('/{record}'),
            'edit' => Pages\EditOption::route('/{record}/edit'),
        ];
    }
}
