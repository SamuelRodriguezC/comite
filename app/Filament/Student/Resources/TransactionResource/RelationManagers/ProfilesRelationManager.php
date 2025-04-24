<?php

namespace App\Filament\Student\Resources\TransactionResource\RelationManagers;

use App\Enums\Component;
use App\Enums\Enabled;
use App\Enums\Level;
use Filament\Forms;
use Filament\Tables;
use App\Models\Course;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Forms\Components\Group;
use Filament\Infolists\Components\Section;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ProfilesRelationManager extends RelationManager
{
    protected static string $relationship = 'profiles';
    protected static ?string $title = 'Integrantes de La Transacción';

    public static function getEloquentQuery(): Builder
    {
        // Obtén el perfil del usuario autenticado
        $profileId = Auth::user()->profiles->id;

        // Realiza la consulta para obtener las transacciones relacionadas con el perfil del usuario
        return Transaction::whereNHas('profiles', function (Builder $query) use ($profileId) {
            $query->where('profile_id', $profileId);
        });
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\TextInput::make('document_number')
                    ->required()
                    ->maxLength(255),

                    Select::make('courses_id')
                    ->label('Curso')
                    ->options(Course::all()->pluck('course', 'id'))
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('document_number') //Atributo de busqueda
            ->columns([
                Tables\Columns\TextColumn::make('document_number')->label('Documento'),
                Tables\Columns\TextColumn::make('name')->label('Nombres'),
                Tables\Columns\TextColumn::make('last_name')->label('Apellidos'),
                Tables\Columns\TextColumn::make('pivot.courses_id')->label('Carrera')
                    ->words(4)
                    // Transformar el ID del curso a su nombre
                    ->formatStateUsing(function ($state) {
                        return \App\Models\Course::find($state)?->course ?? 'Curso no encontrado';
                }),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('courses_id') // Asegúrate que sea el nombre correcto en la tabla pivote
                            ->label('Curso')
                            ->options(Course::all()->pluck('course', 'id'))
                            ->searchable()
                            ->required(),
                    ]),
            ])
            ->actions([
                // Botón para ver detalles de integrante
                Tables\Actions\ViewAction::make()
                ->label('Ver')
                ->modalHeading('Información Personal')
                // Crear el modal con una infolista
                ->modalContent(fn ($record) => Infolist::make()
                    ->schema([
                        Section::make([
                            TextEntry::make('name')->label('Nombre'),
                            TextEntry::make('last_name')->label('Apellido'),
                            TextEntry::make('User.email')->label('Email'),
                            TextEntry::make('phone_number')->label('Número de Teléfono'),
                        ])->columns(2)->columnSpan(2),

                        Section::make([
                            TextEntry::make('level')->label('Nivel Universitario')->formatStateUsing(fn ($state) => Level::from($state)->getLabel()),
                        ])->columnSpan(1),


                    ])->columns(3)
                    ->record($record)),// El $record aquí viene del modelo actual en la tabla


                // Solo la persona en sesión puede cambiar su carrera
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->id === auth_profile_id()),

                // la persona en sesión no puede desvincularse
                Tables\Actions\DetachAction::make()
                    ->visible(fn ($record) => $record->id !== auth_profile_id()),

            ])
            ->emptyStateActions([
                Tables\Actions\AttachAction::make(),
            ])
            ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make(),
                    ]),
                ]);
    }



}

