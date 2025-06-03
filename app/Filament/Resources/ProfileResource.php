<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Enums\Level;
use Filament\Tables;
use App\Models\Profile;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Select;
use App\Filament\Resources\ProfileResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProfileResource\RelationManagers;

class ProfileResource extends Resource
{
    protected static ?string $model = Profile::class;
    //protected static ?string $label = 'Información de perfil';
    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $modelLabel = 'Perfil de usuario';
    protected static ?string $pluralModelLabel = 'Perfiles de usuarios';
    protected static ?int $navigationSort = 3;
    //protected static ?string $slug = 'perfiles';
    //protected static ?string $navigationLabel = '1. Información de perfil';

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
                    ->label('Tipo de documento de identidad')
                    ->relationship('document', 'type')
                    ->required(),
                Forms\Components\TextInput::make('document_number')
                    ->label('Número de documento de identidad')
                    ->required()
                    ->numeric(),
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
                Forms\Components\Select::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->disabled()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label("Nombres")
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label("Apellidos")
                    ->searchable(),
                Tables\Columns\TextColumn::make('document_number')
                    ->label("Número documento")
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label("Número telefono")
                    // ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('level')
                    ->label("Nivel universitario")
                    ->formatStateUsing(fn ($state) => Level::from($state)->getLabel())
                    ->sortable(),
                Tables\Columns\TextColumn::make('document.type')
                    ->label("Tipo de documento")
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label("Nombre de usuario")
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label("Creado en")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label("Actualización")
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
    /**
    * Lista de información de los detalles de usuario
    */
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
                ]),
            Section::make('Información académica')
                ->columnSpan(2)
                ->columns(2)
                ->schema([
                    TextEntry::make('level')
                        ->label('Nivel universitario')
                        ->formatStateUsing(fn ($state) => Level::from($state)->getLabel()),
                    //TextEntry::make('UniversityCourse.course')
                    //    ->label('Carrera universitaria'),
                    TextEntry::make('institutional_code')
                        ->label('Código institucional'),
                ]),
            Section::make('Información de autenticación')
                ->columnSpan(2)
                ->columns(2)
                ->schema([
                    TextEntry::make('User.email')
                        ->label('Email'),
                    TextEntry::make('User.created_at')
                        ->label('Registrado en'),
                    TextEntry::make('User.id')
                        ->label('Id de usuario'),
                    TextEntry::make('Role.name')
                        ->label('Rol')
                        ->formatStateUsing(fn ($state) => $state->getRoleNames()->implode(', ')),
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
            'view' => Pages\ViewProfile::route('/{record}'),
            'edit' => Pages\EditProfile::route('/{record}/edit'),
        ];
    }
}
