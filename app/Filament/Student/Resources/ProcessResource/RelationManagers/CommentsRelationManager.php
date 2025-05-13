<?php

namespace App\Filament\Student\Resources\ProcessResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'Comments';
    protected static ?string $title = 'Comentarios';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('comment')
                    ->label('Comentario')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('concept_id')
                    ->label('Concepto')
                    ->required()
                    ->relationship('concept', 'concept'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->description('Para que el "Estado" del proceso sea aprobado todos los conceptos de los comentarios deben ser APROBADOS, si al menos uno de los conceptos es NO APROBADO el proceso será improbado.')
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('concept.concept')
                    ->label('Concepto Individual')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Aprobado' => 'success',
                        'No aprobado' => 'danger',
                    }),
                TextColumn::make('comment')
                    ->label('Comentario')
                    ->markdown()
                    ->limit(20),
                Tables\Columns\TextColumn::make('profile.name')
                    ->label('Nombre'),
                Tables\Columns\TextColumn::make('profile.last_name')
                    ->label('Apellido')
                    ->formatStateUsing(function ($state){
                        return Str::limit($state, 10);
                    }),
                Tables\Columns\TextColumn::make('profile.user.email')
                    ->label('Correo')
                    ->formatStateUsing(function ($state){
                        return Str::limit($state, 20);
                    }),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])

            ->actions([
                // Botón para ver detalles de integrante
                Tables\Actions\ViewAction::make()
                    ->label('Ver')
                    // ->modalContentOnly() // Esta línea evita que Filament incluya los campos del formulario por defecto
                    ->modalHeading('Información Personal')
                    // Crear el modal con una infolista
                    ->infolist(fn ($record) => Infolist::make()
                        ->schema([
                            Section::make([
                                TextEntry::make('profile.name')->label('Nombre(s)'),
                                TextEntry::make('profile.last_name')->label('Apellido)(s)'),
                                TextEntry::make('profile.User.email')->label('Email'),
                                TextEntry::make('profile.phone_number')->label('Número de Teléfono'),
                            ])->columns(2),

                            Section::make([
                                TextEntry::make('comment')
                                    ->label('Comentario')
                                    ->markdown(),
                                TextEntry::make('concept.concept')
                                    ->label('Concepto')
                                    ->badge()
                                    ->color(fn ($state) => match ($state) {
                                        'Aprobado' => 'success',
                                        'No aprobado' => 'danger',
                                    }),
                            ])->columns(2),

                        ])->columns(2)
                        ->record($record)),// El $record aquí viene del modelo actual en la tabla

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->after(function () {
                            foreach ($this->getSelected() as $commentId) {
                                if ($comment = \App\Models\Comment::find($commentId)) {
                                    \App\Models\Comment::updateProcessState($comment->process);
                                }
                            }
                        }),
                ]),
            ]);
    }
}

