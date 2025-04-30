<?php

namespace App\Filament\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use App\Enums\State;
use Filament\Tables;
use App\Enums\Completed;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
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
                    ->disk('public')
                    ->directory('processes/requirements')
                    ->acceptedFileTypes(['application/pdf'])
                    ->rules([
                        'required',
                        'mimes:pdf',
                        'max:10240',
                    ])
                    ->maxSize(10240) // 10MB
                    ->maxFiles(1) ,
                Forms\Components\TextInput::make('comment')
                    ->label('Comentario')
                    ->required()
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
                    ->color(fn ($state) => State::from($state)->getColor())
                    ->formatStateUsing(fn ($state) => State::from($state)->getLabel())
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label("Comentario")
                    ->placeholder('Sin Comentario Aún')
                    ->formatStateUsing(function ($state){
                        return Str::limit($state, 20);
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('completed')
                    ->label('Finalizado')
                    ->icon(fn ($state) => Completed::from($state)->getIcon())
                    ->color(fn ($state) => Completed::from($state)->getColor()),
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
                    // ->modalContentOnly() // Esta línea evita que Filament incluya los campos del formulario por defecto
                    ->modalHeading('Información Personal')
                    // Crear el modal con una infolista
                    ->modalContent(fn ($record) => Infolist::make()
                        ->schema([
                            Section::make([
                                TextEntry::make('transaction.id')->label('# Ticket'),
                                TextEntry::make('id')->label('# Proceso'),
                                TextEntry::make('state')
                                    ->label('Estado')
                                    ->badge()
                                    ->formatStateUsing(fn ($state) => State::from($state)->getLabel())
                                    ->color(fn ($state) => State::from($state)->getColor()),
                                TextEntry::make('stage.stage')->label('Etapa'),
                                TextEntry::make('requirement')->label('Requisitos'),
                                TextEntry::make('comment')->label('Tu Comentario'),
                            ])->columns(2),

                            Section::make([
                                TextEntry::make('comment')
                                    ->label('Comentario'),
                                TextEntry::make('concept.concept')
                                    ->label('Concepto')
                                    ->badge()
                                    ->color(fn ($state) => match ($state) {
                                        'Aprobado' => 'success',
                                        'No aprobado' => 'danger',
                                    }),
                            ]),
                        ])
                        ->record($record)),// El $record aquí viene del modelo actual en la tabla
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
