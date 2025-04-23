<?php

namespace App\Filament\Advisor\Resources;

use Filament\Forms;
use App\Enums\Level;
use Filament\Tables;
use App\Models\Profile;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Advisor\Resources\ProfileResource\Pages;
use App\Filament\Advisor\Resources\ProfileResource\RelationManagers;

class ProfileResource extends Resource
{
    protected static ?string $model = Profile::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = "Perfil";
    protected static ?string $pluralModelLabel = "Perfiles";

    // Función para consultar solo los registros del usuario autenticado
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id', Auth::user()->profiles->id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombres')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->label('Apellidos')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('document_id')
                    ->label('Tipo de documento')
                    ->relationship('document', 'id')
                    ->required(),
                Forms\Components\TextInput::make('document_number')
                    ->label('Número de documento')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('phone_number')
                    ->label('Número de telefono')
                    ->tel()
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('level')
                    ->label('Nivel universitario')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('user_id')
                    ->label('User_id')
                    ->relationship('user', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombres')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Apellidos')
                    ->searchable(),
                Tables\Columns\TextColumn::make('document.type')
                    ->label('Tipo de documento')
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('Número de documento')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Número de telefono')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->label('Nivel universitario')
                    ->formatStateUsing(fn ($state) => Level::from($state)->getLabel())
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado en')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado en')
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
            Section::make('Información personal')
                ->columnSpan(2)
                ->columns(2)
                ->schema([
                    TextEntry::make('name')
                        ->label('Nombres'),
                    TextEntry::make('last_name')
                        ->label('Apellidos'),
                    TextEntry::make('Document.type')
                        ->label('Tipo de documento'),
                    TextEntry::make('document_number')
                        ->label('Número de documento'),
                    TextEntry::make('phone_number')
                        ->label('Número de telefono'),
                    TextEntry::make('level')
                        ->label('Nivel académico')
                        ->formatStateUsing(fn ($state) => Level::from($state)->getLabel()),
                ]),
            Section::make('Información de autenticación')
                ->columnSpan(2)
                ->columns(2)
                ->schema([
                    TextEntry::make('User.email')
                        ->label('Email'),
                    TextEntry::make('User.created_at')
                        ->dateTime()
                        ->label('Registrado en'),
                    TextEntry::make('User.id')
                        ->label('Id de usuario'),
                    //TextEntry::make('User.roles')
                    //    ->label('Rol')
                    //    ->formatStateUsing(fn ($record) => $record->user
                    //    ? $record->user->getRoleNames()->implode(', ')
                    //    : 'Sin rol asignado')
                ]),
            //TextEntry::make('user_id')
            //        ->label('ID detalles de usuario'),
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
            'index' => Pages\ListProfiles::route('/'),
            'create' => Pages\CreateProfile::route('/create'),
            'view' => Pages\ViewProfile::route('/{record}'),
            'edit' => Pages\EditProfile::route('/{record}/edit'),
        ];
    }


}
