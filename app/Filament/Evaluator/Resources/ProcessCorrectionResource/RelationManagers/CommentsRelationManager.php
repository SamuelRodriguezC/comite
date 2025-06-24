<?php

namespace App\Filament\Evaluator\Resources\ProcessCorrectionResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $title = 'Comentarios';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('concept_id')
                    ->label('Concepto')
                    ->required()
                    ->relationship('concept', 'concept'),
                Forms\Components\RichEditor::make('comment')
                    ->label('Tu Comentario')
                    ->required()
                    ->disableToolbarButtons(['attachFiles', 'link', 'strike', 'codeBlock', 'h2', 'h3', 'blockquote'])
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            // ->recordTitleAttribute('comment')
            ->description('Para que el "Estado" del proceso sea aprobado todos los conceptos de los comentarios deben ser APROBADOS, si al menos uno de los conceptos es NO APROBADO el proceso será improbado.')
           // Mostrar registros en orden descendente por fecha de creación
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('concept.concept')
                    ->label('Concepto Individual')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Aprobado' => 'success',
                        'No aprobado' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Comentario')
                    ->markdown()
                    ->limit(20),
                Tables\Columns\TextColumn::make('profile.name')
                    ->label('Nombre')
                     // Función para mostrar el texto "Tú" junto al nombre si el perfil del comentario es el mismo que el del usuario autenticado
                    ->formatStateUsing(function ($state, $record) {
                        $userProfileId = Auth::user()?->profiles?->id;
                        // Mostrar en la columna nombre Tú en caso de que sea el perfil autenticado
                        return $state . ($record->profile_id === $userProfileId ? ' (Tú)' : '');
                    }),
                Tables\Columns\TextColumn::make('profile.last_name')
                    ->label('Apellido')
                    ->limit(10),
                Tables\Columns\TextColumn::make('profile.user.email')
                    ->label('Correo')
                    ->limit(20)

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label("Nuevo comentario")
                    ->disableCreateAnother() // <-- Desactiva el botón "Crear y crear otro"
                    ->mutateFormDataUsing(function (array $data): array {
                        // Guardar en el campo profile_id el id del perfil del usuario en sesión al hacer un comentario
                        $data['profile_id'] = Auth::user()->profiles->id;
                        return $data;

                    })
                    // El botón crear solo se mostrará si el usuario no ha comentado
                    ->visible(function () {
                        $user = Auth::user();
                        $profileId = $user?->profiles?->id;
                        $processId = $this->getOwnerRecord()->id;

                        return !\App\Models\Comment::where('process_id', $processId)
                            ->where('profile_id', $profileId)
                            ->exists();
                    }),
            ])

            ->actions([
                Tables\Actions\EditAction::make()
                    // Luego de editar un comentario, actualiza el estado del proceso
                    ->after(function (\App\Models\Comment $record, array $data) {
                        \App\Models\Comment::updateProcessState($record->process);
                    }),
                //  Luego de eliminar un comentario, actualiza el estado del proceso
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Eliminar Comentario')
                    ->after(function (\App\Models\Comment $record) {
                        \App\Models\Comment::updateProcessState($record->process);
                    }),
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
                            ])->columns(2)->columnSpan(3),

                            Section::make([
                                TextEntry::make('comment')
                                    ->label('Comentario')
                                    ->markdown(),
                            ])->columnSpan(2),

                            Section::make([
                                TextEntry::make('concept.concept')
                                    ->label('Concepto')
                                    ->badge()
                                    ->color(fn ($state) => match ($state) {
                                        'Aprobado' => 'success',
                                        'No aprobado' => 'danger',
                                    }),
                            ])->columnSpan(1),



                        ])->columns(3)
                        ->record($record)),// El $record aquí viene del modelo actual en la tabla

            ])
            ->bulkActions([
                //
            ]);
    }
}
