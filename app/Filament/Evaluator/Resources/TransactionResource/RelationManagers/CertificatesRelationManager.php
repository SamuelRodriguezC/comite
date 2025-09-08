<?php

namespace App\Filament\Evaluator\Resources\TransactionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CertificatesRelationManager extends RelationManager
{
    protected static string $relationship = 'certificates';
    protected static ?string $title = 'Certificados y Evaluaciones';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('acta')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('# Certificado'),
                Tables\Columns\TextColumn::make('acta')
                    ->label('Certificado')
                    ->formatStateUsing(function ($state) {if (!$state) {return null;}return basename($state);}),
                Tables\Columns\TextColumn::make('signer.full_name')
                    ->label('Director de Investigación'),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo Certificación')
                    ->formatStateUsing(fn ($state) => $state?->getLabel())
                    ->badge()
                    ->color(fn ($state) => $state?->getColor()),
                Tables\Columns\TextColumn::make('profile.name')
                    ->label('Asignado')
                    ->placeholder('Estudiantes'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_certificate')
                ->label('Ver certificado')
                ->icon('heroicon-o-eye')
                ->color('primary')
                ->url(fn ($record) => $record->acta ? asset($record->acta) : null, true)
                ->openUrlInNewTab()
                ->hidden(fn ($record) => !$record->acta), // Oculta el botón si no hay archivo
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([

                ]),
            ]);
    }
}
