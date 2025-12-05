<?php
namespace App\Utils;

use App\Models\Empleado;
use TCPDF;

class ReportePDF
{
    public static function generarReporteGeneral($empleados)
    {
        // Crear PDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        // Paleta empresarial: azul oscuro, gris, blanco, acento azul claro
        $colorHeaderBg = '#223A5E';
        $colorHeaderText = '#FFFFFF';
        $colorTableHeader = '#2E5C9A';
        $colorTableRowEven = '#F4F8FB';
        $colorTableRowOdd = '#FFFFFF';
        $colorBorder = '#B0B8C1';
        // Logo y encabezado
        $logo = __DIR__ . '/../../public/img/logo_zigma.jpg';
        $pdf->SetCreator('ZIGMA');
        $pdf->SetAuthor('ZIGMA');
        $pdf->SetTitle('Reporte General de Nómina');
        $pdf->SetHeaderData($logo, 32, 'Reporte General de Nómina', "ZIGMA | Nómina y Empleados", array(34,58,94), array(255,255,255));
        $pdf->setHeaderFont(['helvetica', 'B', 14]);
        $pdf->setFooterFont(['helvetica', '', 10]);
        $pdf->SetMargins(12, 28, 12);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(TRUE, 18);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);
        // Tabla con estilos (usando estilos inline para compatibilidad TCPDF)
        $tbl = '<table cellpadding="5" cellspacing="0" width="100%">';
        // ENCABEZADO DE COLUMNAS
        $tbl .= '<thead><tr>';
        $tbl .= '<th style="background-color:#2E5C9A;color:#fff;font-size:12px;font-weight:bold;border:1px solid #B0B8C1;">Empleado</th>';
        $tbl .= '<th style="background-color:#2E5C9A;color:#fff;font-size:12px;font-weight:bold;border:1px solid #B0B8C1;">Documento</th>';
        $tbl .= '<th style="background-color:#2E5C9A;color:#fff;font-size:12px;font-weight:bold;border:1px solid #B0B8C1;">Salario</th>';
        $tbl .= '<th style="background-color:#2E5C9A;color:#fff;font-size:12px;font-weight:bold;border:1px solid #B0B8C1;">Cargo</th>';
        $tbl .= '<th style="background-color:#2E5C9A;color:#fff;font-size:12px;font-weight:bold;border:1px solid #B0B8C1;">Roles</th>';
        $tbl .= '</tr></thead><tbody>';
        $rowNum = 0;
        foreach ($empleados as $emp) {
            $rowColor = ($rowNum % 2 == 0) ? '#F4F8FB' : '#FFFFFF';
            $tbl .= '<tr style="background-color:' . $rowColor . ';">';
            $tbl .= '<td style="border:1px solid #B0B8C1;font-size:11px;">' . htmlspecialchars($emp['nombre'] . ' ' . $emp['apellido']) . '</td>';
            $tbl .= '<td style="border:1px solid #B0B8C1;font-size:11px;">' . htmlspecialchars($emp['id_empleados'] ?? '') . '</td>';
            $tbl .= '<td style="border:1px solid #B0B8C1;font-size:11px;">$ ' . number_format($emp['sueldo_actual'] ?? 0, 0, ',', '.') . '</td>';
            $tbl .= '<td style="border:1px solid #B0B8C1;font-size:11px;">' . htmlspecialchars($emp['rol_nombre'] ?? '') . '</td>';
            $tbl .= '<td style="border:1px solid #B0B8C1;font-size:11px;">' . htmlspecialchars($emp['todos_los_roles'] ?? '') . '</td>';
            $tbl .= '</tr>';
            $rowNum++;
        }
        $tbl .= '</tbody></table>';
        // Título grande
        $pdf->SetY(38);
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(34,58,94);
        $pdf->Cell(0, 12, 'Resumen General de Nómina y Empleados', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(2);
        $pdf->writeHTML($tbl, true, false, false, false, '');
        $pdf->Output('reporte_general.pdf', 'D');
        exit();
    }

