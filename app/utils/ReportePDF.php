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
}
