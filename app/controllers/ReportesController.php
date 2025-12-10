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
        // Solo administradores pueden descargar reporte general
        if (!isset($_SESSION['user']) || (isset($_SESSION['user']['rol']) && $_SESSION['user']['rol'] !== 'admin')) {
            header('Location: /Login');
            exit();
        }

        $empleadoModel = new Empleado();
        $empleados = $empleadoModel->getAllWithRoles();
        // Filtrar empleados especiales
        $empleados = array_filter($empleados, function($emp) {
            $nombre = trim(mb_strtolower($emp['nombre']));
            $apellido = trim(mb_strtolower($emp['apellido']));
            if (($nombre === 'administrador' && $apellido === 'del sistema') ||
                ($nombre === 'coordinador' && $apellido === 'rrhh') ||
                ($nombre === 'empleado' && $apellido === 'general')) {
                return false;
            }
            return true;
        });
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

    public function reporteEmpleado() {
        if (!isset($_SESSION['user'])) {
            header('Location: /Login');
            exit();
        }
        $empleadoModel = $this->model('Empleado');
        $empleados = $empleadoModel->getAllWithRoles();
        $empleado_id = isset($_GET['empleado_id']) ? intval($_GET['empleado_id']) : null;
        $resumen = null;
        // Si el usuario es empleado, solo puede ver su propio reporte
        if (isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'empleado') {
            $empleado_id = $_SESSION['user']['empleado_id'];
            // Filtrar lista de empleados para mostrar solo el propio
            $empleados = array_filter($empleados, function($emp) use ($empleado_id) {
                return $emp['id_empleados'] == $empleado_id;
            });
        }
        if ($empleado_id) {
            $empleado = $empleadoModel->getByIdWithRoles($empleado_id);
            $devengadoModel = $this->model('DevengadoModel');
            $deducidoModel = $this->model('TotalDeducidoModel');
            $horasExtrasModel = $this->model('HorasExtras');
            
            try {
                $devengadoCompleto = $devengadoModel->calcularDevengadoCompleto($empleado_id);
                $total_devengado = $devengadoCompleto['resumen']['total_devengado'];
            } catch (\Exception $e) {
                error_log("Error calculando devengado: " . $e->getMessage());
                $total_devengado = 0;
            }
            
            try {
                $deducidoCompleto = $deducidoModel->calcularTotalDeducidoCompleto($empleado_id);
                $total_deducido = $deducidoCompleto['resumen']['total_deducciones'];
            } catch (\Exception $e) {
                error_log("Error calculando deducido: " . $e->getMessage());
                $total_deducido = 0;
            }
            
            $horas_extras = $horasExtrasModel->getHorasExtrasByEmpleado($empleado_id);
            $total_horas = 0;
            $total_valor = 0;
            foreach ($horas_extras as $he) {
                if (isset($he['estado']) && strtolower($he['estado']) !== 'rechazado') {
                    $total_horas += floatval($he['cantidad']);
                    $total_valor += floatval($he['valor']);
                }
            }
            $resumen = [
                'empleado' => $empleado,
                'total_devengado' => $total_devengado,
                'total_deducido' => $total_deducido,
                'total_horas_extras' => $total_horas,
                'total_valor_extras' => $total_valor,
                'horas_extras' => $horas_extras
            ];
        }
        $this->view('reportes/reporte_empleado', [
            'empleados' => $empleados,
            'resumen' => $resumen
        ]);
    }

    public function descargarEmpleado() {
        if (!isset($_SESSION['user'])) {
            header('Location: /Login');
            exit();
        }
        $empleado_id = isset($_GET['empleado_id']) ? intval($_GET['empleado_id']) : null;
        // Si el usuario es empleado, solo puede descargar su propio reporte
        if (isset($_SESSION['user']) && $_SESSION['user']['rol'] === 'empleado') {
            $empleado_id = $_SESSION['user']['empleado_id'];
        }
        if (!$empleado_id) {
            header('Location: /ZIGMA/public/index.php?url=Reportes/reporteEmpleado');
            exit();
        }
        $empleadoModel = $this->model('Empleado');
        $devengadoModel = $this->model('DevengadoModel');
        $deducidoModel = $this->model('TotalDeducidoModel');
        $horasExtrasModel = $this->model('HorasExtras');
        $empleado = $empleadoModel->getByIdWithRoles($empleado_id);
        
        try {
            $devengadoCompleto = $devengadoModel->calcularDevengadoCompleto($empleado_id);
            $total_devengado = $devengadoCompleto['resumen']['total_devengado'];
        } catch (\Exception $e) {
            error_log("Error calculando devengado: " . $e->getMessage());
            $total_devengado = 0;
        }
        
        try {
            $deducidoCompleto = $deducidoModel->calcularTotalDeducidoCompleto($empleado_id);
            $total_deducido = $deducidoCompleto['resumen']['total_deducciones'];
        } catch (\Exception $e) {
            error_log("Error calculando deducido: " . $e->getMessage());
            $total_deducido = 0;
        }
        
        $horas_extras = $horasExtrasModel->getHorasExtrasByEmpleado($empleado_id);
        $total_horas = 0;
        $total_valor = 0;
        foreach ($horas_extras as $he) {
            if (isset($he['estado']) && strtolower($he['estado']) !== 'rechazado') {
                $total_horas += floatval($he['cantidad']);
                $total_valor += floatval($he['valor']);
            }
        }
        // PDF
        if (isset($_GET['formato']) && $_GET['formato'] === 'pdf') {
            require_once __DIR__ . '/../../vendor/autoload.php';
            \App\Utils\ReportePDF::generarReporteEmpleado([
                'empleado' => $empleado,
                'total_devengado' => $total_devengado,
                'total_deducido' => $total_deducido,
                'total_horas_extras' => $total_horas,
                'total_valor_extras' => $total_valor,
                'horas_extras' => $horas_extras
            ]);
            exit();
        }
        // Excel
        if (isset($_GET['formato']) && $_GET['formato'] === 'excel') {
            require_once __DIR__ . '/../../vendor/autoload.php';
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            // Encabezado principal
            $sheet->fromArray(['Empleado', 'Total Devengado', 'Total Deducido', 'Total Horas Extras', 'Valor Horas Extras'], NULL, 'A1');
            $sheet->getStyle('A1:E1')->getFont()->setBold(true);
            $sheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('223A5E');
            $sheet->getStyle('A1:E1')->getFont()->getColor()->setRGB('FFFFFF');
            $sheet->fromArray([
                $empleado['nombre'] . ' ' . $empleado['apellido'],
                $total_devengado,
                $total_deducido,
                $total_horas,
                $total_valor
            ], NULL, 'A2');
            // Encabezado detalle
            $sheet->fromArray(['Fecha', 'Tipo', 'Cantidad', 'Valor', 'Estado'], NULL, 'A4');
            $sheet->getStyle('A4:E4')->getFont()->setBold(true);
            $sheet->getStyle('A4:E4')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('2E5C9A');
            $sheet->getStyle('A4:E4')->getFont()->getColor()->setRGB('FFFFFF');
            $row = 5;
            foreach ($horas_extras as $he) {
                $sheet->fromArray([
                    $he['dia'] . '/' . $he['mes'] . '/' . $he['anio'],
                    $he['tipo'],
                    $he['cantidad'],
                    $he['valor'],
                    $he['estado']
                ], NULL, 'A' . $row);
                // Bordes para cada fila de detalle
                $sheet->getStyle('A'.$row.':E'.$row)->getBorders()->getAllBorders()->setBorderStyle(
                    \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                );
                $row++;
            }
            // Bordes para encabezados
            $sheet->getStyle('A1:E2')->getBorders()->getAllBorders()->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );
            $sheet->getStyle('A4:E4')->getBorders()->getAllBorders()->setBorderStyle(
                \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            );
            // Ajuste de columnas
            foreach (range('A', 'E') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
                $sheet->getStyle($col.'1:'.$col.$row)->getAlignment()->setHorizontal(
                    \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                );
            }
            $sheet->getStyle('A1:E'.$row)->getFont()->setSize(11);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="reporte_empleado.xlsx"');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit();
        }
        // Si no formato, redirigir
        header('Location: /ZIGMA/public/index.php?url=Reportes/reporteEmpleado&empleado_id=' . $empleado_id);
        exit();
    }

    public function reporteNomina() {
        // Solo administradores y coordinadores RRHH pueden ver reporte de nómina
        if (!isset($_SESSION['user'])) {
            header('Location: /Login');
            exit();
        }

        $userRole = isset($_SESSION['user']['rol']) ? $_SESSION['user']['rol'] : 'empleado';
        
        // Si es empleado, no puede acceder a reportes de nómina
        if ($userRole === 'empleado') {
            header('Location: /ZIGMA/public/index.php?url=Reportes');
            exit();
        }

        $empleadoModel = $this->model('Empleado');
        $devengadoModel = $this->model('DevengadoModel');
        $deducidoModel = $this->model('TotalDeducidoModel');
        $nominaData = [];
        $total_nomina = 0;
        $total_devengado = 0;
        $total_deducido = 0;
        $empleados = $empleadoModel->getAllWithRoles();
        foreach ($empleados as $emp) {
            if (in_array($emp['id_empleados'], [1,2,3])) continue;
            
            try {
                $devengadoCompleto = $devengadoModel->calcularDevengadoCompleto($emp['id_empleados']);
                $dev = $devengadoCompleto['resumen']['total_devengado'];
            } catch (\Exception $e) {
                error_log("Error calculando devengado para empleado {$emp['id_empleados']}: " . $e->getMessage());
                $dev = 0;
            }
            
            try {
                $deducidoCompleto = $deducidoModel->calcularTotalDeducidoCompleto($emp['id_empleados']);
                $ded = $deducidoCompleto['resumen']['total_deducciones'];
            } catch (\Exception $e) {
                error_log("Error calculando deducido para empleado {$emp['id_empleados']}: " . $e->getMessage());
                $ded = 0;
            }
            
            $horasExtrasModel = $this->model('HorasExtras');
            $horas_extras = $horasExtrasModel->getHorasExtrasByEmpleado($emp['id_empleados']);
            $total_horas = 0;
            $total_valor = 0;
            foreach ($horas_extras as $he) {
                if (isset($he['estado']) && strtolower($he['estado']) !== 'rechazado') {
                    $total_horas += floatval($he['cantidad']);
                    $total_valor += floatval($he['valor']);
                }
            }
            $valor_pagar = $dev - $ded;
            $nominaData[] = [
                'nombre' => $emp['nombre'],
                'apellido' => $emp['apellido'],
                'devengado' => $dev,
                'deducido' => $ded,
                'valor_pagar' => $valor_pagar,
                'total_horas' => $total_horas,
                'total_valor_horas' => $total_valor
            ];
            $total_nomina += $valor_pagar;
            $total_devengado += $dev;
            $total_deducido += $ded;
        }
        $estadisticas = [
            'total_nomina' => $total_nomina,
            'total_devengado' => $total_devengado,
            'total_deducido' => $total_deducido,
            'promedio_nomina' => count($nominaData) ? $total_nomina / count($nominaData) : 0
        ];
        $this->view('reportes/reporte_nomina', [
            'nomina' => $nominaData,
            'estadisticas' => $estadisticas
        ]);
    }

    public function descargarNomina() {
        // Solo administradores y coordinadores RRHH pueden descargar reporte de nómina
        if (!isset($_SESSION['user'])) {
            header('Location: /Login');
            exit();
        }

        $userRole = isset($_SESSION['user']['rol']) ? $_SESSION['user']['rol'] : 'empleado';
        
        // Si es empleado, no puede descargar reporte de nómina
        if ($userRole === 'empleado') {
            header('Location: /ZIGMA/public/index.php?url=Reportes');
            exit();
        }

        $formato = isset($_GET['formato']) ? strtolower($_GET['formato']) : 'pdf';
        $empleadoModel = $this->model('Empleado');
        $devengadoModel = $this->model('DevengadoModel');
        $deducidoModel = $this->model('TotalDeducidoModel');
        $nominaData = [];
        $total_nomina = 0;
        $total_devengado = 0;
        $total_deducido = 0;
        $horasExtrasModel = $this->model('HorasExtras');
        $empleados = $empleadoModel->getAllWithRoles();
        foreach ($empleados as $emp) {
            if (in_array($emp['id_empleados'], [1,2,3])) continue;
            
            try {
                $devengadoCompleto = $devengadoModel->calcularDevengadoCompleto($emp['id_empleados']);
                $dev = $devengadoCompleto['resumen']['total_devengado'];
            } catch (\Exception $e) {
                error_log("Error calculando devengado para empleado {$emp['id_empleados']}: " . $e->getMessage());
                $dev = 0;
            }
            
            try {
                $deducidoCompleto = $deducidoModel->calcularTotalDeducidoCompleto($emp['id_empleados']);
                $ded = $deducidoCompleto['resumen']['total_deducciones'];
            } catch (\Exception $e) {
                error_log("Error calculando deducido para empleado {$emp['id_empleados']}: " . $e->getMessage());
                $ded = 0;
            }
            
            $horas_extras = $horasExtrasModel->getHorasExtrasByEmpleado($emp['id_empleados']);
            $total_horas = 0;
            $total_valor = 0;
            foreach ($horas_extras as $he) {
                if (isset($he['estado']) && strtolower($he['estado']) !== 'rechazado') {
                    $total_horas += floatval($he['cantidad']);
                    $total_valor += floatval($he['valor']);
                }
            }
            $valor_pagar = $dev - $ded;
            $nominaData[] = [
                'nombre' => $emp['nombre'],
                'apellido' => $emp['apellido'],
                'devengado' => $dev,
                'deducido' => $ded,
                'valor_pagar' => $valor_pagar,
                'total_horas' => $total_horas,
                'total_valor_horas' => $total_valor
            ];
            $total_nomina += $valor_pagar;
            $total_devengado += $dev;
            $total_deducido += $ded;
        }
        $estadisticas = [
            'total_nomina' => $total_nomina,
            'total_devengado' => $total_devengado,
            'total_deducido' => $total_deducido,
            'promedio_nomina' => count($nominaData) ? $total_nomina / count($nominaData) : 0
        ];
        if ($formato === 'excel') {
            require_once __DIR__ . '/../utils/ReporteExcel.php';
            $excel = new \App\Utils\ReporteExcel();
            $excel->generarReporteNomina($nominaData, $estadisticas);
        } else {
            require_once __DIR__ . '/../utils/ReportePDF.php';
            $pdf = new \App\Utils\ReportePDF();
            $pdf->generarReporteNomina($nominaData, $estadisticas);
        }
        exit;
    }
}
