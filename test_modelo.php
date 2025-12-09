<?php
require 'vendor/autoload.php';
require 'core/App.php';
require 'config/database.php';

$app = new \Core\App();

// Test getTotalByEmpleado para empleado 4
$devengadoModel = $app->model('TotalDevengado');
$deducidoModel = $app->model('TotalDeducidoModel');

$empleado_id = 4;

echo "=== TEST MODELOS REPORTE ===\n";
echo "Empleado ID: $empleado_id\n\n";

try {
    $total_dev = $devengadoModel->getTotalByEmpleado($empleado_id);
    echo "Total Devengado: " . number_format($total_dev, 2) . "\n";
} catch (Exception $e) {
    echo "Error en getTotalByEmpleado (devengado): " . $e->getMessage() . "\n";
}

try {
    $total_ded = $deducidoModel->getTotalByEmpleado($empleado_id);
    echo "Total Deducido: " . number_format($total_ded, 2) . "\n";
} catch (Exception $e) {
    echo "Error en getTotalByEmpleado (deducido): " . $e->getMessage() . "\n";
}

echo "\n=== TEST DIRECTO CONCEPTOS ===\n";
$conceptosModel = $app->model('ConceptosAdicionalesModel');
$conceptos = $conceptosModel->obtenerConceptosPorEmpleado($empleado_id);
echo "Conceptos obtenidos: " . count($conceptos) . "\n";
foreach($conceptos as $c) {
    echo "- " . $c['concepto'] . ": " . $c['valor'] . "\n";
}
?>
