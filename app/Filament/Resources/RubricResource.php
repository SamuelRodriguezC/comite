<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RubricResource\Pages;
use App\Filament\Resources\RubricResource\RelationManagers;
use App\Models\Rubric;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Course; 


class RubricResource extends Resource
{
        protected static ?string $model = Rubric::class;

    protected static ?string $navigationLabel = 'Rúbrica';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $pluralModelLabel = 'Rúbricas';
    protected static ?string $navigationGroup = 'Certificados';

    public static function form(Form $form): Form
    {
           return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nombre de la rúbrica')
                ->required(),
                Forms\Components\Select::make('course_id') 
                ->label('Programa')
                ->options(
                    Course::all()->pluck('course', 'id')->toArray()
                )
                ->searchable()
                ->required(),
                Forms\Components\Textarea::make('competencies_results_grades')
    ->label('Competencias, Resultados y Calificaciones')
    ->placeholder('Ingresa aquí las competencias, resultados y calificaciones...')
    ->rows(5) // tamaño del cuadro
    ->required(),
    
    Forms\Components\Select::make('performance_level')
    ->label('Niveles de desempeño')
    ->options([
        'Insuficiente' => 'Insuficiente',
        'Básico' => 'Básico',
        'Bueno' => 'Bueno',
        'Excelente' => 'Excelente',
        
    ])
    ->required(),
    Forms\Components\Textarea::make('level_descriptions')
    ->label('Descripciones por nivel')
    ->placeholder('Ingresa aquí las descripciones por nivel...')
    ->rows(5)
    ->required(),
    Forms\Components\Textarea::make('resultados_aprendizaje')
    ->label('Resultados de Aprendizaje y calificaciones')
    ->placeholder('Ingresa aquí los resultados y calificaciones...')
    ->rows(5) 
    ->required(),
Forms\Components\Select::make('academic_period')
    ->label('Periodo Académico')
    ->options(function () {
        $year = date('Y'); // año actual
        return [
            $year.'-1' => $year.'-1',
            $year.'-2' => $year.'-2',
        ];
        
    })
    
    ->required(),

        ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->label('Nombre de la rúbrica')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('course.course')
                ->label('Programa')
                ->sortable()
                ->searchable(),

            

            Tables\Columns\TextColumn::make('performance_level')
                ->label('Niveles de desempeño'),

        

            Tables\Columns\TextColumn::make('academic_period')
                ->label('Periodo Académico'),


            Tables\Columns\TextColumn::make('status')
                ->label('Estado'),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Creado')
                ->date(),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Actualizado')
                ->date(),
                
        ])
        ->actions([
            Tables\Actions\EditAction::make(),

            Tables\Actions\Action::make('toggleStatus')
                ->label(fn ($record) => $record->status == 'Habilitado' ? 'Cancelar' : 'Habilitar')
                ->action(function ($record) {
                    $record->status = $record->status == 'Habilitado' ? 'Cancelado' : 'Habilitado';
                    $record->save();
                })
                ->color(fn ($record) => $record->status == 'Habilitado' ? 'danger' : 'success')
                ->icon(fn ($record) => $record->status == 'Habilitado' ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle'),
                Tables\Actions\Action::make('downloadPdf')
    ->label('Descargar PDF')
    ->icon('heroicon-s-arrow-down')
    ->url(fn ($record) => route('rubrics.pdf', $record))
    ->openUrlInNewTab()
                ->action(function ($record) {
                    $pdf = new \FPDF();
                    $pdf->AddPage();

                    // Logo (opcional)
                    if(file_exists(public_path('images/logo.png'))) {
                        $pdf->Image(public_path('images/logo.png'), 10, 10, 40);
                        $pdf->Ln(20);
                    }

                    $pdf->SetFont('Arial', 'B', 16);
                    $pdf->Cell(0, 10, 'Rúbrica: ' . $record->name, 0, 1);

                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Cell(0, 10, 'Programa: ' . $record->course->course, 0, 1);
                    $pdf->Cell(0, 10, 'Periodo Académico: ' . $record->academic_period, 0, 1);
                    $pdf->Cell(0, 10, 'Nivel de desempeño: ' . $record->performance_level, 0, 1);
                    $pdf->Cell(0, 10, 'Puntaje máximo: ' . $record->score, 0, 1);
                    $pdf->Cell(0, 10, 'Estado: ' . $record->status, 0, 1);
                    $pdf->Ln(5);

                    $pdf->MultiCell(0, 8, "Competencias, Resultados y Calificaciones:\n" . $record->competencies_results_grades);
                    $pdf->Ln(2);
                    $pdf->MultiCell(0, 8, "Descripciones por nivel:\n" . $record->level_descriptions);
                    $pdf->Ln(2);
                    $pdf->MultiCell(0, 8, "Resultados de aprendizaje:\n" . $record->Resultados_aprendizaje);

                    $pdf->Output('D', 'Rúbrica-' . $record->id . '.pdf');
                }),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ])
        ->defaultSort('created_at', 'desc');
        
}
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->with('course');
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
            'index' => Pages\ListRubrics::route('/'),
            'create' => Pages\CreateRubric::route('/create'),
            'edit' => Pages\EditRubric::route('/{record}/edit'),
        ];
    }
}
