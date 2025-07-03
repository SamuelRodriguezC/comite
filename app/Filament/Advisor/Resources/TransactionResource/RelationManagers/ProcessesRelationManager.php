<?php

namespace App\Filament\Advisor\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use App\Enums\State;
use Filament\Tables;
use App\Models\Concept;
use App\Enums\Completed;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
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
                Forms\Components\RichEditor::make('comment')
                    ->label('Comentario de Entrega')
                    ->required()
                    ->disableToolbarButtons(['attachFiles', 'link', 'strike', 'codeBlock', 'h2', 'h3', 'blockquote'])
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('requirement')
                    ->label('Requisitos en PDF')
                    ->disk('local') // Indica que se usará el disco 'public'
                    ->directory('secure/requirements') // Define la ruta donde se almacenará el archivo
                    ->acceptedFileTypes(['application/pdf']) // Limita los tipos de archivo a PDF
                    ->rules([
                        'required',
                        'mimes:pdf',
                        'max:10240',
                    ]) // Agrega validación: campo requerido y solo PDF
                    ->maxSize(10240) // 10MB
                    ->columnSpanFull()
                    ->required()
                    ->maxFiles(1) ,
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
                    ->icon(
                        fn ($record) => Completed::from($record->completed)
                            ->getIcon()
                    )
                    ->color(
                        fn ($record) => Completed::from($record->completed)
                            ->getColor()
                    ),
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
            ->actions([
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
                                ->description('Ambos conceptos de los evaluadores deben ser "Aprobados" para que el Estado del proceso sea aprobado')
                                ->schema(
                                    $record->comments->map(function ($comment) {
                                        return Section::make()
                                            ->schema([
                                                TextEntry::make('profile.name')
                                                    ->label('Evaluador')
                                                    ->default(optional($comment->profile)->name ?? 'Desconocido'),
                                                TextEntry::make('comment.comment')
                                                    ->label('Comentario')
                                                    ->markdown()
                                                    ->default($comment->comment),
                                                TextEntry::make('concept.concept')
                                                    ->label('Concepto')
                                                    ->default(optional($comment->concept)->concept ?? 'Sin Concepto')
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
                    ->label('Subir')
                    ->modalHeading('Subir Requisitos')
                    ->icon('heroicon-o-document-arrow-up')
                    ->visible(function ($record) {
                        $hasNoRequirement = !$record->requirement || trim($record->requirement) === '';
                        $stillInTime = !$record->delivery_date || Carbon::now()->lessThan($record->delivery_date);
                        $notInFinalState = $record->state != 7;

                        return $hasNoRequirement && $stillInTime && $notInFinalState;
                }),


                // --------------------------- GRUPO DE BOTONES ---------------------------
                ActionGroup::make([
                    // --------------------------- VER REQUERIMIENTOS ---------------------------
                    Tables\Actions\Action::make('show')
                    ->label('Visualizar requerimiento')
                    ->icon('heroicon-o-eye') // Icono de ver
                    ->url(
                        fn ($record) => route('file.view', ['file' => basename($record->requirement)])
                    )
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => trim($record->requirement) !== ''), // Solo se muestra si hay un archivo

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
                        )
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                ]),
            ]);
    }
}
