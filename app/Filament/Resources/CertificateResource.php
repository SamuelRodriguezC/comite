<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateResource\Pages;
use App\Models\Certificate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\Action;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static ?string $navigationLabel = 'Certificados';
    protected static ?string $pluralModelLabel = 'Certificados';
    protected static ?string $modelLabel = 'Certificado';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;

    // ---------------- FORMULARIO ----------------
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('acta')
                    ->label('Acta')
                    ->required(),

                Forms\Components\Select::make('transaction_id')
                    ->label('Transacción')
                    ->relationship('transaction', 'id')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('signer_id')
                    ->label('Firmante')
                    ->relationship('signer', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->options([
                        1 => 'Estudiante',
                        2 => 'Otro tipo', // ajusta según tu enum CertificateType
                    ])
                    ->required(),

                Forms\Components\Select::make('profile_id')
                    ->label('Perfil')
                    ->relationship('profile', 'name')
                    ->searchable()
                    ->required(),
            ]);
    }

    // ---------------- TABLA ----------------
    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('id')->label('ID')->sortable(),
            TextColumn::make('acta')->label('Acta')->sortable(),
            TextColumn::make('transaction.id')->label('Transacción')->sortable(),
            TextColumn::make('signer.name')->label('Firmante')->sortable(),
            TextColumn::make('type')
                ->label('Tipo')
                ->formatStateUsing(fn ($state) => $state == 1 ? 'Estudiante' : 'Otro tipo')
                ->sortable(),
            TextColumn::make('profile.name')->label('Perfil')->sortable(),
            TextColumn::make('created_at')->label('Creado')->date()->sortable(),
        ])
        ->actions([
            Action::make('descargar')
                ->label('Descargar PDF')
                ->icon('heroicon-o-arrow-down')
                ->color('success')
                ->url(fn ($record) => route('pdf.download', urlencode($record->acta)))
                ->openUrlInNewTab(false),
                 // Botón de ver
            Action::make('ver')
                ->label('Ver PDF')
                ->icon('heroicon-o-eye')
                ->color('primary')
                ->url(fn ($record) => route('pdf.view', urlencode($record->acta)))
                ->openUrlInNewTab(true),
        ])
        ->bulkActions([
            DeleteBulkAction::make()->label('Eliminar varios'),
        ]);
        
}




    // ---------------- RELACIONES ----------------
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // ---------------- PÁGINAS ----------------
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCertificates::route('/'),
            'create' => Pages\CreateCertificate::route('/create'),
            'edit' => Pages\EditCertificate::route('/{record}/edit'),
        ];
    }
}
