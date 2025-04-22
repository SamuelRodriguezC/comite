<?php

namespace App\Filament\Student\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\Enabled;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Student\Resources\TransactionResource\Pages;
use App\Filament\Student\Resources\TransactionResource\RelationManagers;
use Illuminate\Support\Facades\Auth;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $modelLabel = "Transacción";
    protected static ?string $pluralModelLabel = "Transacciones";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        // Obtén el perfil del usuario autenticado
        $profileId = Auth::user()->profiles->id;

        // Realiza la consulta para obtener las transacciones relacionadas con el perfil del usuario
        return Transaction::whereHas('profiles', function (Builder $query) use ($profileId) {
            $query->where('profile_id', $profileId);
        });
    }

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
                Forms\Components\Select::make('option_id')
                    ->label("Opción de grado")
                    ->relationship('Option', 'option')
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('enabled')
                    ->label("Habilitado")
                    ->formatStateUsing(fn ($state) => Enabled::from($state)->getLabel())
                    ->sortable(),
                Tables\Columns\TextColumn::make('Option.option')
                    ->label("Opción de grado")
                    ->sortable(),
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
                    TextEntry::make('component')
                        ->label('Componente')
                        ->formatStateUsing(fn ($state) => Component::from($state)->getLabel()),
                    TextEntry::make('enabled')
                        ->label('Habilitado')
                        ->formatStateUsing(fn ($state) => Enabled::from($state)->getLabel()),
                    TextEntry::make('Option.option')
                        ->label('Opción de grado'),
                    TextEntry::make('created_at')
                        ->label('Creado en'),
                    TextEntry::make('update_at')
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/{record}'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
