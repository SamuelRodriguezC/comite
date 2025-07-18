<?php

namespace App\Filament\Evaluator\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use App\Enums\State;
use Filament\Tables;
use App\Models\Stage;
use App\Models\Concept;
use App\Enums\Completed;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ProcessesRelationManager extends RelationManager
{
    protected static string $relationship = 'processes';
    protected static ?string $title = 'Procesos vinculados a la Opción';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('stage_id')
                    ->label("Etapa")
                    ->options(function ($livewire) {
                        // Obtener IDs de etapas ya utilizadas en esta transacción
                        $usedStageIds = $livewire->ownerRecord
                            ->processes()
                            ->pluck('stage_id')
                            ->toArray();

                        // Traer solo las etapas que NO están en esa lista
                        return Stage::whereNotIn('id', $usedStageIds)
                            ->orderBy('stage')
                            ->get()
                            ->pluck('stage', 'id')
                            ->mapWithKeys(
                                fn ($stage, $id) => [$id => "#{$id} - {$stage}"]
                            );
                    })
                    ->columnSpanFull()
                     ->visibleOn('create')
                    ->required(),

                    Forms\Components\DateTimePicker::make('delivery_date')
                        ->label('Fecha Límite de Entrega')
                        ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label("# Proceso")
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stage.stage')
                    ->label("Etapa")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->label("Estado")
                    ->badge()
                    ->color(
                        fn ($state) => State::from($state)
                            ->getColor()
                    )
                    ->formatStateUsing(
                        fn ($state) => State::from($state)
                            ->getLabel()
                    )
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('requirement')
                    ->label("Requisitos")
                    ->placeholder('Sin Archivos Aún')
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return null;
                        }
                        // Solo tomar el nombre del archivo, quitando el directorio
                        return basename($state);
                    })
                    ->limit(10)
                    ->searchable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label("Comentario Entrega")
                    ->markdown()
                    ->placeholder('Sin Comentario Aún')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\IconColumn::make('completed')
                    ->label('Finalizado')
                    ->icon(fn ($record) => Completed::from($record->completed)->getIcon())
                    ->color(fn ($record) => Completed::from($record->completed)->getColor()),
                Tables\Columns\TextColumn::make('delivery_date')
                    ->label("Limite de Entrega")
                    ->placeholder('Sin fecha Establecida')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear Proceso')
                    ->modalHeading('Crear Nuevo Proceso')
                    ->disableCreateAnother()
                     ->visible(function ($livewire) {
                        $usedStageIds = $livewire->ownerRecord
                            ->processes()
                            ->pluck('stage_id')
                            ->toArray();

                        $remainingStages = Stage::whereNotIn('id', $usedStageIds)->exists();

                        return $remainingStages; // true si hay etapas disponibles, false si no
                    })
                    ->using(
                        function ($data, $livewire) {
                            return $livewire->ownerRecord->processes()->create([
                                'stage_id' => $data['stage_id'],
                                'state' => 3,
                                'completed' => false,
                                'delivery_date' => $data['delivery_date'],
                                'requirement' => ' ',
                                'comment' => ' ',
                            ]);
                    }),
            ])
            ->actions([

                // --------------------------- GRUPO DE BOTONES ---------------------------
                ActionGroup::make([


                    // --------------------------- VER PROCESO ---------------------------
                Tables\Actions\ViewAction::make()
                    ->label('Ver')
                    ->infolist(function ($record) {
                        return [
                            Section::make('Información del Proceso')
                                ->schema([
                                    TextEntry::make('transaction.id')
                                        ->label('# Opción'),
                                    TextEntry::make('id')
                                        ->label('# Proceso'),
                                    TextEntry::make('state')
                                        ->label('Estado')
                                        ->badge()
                                        ->formatStateUsing(
                                            fn ($state) => State::from($state)
                                                ->getLabel()
                                        )
                                        ->color(
                                            fn ($state) => State::from($state)
                                                ->getColor()
                                        ),
                                    TextEntry::make('stage.stage')
                                        ->label('Etapa'),
                                    TextEntry::make('comment')
                                        ->label('Comentario de Entrega')
                                        ->placeholder('No ha Comentado Aún')
                                        ->markdown(),
                                    TextEntry::make('requirement')
                                        ->label('Requisitos')
                                        ->placeholder('No se han Subido Requisitos Aún')
                                        ->formatStateUsing(
                                            function ($state){
                                                if(!$state){return null;}
                                                return basename($state);
                                            }
                                        ),
                                    TextEntry::make('delivery_date')
                                        ->label('Limite de Entrega')
                                        ->placeholder('No se ha establecido fecha limite de entrega aún')
                                        ->dateTime(),
                                ])
                                ->columns(2),

                            Section::make('Comentarios de Evaluadores')
                                ->description('Recuerda que ambos conceptos de los evaluadores deben ser "Aprobados" para que el Estado del proceso sea aprobado')
                                ->schema(
                                    $record->comments->map(function ($comment) {
                                        return Section::make()
                                            ->schema([
                                                TextEntry::make('profile.name')
                                                    ->label('Evaluador')
                                                    ->default(
                                                        optional($comment->profile)->name ?? 'Desconocido'
                                                    ),
                                                TextEntry::make('comment.comment')
                                                    ->label('Comentario')
                                                    ->markdown()
                                                    ->default($comment->comment),
                                                TextEntry::make('concept.concept')
                                                    ->label('Concepto')
                                                    ->default(
                                                        optional($comment->concept)->concept ?? 'Sin Concepto'
                                                    )
                                                    ->badge()
                                                    ->color(
                                                        fn () => match ($comment->concept->concept ?? null) {
                                                            'Aprobado' => 'success',
                                                            'No aprobado' => 'danger',
                                                            default => 'gray',
                                                        }
                                                    ),
                                            ])
                                            ->columns(3);
                                    })->toArray()
                                )
                                ->visible(fn ($record) => $record->comments->isNotEmpty()),
                        ];
                    }),


                // --------------------------- Boton para FINALIZAR  ---------------------------
              Tables\Actions\Action::make('toggleCompleted')
                ->label(fn ($record) => $record->completed ? 'No Finalizado' : 'Finalizado')
                ->icon(fn ($record) => $record->completed ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                ->requiresConfirmation()
                ->modalHeading('Confirmar cambio')
                ->modalDescription('¿Quieres cambiar el estado de finalización de este proceso?')
                ->action(function ($record) {
                    $record->update([
                        'completed' => !$record->completed,  // simplemente invierte el booleano
                    ]);
                }),

                    // --------------------------- COMENTAR ---------------------------
                    Tables\Actions\Action::make('comentar')
                    ->label(function ($record) {
                        // Verificar si el perfil ya tiene un comentario en este proceso
                        $existingComment = $record->comments()
                            ->where('profile_id', Auth::user()->profiles->id)
                            ->first();

                        // Cambiar el label dependiendo si es crear o editar
                        return $existingComment ? 'Editar Comentario' : 'Comentar';
                    })
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->disabled(
                        function ($record) {
                            return !in_array($record->state, [3, 1, 2, 6, 7]);
                        }
                    )
                    ->form(function ($record) {
                        // Verificar si el perfil ya tiene un comentario en este proceso
                        $existingComment = $record->comments()
                            ->where('profile_id', Auth::user()->profiles->id)
                            ->first();

                        // Si existe un comentario, precargar los datos del comentario y el concepto
                        if ($existingComment) {
                            return [
                                Forms\Components\RichEditor::make('comment')
                                    ->label('Comentario')
                                    ->required()
                                    ->disableToolbarButtons([
                                        'attachFiles',
                                        'link',
                                        'strike',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'blockquote'
                                    ])
                                    ->maxLength(255)
                                    ->default($existingComment->comment), // Cargar el comentario actual

                                Forms\Components\Select::make('concept_id')
                                    ->label('Concepto')
                                    ->required()
                                    ->options(Concept::pluck('concept', 'id'))
                                    ->default($existingComment->concept_id), // Cargar el concepto actual
                            ];
                        } else {
                            // Si no existe un comentario, mostrar los campos vacíos
                            return [
                                Forms\Components\RichEditor::make('comment')
                                    ->label('Comentario')
                                    ->required()
                                    ->disableToolbarButtons([
                                        'attachFiles',
                                        'link',
                                        'strike',
                                        'codeBlock',
                                        'h2',
                                        'h3',
                                        'blockquote'
                                    ])
                                    ->maxLength(255),
                                Forms\Components\Select::make('concept_id')
                                    ->label('Concepto')
                                    ->required()
                                    ->options(Concept::pluck('concept', 'id')),
                            ];
                        }
                    })
                    ->action(function ($data, $record) {
                        // Verificar si el perfil ya tiene un comentario en este proceso
                        $existingComment = $record->comments()
                            ->where('profile_id', Auth::user()->profiles->id)
                            ->first();

                        if ($existingComment) {
                            // Si ya existe un comentario, actualizarlo
                            $existingComment->update([
                                'comment' => $data['comment'],
                                'concept_id' => $data['concept_id'],
                            ]);
                        } else {
                            // Si no existe un comentario, crear uno nuevo
                            $record->comments()->create([
                                'comment' => $data['comment'],
                                'concept_id' => $data['concept_id'],
                                'profile_id' => Auth::user()->profiles->id,
                            ]);
                        }
                    })
                    ->modalHeading(function ($record) {
                        // Cambiar el encabezado del modal dependiendo si es crear o editar
                        $existingComment = $record->comments()
                            ->where('profile_id', Auth::user()->profiles->id)
                            ->first();
                        return $existingComment ? 'Editar Comentario' : 'Agregar Comentario';
                    })
                    ->modalSubmitActionLabel(function ($record) {
                        // Cambiar el texto del botón de envío dependiendo si es crear o editar
                        $existingComment = $record->comments()
                            ->where('profile_id', Auth::user()->profiles->id)
                            ->first();
                        return $existingComment ? 'Actualizar' : 'Guardar';
                    })
                    ->modalCancelActionLabel('Cancelar'),

                    // --------------------------- VER REQUERIMIENTOS ---------------------------
                    Tables\Actions\Action::make('show')
                    ->label('Visualizar requerimiento')
                    ->icon('heroicon-o-eye') // Icono de ver
                    ->url(
                        fn ($record) => route('file.view', ['file' => basename($record->requirement)])
                    )
                    ->openUrlInNewTab()
                    ->visible(
                        fn ($record) => trim($record->requirement) !== ''
                    ), // Solo se muestra si hay un archivo

                    // --------------------------- DESCARGAR REQUERIMIENTOS ---------------------------
                    Tables\Actions\Action::make('download')
                        ->icon('heroicon-o-folder-arrow-down') // Icono de descarga
                        ->label('Descargar requerimiento')
                        ->url(
                            fn ($record) => route('file.download', ['file' => basename($record->requirement)])
                        )
                        ->openUrlInNewTab()
                        ->visible(
                            fn ($record) => trim($record->requirement) !== ''
                        ),

                    Tables\Actions\EditAction::make()
                        ->label('Editar Proceso')
                        ->requiresConfirmation() // Esto activa el modal de confirmación
                        ->modalHeading('Editar Proceso')
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                ]),
            ]);
    }
}
