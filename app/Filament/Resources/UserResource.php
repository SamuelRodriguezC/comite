<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Enums\Level;
use App\Models\User;
use Filament\Tables;
use App\Enums\Enabled;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Infolists\Components\InfoGroup;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Filament\Infolists\Components\Section as InfoSection;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    //protected static ?string $label = 'Información de perfil';
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationGroup = "Gestión de Usuarios";
    protected static ?string $modelLabel = 'Usuario';
    protected static ?string $pluralModelLabel = 'Usuarios';
    protected static ?int $navigationSort = 8;
    //protected static ?string $slug = 'perfiles';
    //protected static ?string $navigationLabel = '1. Información de perfil';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->rules([
                        fn ($get, $record) => Rule::unique('users', 'email')->ignore($record?->id),
                    ]),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                // Utiliza CheckboxList para asignarle roles a los usuarios
                Forms\Components\CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->required()
                    ->columns(2),
                Group::make([

                TextInput::make('profiles_name')
                    ->label('Nombre(s)')
                    ->required(),
                TextInput::make('profiles_last_name')
                    ->label('Apellido(s)')
                    ->required(),
                Select::make('profiles_document_id')
                    ->label('Tipo de documento')
                    ->relationship('profiles.document', 'type')
                    ->required(),
                TextInput::make('profiles_document_number')
                    ->label('Número de documento')
                    ->numeric()
                    ->maxLength(10)
                    ->required(),
                TextInput::make('profiles_phone_number')
                    ->label('Teléfono')
                    ->maxLength(10)
                    ->numeric()
                    ->required(),
                Select::make('profiles_level')
                    ->label("Nivel Universitario")
                    ->live()
                    ->preload()
                    ->enum(Level::class)
                    ->options(Level::class)
                    ->required(),
            ])->columns(2)->columnSpanFull()->visibleOn('create')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->limit(21)
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
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
                Tables\Actions\ViewAction::make()->slideOver(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggleVerification')
                    ->label(fn ($record) => $record->email_verified_at ? 'Desverificar' : 'Verificar')
                    ->icon(fn ($record) => $record->email_verified_at ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn ($record) => $record->email_verified_at ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->modalHeading(fn ($record) => $record->email_verified_at ? '¿Desverificar correo?' : '¿Verificar correo?')
                    ->action(function ($record) {
                        $record->email_verified_at = $record->email_verified_at ? null : now();
                        $record->save();
                    }),
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
            InfoSection::make('Información De Usuario')
                ->icon('heroicon-o-user')
                ->schema([
                    TextEntry::make('id')
                        ->label('Número de ID'),
                    TextEntry::make('created_at')
                        ->dateTime()
                        ->label('Creado En:'),
                    TextEntry::make('roles.name')
                        ->label('Rol(es)'),
                    TextEntry::make('email_verified_at')
                        ->dateTime()
                        ->placeholder('Usuario Sin Verficar')
                        ->label('Verficado En'),
                    TextEntry::make('email')
                        ->label('Correo'),

                ])
                ->columns(2)->columnSpanFull(),

            InfoSection::make('Información de Perfil')
                ->icon('heroicon-o-identification')
                ->schema([
                    TextEntry::make('profiles.name')
                        ->label('Nombre(s)'),
                    TextEntry::make('profiles.last_name')
                        ->label('Apellido(s)'),
                    TextEntry::make('profiles.Document.type')
                        ->label('Tipo de documento'),
                    TextEntry::make('profiles.document_number')
                        ->label('Documento'),
                    TextEntry::make('profiles.phone_number')
                        ->label('Teléfono'),
                    TextEntry::make('profiles.level')
                        ->label('Nivel universitario')
                        ->formatStateUsing(fn ($state) => Level::from($state)->getLabel()),
                ])->columns(2)->columnSpan(3),
        ])->columns(3);
    }


    public static function getRelations(): array
    {
        return [
            RelationManagers\ProfilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
