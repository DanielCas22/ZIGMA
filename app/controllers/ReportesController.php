<?php
namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Empleado;

class ReportesController extends Controller
{
    public function index()
    {
        // Verifica si el usuario está autenticado
        if (!isset($_SESSION['user'])) {
            header('Location: /Login');
            exit();
        }
        // Carga la vista de reportes
        $this->view('reportes/index');
    }

    public function descargarGeneral()
    {
        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAllWithRoles();
        // Si se solicita Excel
        if (isset($_GET['formato']) && $_GET['formato'] === 'excel') {
            require_once __DIR__ . '/../../vendor/autoload.php';
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // Encabezado con estilo
            $header = ['Empleado', 'Documento', 'Salario', 'Cargo', 'Roles'];
            $sheet->fromArray($header, NULL, 'A1');
            // Estilo de encabezado
            $headerStyle = [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'startColor' => ['rgb' => '4f8cff'],
                    'endColor' => ['rgb' => '6fd6ff'],
                ],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ];
            $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);
            // Bordes y alternancia de color en filas
            $row = 2;
            foreach ($empleados as $emp) {
                $sheet->fromArray([
                    $emp['nombre'] . ' ' . $emp['apellido'],
                    $emp['id_empleados'] ?? '',
                    $emp['sueldo_actual'] ?? '',
                    $emp['rol_nombre'] ?? '',
                    $emp['todos_los_roles'] ?? ''
                ], NULL, 'A' . $row);
                $fillColor = ($row % 2 == 0) ? 'F2F6FC' : 'FFFFFF';
                $sheet->getStyle('A'.$row.':E'.$row)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $fillColor],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'B0B0B0'],
                        ],
                    ],
                ]);
                $row++;
            }
            // Ajustar ancho de columnas
            foreach (range('A', 'E') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            // Congelar encabezado
            $sheet->freezePane('A2');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="reporte_general.xlsx"');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit();
        }
        // Si se solicita PDF
        if (isset($_GET['formato']) && $_GET['formato'] === 'pdf') {
            require_once __DIR__ . '/../../vendor/autoload.php';
            \App\Utils\ReportePDF::generarReporteGeneral($empleados);
            exit();
        }
        // CSV mejorado: separador punto y coma, encabezado, UTF-8 BOM
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="reporte_general.csv"');
        echo "\xEF\xBB\xBF"; // BOM para Excel
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Empleado', 'Documento', 'Salario', 'Cargo', 'Roles'], ';');
        foreach ($empleados as $emp) {
            fputcsv($output, [
                $emp['nombre'] . ' ' . $emp['apellido'],
                $emp['id_empleados'] ?? '',
                $emp['sueldo_actual'] ?? '',
                $emp['rol_nombre'] ?? '',
                $emp['todos_los_roles'] ?? ''
            ], ';');
        }
        fclose($output);
        exit();
    }
}
