<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\Enabled;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Resources\TransactionResource\RelationManagers;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\IconEntry;

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
                Forms\Components\Select::make('component')
                    ->label("Componente")
                    ->live()
                    ->preload()
                    ->enum(Component::class)
                    ->options(Component::class),
                Forms\Components\Select::make('enabled')
                    ->label("Habilitado")
                    ->live()
                    ->preload()
                    ->enum(Enabled::class)
                    ->options(Enabled::class),
                Forms\Components\Select::make('option_id')
                    ->label("Opción")
                    ->relationship('option', 'option')
                    ->required(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
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
            InfoSection::make([
                TextEntry::make('component')
                    ->label('Componente')
                    ->formatStateUsing(fn ($state) => Component::from($state)->getLabel()),

                TextEntry::make('Option.option')
                    ->label('Opción de grado'),

                TextEntry::make('profiles.name') // Campo para "Personas"
                    ->label('Personas')
                    ->formatStateUsing(fn($state) => format_list_html($state))
                    ->html(), // Permite HTML en la salida

                TextEntry::make('courses') // Campo para "Carreras"
                    ->label('Carreras')
                    ->formatStateUsing(fn($state) => format_list_html($state))
                    ->html(), // Permite HTML en la salida
            ])->columns(2)->columnSpan(2),

            InfoSection::make([
                Group::make([
                    TextEntry::make('created_at')
                        ->label('Creado en'),
                    TextEntry::make('updated_at')
                    ->label('Actualizado en'),
                    IconEntry::make('enabled')
                        ->label('Habilitado')
                        ->icon(fn ($state) => Enabled::from($state)->getIcon())
                        ->color(fn ($state) => Enabled::from($state)->getColor()),

                ])->columns(2),
            ])->columnSpan(1),


        ])->columns(3);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProfileRelationManager::class,
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