    public static function generarReporteEmpleado($resumen)
    {
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $logo = __DIR__ . '/../../public/img/logo_zigma.jpg';
        $pdf->SetCreator('ZIGMA');
        $pdf->SetAuthor('ZIGMA');
        $pdf->SetTitle('Reporte Individual de Empleado');
        $pdf->SetHeaderData($logo, 32, 'Reporte Individual de Empleado', "ZIGMA | Nómina y Empleados", array(34,58,94), array(255,255,255));
        $pdf->setHeaderFont(['helvetica', 'B', 14]);
        $pdf->setFooterFont(['helvetica', '', 10]);
        $pdf->SetMargins(12, 28, 12);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(TRUE, 18);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11);
        $empleado = $resumen['empleado'];
        $pdf->SetY(38);
        $pdf->SetFont('helvetica', 'B', 15);
        $pdf->SetTextColor(34,58,94);
        $pdf->Cell(0, 12, 'Resumen de ' . $empleado['nombre'] . ' ' . $empleado['apellido'], 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(0,0,0);
        $tbl = '<table cellpadding="5" cellspacing="0" width="100%">';
        $tbl .= '<tr><td><strong>Total Devengado:</strong></td><td>$' . number_format($resumen['total_devengado'], 0, ',', '.') . '</td></tr>';
        $tbl .= '<tr><td><strong>Total Deducido:</strong></td><td>$' . number_format($resumen['total_deducido'], 0, ',', '.') . '</td></tr>';
        $tbl .= '<tr><td><strong>Total Horas Extras:</strong></td><td>' . number_format($resumen['total_horas_extras'], 1) . ' horas ($' . number_format($resumen['total_valor_extras'], 0, ',', '.') . ')</td></tr>';
        $tbl .= '</table>';
        $pdf->Ln(2);
        $pdf->writeHTML($tbl, true, false, false, false, '');
        $pdf->Ln(4);
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->Cell(0, 10, 'Detalle de Horas Extras', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $tbl2 = '<table border="1" cellpadding="4" cellspacing="0" width="100%">';
        $tbl2 .= '<thead><tr style="background-color:#2E5C9A;color:#fff;">';
        $tbl2 .= '<th>Fecha</th><th>Tipo</th><th>Cantidad</th><th>Valor</th><th>Estado</th>';
        $tbl2 .= '</tr></thead><tbody>';
        foreach ($resumen['horas_extras'] as $he) {
            $tbl2 .= '<tr>';
            $tbl2 .= '<td>' . htmlspecialchars($he['dia'] . '/' . $he['mes'] . '/' . $he['anio']) . '</td>';
            $tbl2 .= '<td>' . htmlspecialchars($he['tipo']) . '</td>';
            $tbl2 .= '<td>' . htmlspecialchars($he['cantidad']) . '</td>';
            $tbl2 .= '<td>$' . number_format($he['valor'], 0, ',', '.') . '</td>';
            $tbl2 .= '<td>' . htmlspecialchars($he['estado']) . '</td>';
            $tbl2 .= '</tr>';
        }
        $tbl2 .= '</tbody></table>';
        $pdf->writeHTML($tbl2, true, false, false, false, '');
        $pdf->Output('reporte_empleado.pdf', 'D');
        exit();
    }

    public function generarReporteNomina($nominaData, $estadisticas) {
        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $logo = __DIR__ . '/../../public/img/logo_zigma.jpg';
        $pdf->SetCreator('ZIGMA');
        $pdf->SetAuthor('ZIGMA');
        $pdf->SetTitle('Reporte General de Nómina');
        $pdf->SetHeaderData($logo, 32, 'Reporte General de Nómina', "ZIGMA | Nómina y Empleados", array(34,58,94), array(255,255,255));
        $pdf->setHeaderFont(['helvetica', 'B', 14]);
        $pdf->setFooterFont(['helvetica', '', 10]);
        $pdf->SetMargins(12, 28, 12);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(TRUE, 18);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(34,58,94);
        $pdf->Cell(0, 12, 'Reporte General de Nómina', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(0,0,0);
        $pdf->Ln(2);
        // Estadísticas
        $tbl = '<table cellpadding="5" cellspacing="0" width="60%">';
        $tbl .= '<tr><td><strong>Total Nómina Pagada:</strong></td><td>$' . number_format($estadisticas['total_nomina'], 0, ',', '.') . '</td></tr>';
        $tbl .= '<tr><td><strong>Total Devengado:</strong></td><td>$' . number_format($estadisticas['total_devengado'], 0, ',', '.') . '</td></tr>';
        $tbl .= '<tr><td><strong>Total Deducido:</strong></td><td>$' . number_format($estadisticas['total_deducido'], 0, ',', '.') . '</td></tr>';
        $tbl .= '<tr><td><strong>Promedio por Empleado:</strong></td><td>$' . number_format($estadisticas['promedio_nomina'], 0, ',', '.') . '</td></tr>';
        $tbl .= '</table>';
        $pdf->writeHTML($tbl, true, false, false, false, '');
        $pdf->Ln(4);
        // Detalle de nómina
        $pdf->SetFont('helvetica', 'B', 13);
        $pdf->Cell(0, 10, 'Detalle de Nómina por Empleado', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);
        $tbl2 = '<table border="1" cellpadding="4" cellspacing="0" width="100%">';
        $tbl2 .= '<thead><tr style="background-color:#2E5C9A;color:#fff;">';
        $tbl2 .= '<th>Empleado</th><th>Devengado</th><th>Deducido</th><th>Valor a Pagar</th>';
        $tbl2 .= '</tr></thead><tbody>';
        foreach ($nominaData as $emp) {
            $tbl2 .= '<tr>';
            $tbl2 .= '<td>' . htmlspecialchars($emp['nombre'] . ' ' . $emp['apellido']) . '</td>';
            $tbl2 .= '<td>$' . number_format($emp['devengado'], 0, ',', '.') . '</td>';
            $tbl2 .= '<td>$' . number_format($emp['deducido'], 0, ',', '.') . '</td>';
            $tbl2 .= '<td>$' . number_format($emp['valor_pagar'], 0, ',', '.') . '</td>';
            $tbl2 .= '</tr>';
        }
        $tbl2 .= '</tbody></table>';
        $pdf->writeHTML($tbl2, true, false, false, false, '');
        $pdf->Output('reporte_nomina.pdf', 'D');
        exit();
    }
}
