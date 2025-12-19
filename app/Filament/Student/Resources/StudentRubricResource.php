<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\StudentRubricResource\Pages;
use App\Filament\Student\Resources\StudentRubricResource\RelationManagers;
use App\Models\StudentRubric;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Model;

class StudentRubricResource extends Resource
{
    protected static ?string $model = StudentRubric::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
       protected static ?string $navigationLabel = 'Rúbricas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

 public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')->label('Nombre de la rúbrica'),
            Tables\Columns\TextColumn::make('course.course')->label('Programa'),
            Tables\Columns\TextColumn::make('performance_level')->label('Nivel de desempeño'),
            Tables\Columns\TextColumn::make('academic_period')->label('Periodo académico'),
        ])
        ->filters([
            SelectFilter::make('course_id')
                ->label('Filtra tu rúbrica por programa')
                ->options(
                    \App\Models\Course::all()->pluck('course', 'id')->toArray()
                ),
        ])
        ->actions([
            Tables\Actions\Action::make('downloadPdf')
                ->label('Descargar PDF')
                ->url(fn ($record) => route('rubrics.pdf', $record))
                ->openUrlInNewTab(), // abre el PDF en nueva pestaña
        ])
        ->bulkActions([]) // Sin acciones masivas
        ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListStudentRubrics::route('/'),
            'create' => Pages\CreateStudentRubric::route('/create'),
            'edit' => Pages\EditStudentRubric::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->with('course');
}
public static function canCreate(): bool
{
    return false;
}

public static function canEdit(Model $record): bool
{
    return false;
}

public static function canDelete(Model $record): bool
{
    return false;
}

}

