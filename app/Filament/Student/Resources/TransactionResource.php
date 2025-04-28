<?php

namespace App\Filament\Student\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Enums\Enabled;
use App\Models\Course;
use App\Models\Option;
use App\Models\Profile;
use Filament\Forms\Set;
use App\Enums\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Infolists\Components\Group;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section as FormSection;
use Filament\Infolists\Components\Section as InfoSection;
use App\Filament\Student\Resources\TransactionResource\Pages;
use App\Filament\Student\Resources\TransactionResource\RelationManagers;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $modelLabel = "Ticket";
    protected static ?string $pluralModelLabel = "Tickets";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('component')
                    ->label("Componente")
                    ->live()
                    ->preload()
                    ->required()
                    ->enum(Component::class)
                    ->options(Component::class)
                    // Función para poner null el campo option_id cuando component se modifica
                    ->afterStateUpdated(fn (Set $set) => $set('option_id',null)),
                Forms\Components\Select::make('option_id')
                    ->label("Opción de grado")
                    ->relationship('Option', 'option')
                    ->required()
                    // Función para filtrar la opción de grado por nivel universitario y componente
                    ->options(function (callable $get) {
                        $user = Auth::user();
                        if (!$user || !$user->profiles) {
                            return ["Aún no tiene perfil"]; // Si el perfil no está disponible, no se muestran opciones
                        }
                        $userLevel = $user->profiles->level;
                        $selectedComponent = $get('component');
                        if (!$selectedComponent) {
                            return ["Aún no ha seleccionado componente"]; // Evita mostrar opciones si el componente aún no se ha seleccionado
                        }
                        return Option::where('level', $userLevel)
                            ->where('component', $selectedComponent)
                            ->pluck('option', 'id');
                    }),
                // Campo para seleccionar curso
                Forms\Components\Select::make('courses_id')
                    ->label('Curso')
                    ->visibleOn('create')
                    //->relationship('profile_transaction', 'courses_id')
                    // Mostrar los cursos de la tabla
                    ->options(\App\Models\Course::all()->pluck('course', 'id'))
                    //->searchable()
                    ->required()
                    ->options(function () {
                        $user = Auth::user();
                        if (!$user || !$user->profiles) {
                            return ["El perfil no tiene nivel universitario"]; // Retornamos un arreglo vacío si no hay usuario o perfil
                        }
                        // Obtenemos el nivel universitario del usuario autenticado (pregrado o posgrado)
                        $userLevel = $user->profiles->level;
                        // Filtramos las carreras que tengan el mismo nivel universitario
                        return \App\Models\Course::where('level', $userLevel)
                            ->pluck('course', 'id');
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label("Ticket")
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('component')
                    ->label("Componente")
                    ->formatStateUsing(fn ($state) => Component::from($state)->getLabel())
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('option.option')
                    ->label("Opción de grado")
                    ->words(3)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('courses')
                    ->label('Carreras')
                    ->sortable()
                    ->words(4),
                    // ->searchable(),
                Tables\Columns\IconColumn::make('enabled')
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
                SelectFilter::make('component')
                ->options([
                    '1' => 'Investigativo',
                    '2' => 'No Investigativo',
                ])
                ->attribute('component')
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
            InfoSection::make([
                TextEntry::make('id')
                    ->label('Ticket'),
                TextEntry::make('component')
                    ->label('Componente')
                    ->formatStateUsing(fn ($state) => Component::from($state)->getLabel()),

                TextEntry::make('Option.option')
                    ->label('Opción de grado'),

                TextEntry::make('profiles.name') // Campo para "Personas"
                    ->label('Personas')
                    ->formatStateUsing(fn($state) => format_list_html($state))
                    ->html(), // Permite HTML en la salida

                TextEntry::make('courses') // Campo para "Carreras"
                    ->label('Carreras')
                    ->formatStateUsing(fn($state) => format_list_html($state))
                    ->html(), // Permite HTML en la salida
            ])->columns(2)->columnSpan(2),

            InfoSection::make([
                Group::make([
                    TextEntry::make('created_at')
                        ->label('Creado en'),
                    TextEntry::make('updated_at')
                    ->label('Actualizado en'),
                    IconEntry::make('enabled')
                        ->label('Habilitado')
                        ->icon(fn ($state) => Enabled::from($state)->getIcon())
                        ->color(fn ($state) => Enabled::from($state)->getColor()),

                ])->columns(2),
            ])->columnSpan(1),


        ])->columns(3);
    }

    // Función para filtrar las transacciones por usuario
    public static function getEloquentQuery(): Builder
    {
        // Obtén el perfil del usuario autenticado
        $profileId = Auth::user()->profiles->id;
        // Realiza la consulta para obtener las transacciones relacionadas con el perfil del usuario
        return Transaction::whereHas('profiles', function (Builder $query) use ($profileId) {
            $query->where('profile_id', $profileId);
        });
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProfilesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/{record}'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
