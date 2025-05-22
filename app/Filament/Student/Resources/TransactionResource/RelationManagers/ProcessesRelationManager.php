<?php

namespace App\Filament\Student\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use App\Enums\State;
use Filament\Tables;
use App\Enums\Enabled;
use App\Models\Comment;
use App\Enums\Completed;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ProcessesRelationManager extends RelationManager
{
    protected static string $relationship = 'Processes';
    protected static ?string $title = 'Procesos vinculados al Ticket';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('requirement')
                    ->label('Requisitos en PDF')
                    ->required()
                    ->disk('local')
                    ->directory('secure/requirements')
                    ->acceptedFileTypes(['application/pdf'])
                    ->rules([
                        'required',
                        'mimes:pdf',
                        'max:10240',
                    ])
                    ->maxSize(10240)
                    ->columnSpan(1)
                    ->columnSpanFull()
                    ->disabled(
                        fn (?Model $record) => filled($record?->requirement)
                    )
                    ->maxFiles(1),
                Forms\Components\RichEditor::make('comment')
                    ->label('Comentario de Entrega')
                    ->required()
                    ->columnSpanFull()
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
                    ->limit(20)
                    ->searchable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label("Comentario de Entrega")
                    ->placeholder('Sin Comentario Aún')
                    ->limit(30)
                    ->sortable()
                    ->markdown()
                    ->searchable(),
                Tables\Columns\IconColumn::make('completed')
                    ->label('Finalizado')
                    ->icon(
                        fn ($state) => Completed::from($state)
                            ->getIcon()
                    )
                    ->color(
                        fn ($state) => Completed::from($state)
                            ->getColor()
                    ),
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
                Tables\Actions\ViewAction::make()
                    ->label('Ver')
                    ->infolist(function ($record) {
                        return [
                            Section::make('Información del Proceso')
                                ->schema([
                                    TextEntry::make('transaction.id')
                                        ->label('# Ticket'),
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
                                        ->placeholder('No ha Subido Requisitos Aún')
                                        ->formatStateUsing(
                                            function ($state){
                                                if(!$state){return null;}
                                                return basename($state);
                                            }),
                                ])
                                ->columns(2),

                            Section::make('Comentarios de Evaluadores')
                                ->description('Ambos conceptos de los evaluadores deben ser "Aprobados" para que el Estado del proceso sea aprobado')
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
                                ->visible(
                                    fn ($record) => $record->comments->isNotEmpty()
                                ),
                        ];
                    }),

                    Tables\Actions\EditAction::make()
                        ->label('Completar')
                        ->icon('heroicon-o-document-arrow-up')
                        ->visible(fn ($record) =>
                            (!$record->requirement || trim($record->requirement) === '') ||
                            (!$record->comment || trim($record->comment) === '')
                        ),

                    ActionGroup::make([
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
                            ), // Solo se muestra si hay un archivo
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                ]),
            ]);
    }
}
