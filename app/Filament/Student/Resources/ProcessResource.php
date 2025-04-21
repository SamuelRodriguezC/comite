<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\ProcessResource\Pages;
use App\Filament\Student\Resources\ProcessResource\RelationManagers;
use App\Models\Process;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProcessResource extends Resource
{
    protected static ?string $model = Process::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('requeriment')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('state')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('comment')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('transaction_id')
                    ->relationship('transaction', 'id')
                    ->required(),
                Forms\Components\Select::make('stage_id')
                    ->relationship('stage', 'id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('requeriment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stage.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListProcesses::route('/'),
            'create' => Pages\CreateProcess::route('/create'),
            'view' => Pages\ViewProcess::route('/{record}'),
            'edit' => Pages\EditProcess::route('/{record}/edit'),
        ];
    }
}
