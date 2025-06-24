<?php

namespace App\Filament\Advisor\Resources;

use Carbon\Carbon;
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
use App\Models\ProcessAplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Advisor\Resources\ProcessAplicationResource\Pages;
use App\Filament\Advisor\Resources\ProcessAplicationResource\RelationManagers;

class ProcessAplicationResource extends Resource
{
    protected static ?string $model = Process::class;
    protected static ?string $modelLabel = "Solicitud";
    protected static ?string $pluralModelLabel = "Solicitudes";
    protected static ?string $navigationGroup = "Procesos";
    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
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
                    ->maxFiles(1) ,
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction.id')
                    ->label("# Opción")
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
                    ->color(
                        fn ($state) => State::from($state)
                            ->getColor()
                    )
                    ->formatStateUsing(
                        fn ($state) => State::from($state)
                            ->getLabel()
                        )
                    ->sortable(),
                Tables\Columns\IconColumn::make('completed')
                    ->label("Finalizado")
                    ->icon(
                        fn ($state) => Completed::from($state)
                            ->getIcon()
                    )
                    ->color(
                        fn ($state) => Completed::from($state)
                            ->getColor()
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('requirement')
                    ->label("Requisitos")
                    ->placeholder('Sin requisitos aún')
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
                    ->formatStateUsing(
                        fn ($state) => Component::from($state)
                            ->getLabel()
                    )
                    ->sortable()
                    ->searchable(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->label('Subir')
                    ->icon('heroicon-o-document-arrow-up')
                    ->visible(function ($record) {
                        // Verificar si la transacción está habilitada
                        $isEnabled = $record->transaction?->enabled === 1;
                        // Verificar si el campo 'Requirement' está vacío o tiene un espacio en blanco ''
                        $hasNoRequirement = !$record->requirement || trim($record->requirement) === '';
                        // Verificar si la fecha de entrega aún no a vencido o no está definida
                        $stillInTime = !$record->delivery_date || Carbon::now()->lessThan($record->delivery_date);

                        // El botón de edición solo será visible si:
                        // - La transacción está habilitada
                        // - No hay requerimientos en el proceso
                        // - Aún está dentro del tiempo permitido para entregar
                        return $isEnabled && $hasNoRequirement && $stillInTime;
                    }),
            ])
            ->bulkActions([
                
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
                    ->formatStateUsing(function ($state) {
                        // Si el estado del requerimiento es nulo, retorna null para no mostrar nada en la infolist
                        if (!$state)
                         {return null;}

                        //  Si hay valor, retorna solo el nombre del archivo (quitandole la ruta)
                         return basename($state);
                    })
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
                        // Utiliza la función format_list_html que se encuentra en el archivo helpers.php
                        ->formatStateUsing(fn($state) => format_list_html($state))
                        ->html(),
                TextEntry::make('transaction.courses')
                        ->label('Carrera(s)')
                        // Utiliza la función format_list_html que se encuentra en el archivo helpers.php
                        ->formatStateUsing(fn($state) => format_list_html($state))
                        ->html(),
            ])
            ->columns(2)->columnSpan(1),
        ])->columns(2);
    }


    // Filtra por usuario autenticado y por solicitud
    public static function getEloquentQuery(): Builder
    {
        $profileId = Auth::user()->profiles->id; //Obtiene el id del perfil del usuario en sesión

        return parent::getEloquentQuery()
            // Filtra los procesos para que solo muestre los que estan en etapa de solicitud
            ->whereIn('stage_id', [1])
            // Asegura que la transacción muestre los procesos donde está el perfil y su rol es asesor
            ->whereHas('transaction.profiles', function (Builder $query) use ($profileId) {
                $query->where('profile_id', $profileId)
                    ->where('role_id', 2);
            });
    }

    // Filtra por solicitudes pendientes
    public static function getNavigationBadge(): ?string
    {
        // Cuenta todos los procesos donde su estado es 3 = Pendiente
        return static::getEloquentQuery()
            ->where('state', '3')
            ->count();
    }

    public static function getRelations(): array
    {
        return [
            // Gerente de relaciones para mostrar los comentarios relacionados con el proceso
            RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProcessAplications::route('/'),
            // 'create' => Pages\CreateProcessAplication::route('/create'),
            'view' => Pages\ViewProcessAplication::route('/{record}'),
            // 'edit' => Pages\EditProcessAplication::route('/{record}/edit'),
        ];
    }
}
