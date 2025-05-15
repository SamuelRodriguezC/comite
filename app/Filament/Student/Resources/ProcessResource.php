<?php

namespace App\Filament\Student\Resources;

use Filament\Forms;
use App\Enums\State;
use Filament\Tables;
use App\Enums\Enabled;
use App\Models\Process;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Student\Resources\ProcessResource\Pages;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Student\Resources\ProcessResource\RelationManagers;

class ProcessResource extends Resource
{
    protected static ?string $model = Process::class;
    protected static ?string $modelLabel = "Proceso";
    protected static ?string $pluralModelLabel = "Procesos";
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('requirement')
                    ->label('Requisitos en PDF')
                    ->disabled(
                        fn (?Model $record) => filled($record?->requirement)
                    )
                    ->required()
                    ->disk('local')
                    ->directory('secure/requirements') // Define la ruta donde se almacenará el archivo
                    ->acceptedFileTypes(['application/pdf']) // Limita los tipos de archivo a PDF
                    ->rules([
                        'required',
                        'mimes:pdf',
                        'max:10240',
                    ]) // Agrega validación: campo requerido y solo PDF
                    ->maxSize(10240) // 10MB
                    ->maxFiles(1)
                    ->columnSpanFull(),
                // Funcionalidad para evaluadores
                //Forms\Components\Select::make('state')->label("Estado")->live()->preload()->enum(State::class)->options(State::class),
                Forms\Components\RichEditor::make('comment')
                    ->label('Tu Comentario')
                    ->required()
                    ->disabled(fn (?Model $record) => filled($record?->requirement))
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
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction.id')
                    ->label("Ticket")
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stage.stage')
                    ->label("Etapa")
                    ->sortable(),
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('requirement')
                    ->label("Requisitos")
                    ->placeholder('Sin Archivos Aún')
                    ->formatStateUsing(
                        function ($state) {
                            if (!$state) {return null;}
                            return basename($state);
                        }
                    )
                    ->limit(10)
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction.Option.option')
                    ->label("Opción")
                    ->limit(25)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('transaction.enabled')
                    ->label('Habilitado')
                    ->icon(
                        fn ($state) => Enabled::from($state)
                            ->getIcon()
                    )
                    ->color(
                        fn ($state) => Enabled::from($state)
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
                SelectFilter::make('state')
                    ->label('Estado')
                    ->options([
                        1 => 'Aprobado',
                        2=> 'Improbado',
                        3 => 'Pendiente',
                    ])
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
            InfoSection::make('Detalles del Proceso')
            ->schema([
                TextEntry::make('stage.stage')
                    ->label("Etapa"),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->label('Creado en'),
                TextEntry::make('state')
                    ->label("Estado")
                    ->badge()
                    ->formatStateUsing(
                        fn ($state) => State::from($state)
                            ->getLabel()
                    )
                    ->color(
                        fn ($state) => State::from($state)
                            ->getColor()
                    ),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->label('Actualizado en'),
                TextEntry::make('comment')
                    ->label("Tu Comentario")
                    ->markdown()
                    ->limit(25),
                TextEntry::make('requirement')
                    ->label("Requisitos")
                    ->formatStateUsing(
                        function ($state) {
                            if (!$state) {return null;}
                            return basename($state);
                        }
                    ),
            ])->columns(2)->columnSpan(1),

            InfoSection::make('Detalles del Ticket')
            ->schema([
                TextEntry::make('transaction.id')
                    ->label("Ticket"),
                IconEntry::make('transaction.enabled')
                    ->label('Habilitado')
                    ->icon(
                        fn ($state) => Enabled::from($state)
                            ->getIcon()
                    )
                    ->color(fn ($state) => Enabled::from($state)->getColor()),
                TextEntry::make('transaction.Option.option')
                    ->label('Opción de grado'),
                TextEntry::make('transaction.component')
                    ->label('Componente')
                    ->formatStateUsing(
                        fn ($state) => Component::from($state)
                            ->getLabel()
                    ),
                TextEntry::make('transaction.profiles.name')
                    ->label('Integrante(s)')
                    ->formatStateUsing(
                        fn($state) => format_list_html($state)
                    )
                    ->html(),
                TextEntry::make('transaction.courses')
                    ->label('Carrera(s)')
                    ->formatStateUsing(
                        fn($state) => format_list_html($state)
                    )
                    ->html(),
            ])
            ->columns(2)->columnSpan(1),

        ])->columns(2);
    }

    // Función para filtrar los procesos a partir de las transacciones vinculadas con el id del autenticado
    public static function getEloquentQuery(): Builder
    {
        $profileId = Auth::user()->profiles->id;
        return Process::whereHas('transaction.profiles', function (Builder $query) use ($profileId) {
            $query->where('profile_id', $profileId)
                ->where('role_id', 1);
        });
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
        {
            return [
                'index' => Pages\ListProcesses::route('/'),
                // 'create' => Pages\CreateProcess::route('/create'),
                'view' => Pages\ViewProcess::route('/{record}'),
                'edit' => Pages\EditProcess::route('/{record}/edit'),
            ];
        }
    }
