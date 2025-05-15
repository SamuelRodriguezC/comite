<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Enums\State;
use Filament\Tables;
use App\Enums\Enabled;
use App\Models\Process;
use App\Enums\Completed;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\ProcessSubmit;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProcessSubmitResource\Pages;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Resources\ProcessSubmitResource\RelationManagers;

class ProcessSubmitResource extends Resource
{
    protected static ?string $model = Process::class;
    protected static ?string $modelLabel = "Entrega";
    protected static ?string $pluralModelLabel = "Entregas";
    protected static ?string $navigationGroup = "Etapas";
    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('stage_id')
                ->label("Etapa")
                ->relationship('stage', 'stage')
                ->visibleOn('create')
                ->required(),
            Forms\Components\Select::make('state')
                ->label('Estado')
                ->live()
                ->preload()
                // ->disabled()
                ->enum(state::class)
                ->options(State::class)
                ->required(),
            Forms\Components\Toggle::make('completed')
                ->label('Finalizado')
                ->inline(false)
                ->onColor('success')
                ->offColor('danger')
                ->onIcon(Completed::SI->getIcon())
                ->offIcon(Completed::NO->getIcon())
                ->dehydrateStateUsing(fn (bool $state) => $state ? 1 : 0)
                ->afterStateHydrated(function (Forms\Components\Toggle $component, $state) {
                    $component->state($state === 1); // Al cargar: 1 => true, 2 => false
                }),
            Forms\Components\Select::make('transaction_id')
                ->label("Ticket")
                ->relationship('transaction', 'id')
                ->visibleOn('create')
                ->required(),
            Forms\Components\FileUpload::make('requirement')
                ->label('Requisitos en PDF')
                ->required()
                ->columnSpanFull()
                ->disk('local') // Indica que se usará el disco 'public'
                ->directory('secure/requirements') // Define la ruta donde se almacenará el archivo
                ->acceptedFileTypes(['application/pdf']) // Limita los tipos de archivo a PDF
                ->rules([
                    'required',
                    'mimes:pdf',
                    'max:10240',
                ]) // Agrega validación: campo requerido y solo PDF
                ->maxSize(10240) // 10MB
                ->maxFiles(1),
            Forms\Components\RichEditor::make('comment')
                ->label('Comentario del Estudiante')
                ->required()
                ->disableToolbarButtons(['attachFiles', 'link', 'strike', 'codeBlock', 'h2', 'h3', 'blockquote'])
                ->maxLength(255)
                ->columnSpanFull(),
        ])->columns(2);
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
                    ->sortable()
                    ->toggleable(), //Seleccionada por defecto
                    // ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state')
                    ->label("Estado")
                    ->badge()
                    ->color(fn ($state) => State::from($state)->getColor())
                    ->formatStateUsing(fn ($state) => State::from($state)->getLabel())
                    ->sortable(),
                Tables\Columns\IconColumn::make('completed')
                    ->label("Finalizado")
                    ->icon(fn ($state) => Completed::from($state)->getIcon())
                    ->color(fn ($state) => Completed::from($state)->getColor())
                    ->sortable(),
                Tables\Columns\TextColumn::make('requirement')
                    ->label("Requisitos")
                    ->placeholder('Sin requisitos aún')
                    ->formatStateUsing(function ($state) {if (!$state) {return null;}return basename($state);})
                    ->limit(10)
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction.Option.option')
                    ->label("Opción")
                    ->limit(20)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction.component')
                    ->label("Componente")
                    ->formatStateUsing(fn ($state) => Component::from($state)->getLabel())
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('transaction.enabled')
                    ->label('Habilitado')
                    ->icon(fn ($state) => Enabled::from($state)->getIcon())
                    ->color(fn ($state) => Enabled::from($state)->getColor()),
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
                    ->formatStateUsing(fn ($state) => State::from($state)->getLabel())
                    ->color(fn ($state) => State::from($state)->getColor()),
                TextEntry::make('updated_at')
                        ->dateTime()
                        ->label('Actualizado en'),
                IconEntry::make('completed')
                    ->label("Finalizado")
                    ->icon(fn ($state) => Completed::from($state)->getIcon())
                    ->color(fn ($state) => Completed::from($state)->getColor()),
                TextEntry::make('requirement')
                    ->formatStateUsing(function ($state) {if (!$state) {return null;}return basename($state);})
                    ->limit(20)
                    ->default('Sin requisitos aún')
                    ->label("Requisitos"),
                TextEntry::make('comment')
                    ->markdown()
                    ->default('Sin comentario aún')
                    ->label("Comentario del Estudiante"),
            ])->columns(2)->columnSpan(1),

            InfoSection::make('Detalles del Ticket')
            ->schema([
                TextEntry::make('transaction.id')
                    ->label("Ticket"),
                IconEntry::make('transaction.enabled')
                        ->label('Habilitado')
                        ->icon(fn ($state) => Enabled::from($state)->getIcon())
                        ->color(fn ($state) => Enabled::from($state)->getColor()),
                TextEntry::make('transaction.Option.option')
                        ->label('Opción de grado'),
                TextEntry::make('transaction.component')
                        ->label('Componente')
                        ->formatStateUsing(fn ($state) => Component::from($state)->getLabel()),
                TextEntry::make('transaction.profiles.name')
                        ->label('Integrante(s)')
                        ->formatStateUsing(fn($state) => format_list_html($state))
                        ->html(),
                TextEntry::make('transaction.courses')
                        ->label('Carrera(s)')
                        ->formatStateUsing(fn($state) => format_list_html($state))
                        ->html(),
            ])
            ->columns(2)->columnSpan(1),
        ])->columns(2);
    }

    // Filtra etapa por primera entrega (antiguamente llamado proceso)
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('stage_id', 2);
    }

    // Filtra por solicitudes pendientes
    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->where('state', '3')->count();
    }

    // Describe el getNavigationBadge
    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Primeras entregas pendientes';
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
            'index' => Pages\ListProcessSubmits::route('/'),
            // 'create' => Pages\CreateProcessSubmit::route('/create'),
            'view' => Pages\ViewProcessSubmit::route('/{record}'),
            'edit' => Pages\EditProcessSubmit::route('/{record}/edit'),
        ];
    }
}
