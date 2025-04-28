<?php

namespace App\Filament\Advisor\Resources;

use Filament\Forms;
use App\Enums\State;
use Filament\Tables;
use App\Models\Process;
use App\Enums\Completed;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Models\ProcessAplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
                Forms\Components\Select::make('stage_id')
                    ->disabledOn('edit')
                    ->label("Etapa")
                    ->relationship('stage', 'stage')
                    ->required(),
                Forms\Components\Select::make('state')
                    ->label('Estado')
                    ->disabledOn('edit')
                    ->live()
                    ->preload()
                    ->enum(State::class)
                    ->options(State::class)
                    ->required(),
                Forms\Components\Select::make('completed')
                    ->label('Finalizado')
                    ->disabledOn('edit')
                    ->live()
                    ->preload()
                    ->enum(Completed::class)
                    ->options(Completed::class)
                    ->required(),
                Forms\Components\Textarea::make('comment')
                    ->label("Comentario")
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('transaction_id')
                    ->label("Ticket")
                    ->relationship('transaction', 'id')
                    ->disabledOn('edit')
                    ->required(),
                Forms\Components\FileUpload::make('requirement')
                    ->label('Requisitos en PDF')
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction.id')
                    ->label("Ticket")
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stage.stage')
                    ->label("Etapa")
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->label("Estado")
                    ->formatStateUsing(fn ($state) => State::from($state)->getLabel())
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed')
                    ->label("Finalizado")
                    ->formatStateUsing(fn ($state) => Completed::from($state)->getLabel())
                    ->sortable(),
                Tables\Columns\TextColumn::make('requirement')
                    ->label("Requisitos")
                    ->searchable(),
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
                    TextEntry::make('transaction.id')
                        ->label("Ticket"),
                    TextEntry::make('stage.stage')
                        ->label("Etapa"),
                    TextEntry::make('state')
                        ->label("Estado")
                        ->formatStateUsing(fn ($state) => State::from($state)->getLabel()),
                    TextEntry::make('completed')
                        ->label("Finalizado")
                        ->formatStateUsing(fn ($state) => Completed::from($state)->getLabel()),
                    TextEntry::make('requirement')
                        ->label("Requisitos"),
                    TextEntry::make('created_at')
                        ->dateTime()
                        ->label('Creado en'),
                    TextEntry::make('update_at')
                        ->dateTime()
                        ->label('Actualizado en'),
                ]),
        ]);
    }

    // Filtra por usuario autenticado y por solicitud
    public static function getEloquentQuery(): Builder
    {
        $profileId = Auth::user()->profiles->id;
        return parent::getEloquentQuery()
            ->whereIn('stage_id', [1])
            ->whereHas('transaction.profiles', function (Builder $query) use ($profileId) {
                $query->where('profile_id', $profileId);
            });
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
            'index' => Pages\ListProcessAplications::route('/'),
            'create' => Pages\CreateProcessAplication::route('/create'),
            'view' => Pages\ViewProcessAplication::route('/{record}'),
            //'edit' => Pages\EditProcessAplication::route('/{record}/edit'),
        ];
    }
}
