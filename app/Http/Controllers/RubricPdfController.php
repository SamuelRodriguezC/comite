<?php


namespace App\Http\Controllers;

use App\Models\Rubric;
use Illuminate\Http\Request;
use TCPDF;

class RubricPdfController extends Controller
{
    public function download(Rubric $rubric)
    {
        // Crear nuevo PDF
        $pdf = new TCPDF();
        $pdf->SetCreator('MiApp');
        $pdf->SetAuthor('Coordinación');
        $pdf->SetTitle('Rúbrica de Evaluación');
        $pdf->SetMargins(15, 20, 15);
        $pdf->AddPage();

        // --- Logo ---
        if(file_exists(public_path('images/logo.jpg'))) {
            $pdf->Image(public_path('images/logo.jpg'), 15, 10, 30, 0, 'JPG');
        }

        // --- Título ---
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->Ln(15);
        $pdf->Cell(0, 10, 'Rúbrica de Evaluación', 0, 1, 'C');

        // --- Información general ---
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Ln(5);
        $pdf->Cell(50, 8, 'Nombre:', 1);
        $pdf->Cell(0, 8, $rubric->name, 1, 1);

        $pdf->Cell(50, 8, 'Programa:', 1);
        $pdf->Cell(0, 8, $rubric->course->course ?? 'N/A', 1, 1);

        $pdf->Cell(50, 8, 'Periodo Académico:', 1);
        $pdf->Cell(0, 8, $rubric->academic_period, 1, 1);


        $pdf->Cell(50, 8, 'Estado:', 1);
        $pdf->Cell(0, 8, $rubric->status, 1, 1);

        // --- Competencias y Resultados ---
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Competencias y Resultados:', 0, 1);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->MultiCell(0, 8, $rubric->competencies_results_grades, 1, 'L');

        // --- Descripción por nivel ---
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Descripción por Nivel:', 0, 1);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->MultiCell(0, 8, $rubric->level_descriptions, 1, 'L');

        // --- Resultados de aprendizaje ---
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Resultados de Aprendizaje y Calificaciones:', 0, 1);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->MultiCell(0, 8, $rubric->resultados_aprendizaje, 1, 'L');

        // --- Niveles de desempeño ---
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Niveles de Desempeño:', 0, 1);

        $niveles = ['Insuficiente', 'Básico', 'Bueno', 'Excelente'];
        $pdf->SetFont('dejavusans', '', 12);

        foreach ($niveles as $nivel) {
            if ($rubric->performance_level === $nivel) {
                switch ($nivel) {
                    case 'Insuficiente':
                        $pdf->SetFillColor(255, 0, 0);
                        $pdf->SetTextColor(255, 255, 255);
                        break;
                    case 'Básico':
                        $pdf->SetFillColor(255, 223, 0);
                        $pdf->SetTextColor(0, 0, 0);
                        break;
                    case 'Bueno':
                        $pdf->SetFillColor(0, 128, 0);
                        $pdf->SetTextColor(255, 255, 255);
                        break;
                    case 'Excelente':
                        $pdf->SetFillColor(0, 102, 204);
                        $pdf->SetTextColor(255, 255, 255);
                        break;
                }
                $fill = true;
                $marca = '★ ';
            } else {
                $pdf->SetFillColor(255, 255, 255);
                $pdf->SetTextColor(0, 0, 0);
                $fill = false;
                $marca = '';
            }

            $pdf->Cell(0, 8, $marca . $nivel, 1, 1, 'L', $fill);
        }

        // --- Salida ---
        $pdf->Output('Rúbrica-' . $rubric->id . '.pdf', 'D'); // descarga directa
    }
}
