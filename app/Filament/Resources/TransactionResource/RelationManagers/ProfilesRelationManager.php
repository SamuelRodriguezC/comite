<?php

namespace App\Filament\Resources\TransactionResource\RelationManagers;

use App\Enums\Level;
use App\Models\Course;
use Filament\Forms;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProfilesRelationManager extends RelationManager
{
    protected static string $relationship = 'Profiles';
    protected static ?string $title = 'Integrante(s) de la Transacción';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\TextInput::make('document_number')
                        ->label('Documento')
                        ->required()
                        ->maxLength(255)
                        ->visibleOn('create'),

                    Select::make('courses_id')
                        ->label('Curso')
                        ->options(Course::all()->pluck('course', 'id'))
                        ->searchable()
                        ->required()
                        ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('document_number') //Atributo de busqueda
            ->columns([
                Tables\Columns\TextColumn::make('document_number')->label('Documento'),
                Tables\Columns\TextColumn::make('name')->label('Nombres'),
                Tables\Columns\TextColumn::make('last_name')->label('Apellidos'),
                Tables\Columns\TextColumn::make('phone_number')->label('Telefono'),
                Tables\Columns\TextColumn::make('pivot.courses_id')->label('Carrera')
                    ->words(3)
                    // Transformar el ID del curso a su nombre
                    ->formatStateUsing(function ($state) {
                        return \App\Models\Course::find($state)?->course ?? 'Curso no encontrado';
                }),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('courses_id')
                            ->label('Curso')
                            ->options(Course::all()->pluck('course', 'id'))
                            ->searchable()
                            ->required(),
                    ]),
            ])
            ->actions([
                // Botón para ver detalles de integrante
                Tables\Actions\ViewAction::make()
                ->label('Ver')
                ->modalHeading('Información del Integrante')
                // Crear el modal con una infolista
                ->modalContent(fn ($record) => Infolist::make()
                    ->schema([
                        Section::make('Información Personal')
                            ->schema([
                                TextEntry::make('name')->label('Nombre'),
                                TextEntry::make('last_name')->label('Apellido'),
                                TextEntry::make('User.email')->label('Email'),
                                TextEntry::make('phone_number')->label('Número de Teléfono'),
                            ])->columns(2)->columnSpan(2),

                            Section::make([
                                TextEntry::make('level')->label('Nivel Universitario')->formatStateUsing(fn ($state) => Level::from($state)->getLabel()),
                            ])->columnSpan(1)

                    ])->columns(3)
                    ->record($record)),// El $record aquí viene del modelo actual en la tabla


                Tables\Actions\EditAction::make(),

                Tables\Actions\DetachAction::make(),

            ])
            ->emptyStateActions([
                Tables\Actions\AttachAction::make(),
            ])
            ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make(),
                    ]),
                ]);
    }



}

