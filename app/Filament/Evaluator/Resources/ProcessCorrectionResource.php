<?php

namespace App\Filament\Evaluator\Resources;

use Filament\Forms;
use App\Enums\State;
use Filament\Tables;
use App\Enums\Enabled;
use App\Models\Process;
use App\Enums\Completed;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Models\ProcessCorrection;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Evaluator\Resources\ProcessCorrectionResource\Pages;
use App\Filament\Evaluator\Resources\ProcessCorrectionResource\RelationManagers;

class ProcessCorrectionResource extends Resource
{
    protected static ?string $model = Process::class;
    protected static ?string $modelLabel = "Correción";
    protected static ?string $pluralModelLabel = "Correcciones";
    protected static ?string $navigationGroup = "Procesos";
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?int $navigationSort = 3;

 public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('state')
                ->label('Estado')
                ->live()
                ->disabled()
                ->preload()
                ->enum(state::class)
                ->options(State::class),
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
            Forms\Components\DateTimePicker::make('delivery_date')
                ->label('Fecha Límite de Entrega')
                ->columnSpanFull(),
        ])->columns(2);
    }


    public static function table(Table $table): Table
    {
        return $table
            // Mostrar registros en orden descendente por su fecha de creación
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('transaction.id')
                    ->label("Opción")
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stage.stage')
                    ->label("Etapa")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state')
                    ->label("Estado")
                    ->badge()
                     // Mostrar el color del badge según el estado
                    ->color(
                        fn ($state) => State::from($state)
                            ->getColor()
                    )
                     // Mostrar el texto del badge según el estado
                    ->formatStateUsing(
                        fn ($state) => State::from($state)
                            ->getLabel()
                    )
                    ->sortable(),
                Tables\Columns\IconColumn::make('completed')
                    ->label("Finalizado")
                    // Mostrar el icono y color según el estado de finalización
                    ->icon(
                        fn ($state) => Completed::from($state)
                            ->getIcon()
                    )
                    // Mostrar el color del icono según el estado de finalización
                    ->color(
                        fn ($state) => Completed::from($state)
                            ->getColor()
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('requirement')
                    ->label("Requisitos")
                    ->placeholder('Sin requisitos aún')
                     // Mostrar solo el nombre del archivo si está cargado
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
                Tables\Columns\TextColumn::make('transaction.component')
                    ->label("Componente")
                    // Mostrar el nombre del componente
                    ->formatStateUsing(
                        fn ($state) => Component::from($state)
                            ->getLabel()
                    )
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('transaction.enabled')
                    ->label('Habilitado')
                    ->icon(fn ($state) => Enabled::from($state)->getIcon())
                    ->color(fn ($state) => Enabled::from($state)->getColor()),
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
                //Filtro por requisito: muestra registros con o sin requisitos
                 SelectFilter::make('requirement')
                    ->label('Requisitos')
                    ->options([
                        'empty' => 'Sin requisitos',
                        'not_empty' => 'Con requisitos',
                    ])
                    ->query(fn (Builder $query, array $data) => match ($data['value'] ?? null) {
                         // Si está vacío o contiene solo espacios, lo considera "sin requisitos"
                        'empty' => $query->where(fn ($q) =>
                            $q->whereNull('requirement')->orWhereIn('requirement', ['', ' '])
                        ),
                        // Si tiene algún valor distinto de vacío/espacios, lo considera "con requisitos"
                        'not_empty' => $query->whereNotNull('requirement')->whereNotIn('requirement', ['', ' ']),

                         // Sin filtro si no se selecciona opción
                        default => $query,
                    }),

                // Filtro por estado de habilitación de la transacción
                SelectFilter::make('enabled')
                    ->label('Habilitado')
                    ->options([
                        '1' => 'Habilitado',
                        '2' => 'Deshabilitado',
                    ])
                    ->query(fn (Builder $query, array $data) =>
                        // Filtro por estado de habilitación de la transacción
                        isset($data['value'])
                            ? $query->whereHas('transaction', fn ($q) => $q->where('enabled', $data['value']))
                            : $query
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    // El botón de edición solo se  muestra si la transacción está habilitada
                    ->visible(fn ($record) => $record->transaction?->enabled !== 2),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                ]),
            ]);
    }

   public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            InfoSection::make('Detalles del Proceso #')
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
                    ->limit(10)
                    ->placeholder('Sin requisitos aún')
                    ->label("Requisitos"),
                TextEntry::make('comment')
                    ->markdown()
                    ->columnSpanFull()
                    ->placeholder('Sin comentario aún')
                    ->label("Comentario de Entrega"),
                TextEntry::make('delivery_date')
                    ->label('Limite de Entrega')
                    ->placeholder('No se ha establecido fecha limite de entrega aún')
                    ->dateTime(),
            ])->columns(2)->columnSpan(1),

            InfoSection::make('Detalles de la Opción')
            ->schema([
                TextEntry::make('transaction.id')
                    ->label("Opción"),
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
                        // Usar helper para formatetar los integrantes y mostrarlos en una lista
                        ->formatStateUsing(fn($state) => format_list_html($state))
                        ->html(),
                TextEntry::make('transaction.courses')
                        ->label('Carrera(s)')
                        // Usar helper para formatetar las carreras y mostrarlos en una lista
                        ->formatStateUsing(fn($state) => format_list_html($state))
                        ->html(),
            ])
            ->columns(2)->columnSpan(1),
        ])->columns(2);
    }

    // Filtra por usuario autenticado y por corrección 1 y 2
    public static function getEloquentQuery(): Builder
    {
        // Obtener el ID del perfil del usuario autenticado
        $profileId = Auth::user()->profiles->id;
        return parent::getEloquentQuery()
            // Filtrar solo registros que estén en la etapa con ID 1
            ->whereIn('stage_id', [3, 4])

            // Filtrar registros que estén asociados a transacciones donde:
            // - El perfil coincida con el del usuario autenticado
            // - El rol asignado sea el de Evaluador (role_id = 3)
            ->whereHas('transaction.profiles', function (Builder $query) use ($profileId) {
                $query->where('profile_id', $profileId)
                    ->where('role_id', 3); // Rol Evaluador
            });
    }

    // Filtra por solicitudes pendientes
    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()
            // Filtrar registros con estado 3 = Pendiente
            ->where('state', '3')

            // Solo contar si la transacción asociada está habilitada (enabled = 1)
            ->whereHas('transaction', function ($query) {
                    $query->where('enabled', 1);
            })


            // Devolver la cantidad como badge de navegación
            ->count();
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
            'index' => Pages\ListProcessCorrections::route('/'),
            // Url para crear deshabilitada (Puerta Trasera)
            //'create' => Pages\CreateProcessCorrection::route('/create'),
            'view' => Pages\ViewProcessCorrection::route('/{record}'),
            'edit' => Pages\EditProcessCorrection::route('/{record}/edit'),
        ];
    }
}
