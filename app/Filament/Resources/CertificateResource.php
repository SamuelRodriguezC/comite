<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Certificate;
use App\Enums\CertificateType;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\CertificateResource\Pages;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static ?string $navigationLabel = 'Certificados';
    protected static ?string $pluralModelLabel = 'Certificados';
    protected static ?string $modelLabel = 'Certificado';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;


    // ---------------- TABLA ----------------
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('acta')->label('Acta')->sortable()->formatStateUsing(fn ($state) => $state ? basename($state) : null),
                TextColumn::make('transaction.id')->label('Opci.. Grado')->sortable(),
                TextColumn::make('signer.full_name')->label('Director De Investigación')->sortable(),
                TextColumn::make('type')
                    ->label('Tipo Certificación')
                    ->formatStateUsing(fn ($state) => $state?->getLabel())
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state?->getColor())
                    ->searchable(),
                TextColumn::make('profile.name')->label('Perfil')->sortable()->placeholder('Estudiantes'),
                TextColumn::make('created_at')->label('Creado')->date()->sortable(),
            ])
            ->actions([
                Action::make('descargar')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down')
                    ->color('success')
                    ->url(fn($record) => route('pdf.download', urlencode($record->acta)))
                    ->openUrlInNewTab(false),
                // Botón de ver
                Action::make('ver')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(fn($record) => route('pdf.view', urlencode($record->acta)))
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
            // 'create' => Pages\CreateCertificate::route('/create'),
            'edit' => Pages\EditCertificate::route('/{record}/edit'),
        ];
    }
}
