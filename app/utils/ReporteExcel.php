<?php
namespace App\Utils;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReporteExcel {
    public function generarReporteNomina($nominaData, $estadisticas) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Nómina General');
        // Encabezado
        $sheet->setCellValue('A1', 'Reporte General de Nómina');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->setCellValue('A3', 'Total Nómina Pagada:');
        $sheet->setCellValue('B3', $estadisticas['total_nomina']);
        $sheet->setCellValue('A4', 'Total Devengado:');
        $sheet->setCellValue('B4', $estadisticas['total_devengado']);
        $sheet->setCellValue('A5', 'Total Deducido:');
        $sheet->setCellValue('B5', $estadisticas['total_deducido']);
        $sheet->setCellValue('A6', 'Promedio por Empleado:');
        $sheet->setCellValue('B6', $estadisticas['promedio_nomina']);
        // Tabla detalle
        $sheet->setCellValue('A8', 'Empleado');
        $sheet->setCellValue('B8', 'Devengado');
        $sheet->setCellValue('C8', 'Deducido');
        $sheet->setCellValue('D8', 'Valor a Pagar');
        $row = 9;
        foreach ($nominaData as $emp) {
            $sheet->setCellValue('A'.$row, $emp['nombre'].' '.$emp['apellido']);
            $sheet->setCellValue('B'.$row, $emp['devengado']);
            $sheet->setCellValue('C'.$row, $emp['deducido']);
            $sheet->setCellValue('D'.$row, $emp['valor_pagar']);
            $row++;
        }
        // Formato
        foreach (['B','C','D'] as $col) {
            $sheet->getStyle($col.'9:'.$col.($row-1))->getNumberFormat()->setFormatCode('#,##0');
        }
        $sheet->getStyle('A8:D8')->getFont()->setBold(true);
        // Descargar
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reporte_nomina.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
