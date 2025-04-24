<?php

namespace App\Filament\Student\Resources;

use Filament\Forms;
use App\Enums\State;
use Filament\Tables;
use App\Models\Process;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Student\Resources\ProcessResource\Pages;
use App\Filament\Student\Resources\ProcessResource\RelationManagers;

class ProcessResource extends Resource
{
    protected static ?string $model = Process::class;
    protected static ?string $modelLabel = "Proceso";
    protected static ?string $pluralModelLabel = "Procesos";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('requirement')
                    ->label('Requerimientos en PDF')
                    ->disk('public') // Indica que se usará el disco 'public'
                    ->directory('processes/requirements') // Define la ruta donde se almacenará el archivo
                    ->acceptedFileTypes(['application/pdf']) // Limita los tipos de archivo a PDF
                    ->rules([
                        'required',
                        'mimes:pdf',
                        'max:10240',
                    ]) // Agrega validación: campo requerido y solo PDF
                    ->maxSize(10240) // 10MB
                    ->maxFiles(1) ,
                Forms\Components\Select::make('stage_id')
                    ->label("Etapa")
                    ->relationship('Stage', 'stage')
                    ->required(),
                // Funcionalidad para evaluadores
                //Forms\Components\Select::make('state')->label("Estado")->live()->preload()->enum(State::class)->options(State::class),
                Forms\Components\Textarea::make('comment')
                    ->label("Comentario")
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('transaction_id')
                    ->label("Transacción")
                    ->relationship('transaction', 'id')
                    ->required(),
            ]);
        }

        public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    Tables\Columns\TextColumn::make('requirement')
                        ->label("Requisitos")
                        ->searchable(),
                    Tables\Columns\TextColumn::make('state')
                        ->label("Estado")
                        ->formatStateUsing(fn ($state) => State::from($state)->getLabel())
                        ->sortable(),
                    Tables\Columns\TextColumn::make('transaction.id')
                        ->label("Transacción")
                        ->numeric()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('stage.stage')
                        ->label("Etapa")
                        ->sortable(),
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
                Section::make('')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        TextEntry::make('stage.stage')
                            ->label("Etapa"),
                        TextEntry::make('state')
                            ->label("Estado")
                            ->formatStateUsing(fn ($state) => State::from($state)->getLabel()),
                        TextEntry::make('requirement')
                            ->label("requisitos"),
                        TextEntry::make('transaction.id')
                            ->label("Número de Transacción"),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->label('Creado en'),
                        TextEntry::make('update_at')
                            ->dateTime()
                            ->label('Actualizado en'),
                    ]),
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
                'index' => Pages\ListProcesses::route('/'),
                'create' => Pages\CreateProcess::route('/create'),
                'view' => Pages\ViewProcess::route('/{record}'),
                'edit' => Pages\EditProcess::route('/{record}/edit'),
            ];
        }
    }
