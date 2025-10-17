<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/Model.php';
require_once __DIR__ . '/../../app/models/Empleado.php';
require_once __DIR__ . '/../../app/models/DevengadoModel.php';
require_once __DIR__ . '/../../app/models/SeguridadSocialModel.php';
require_once __DIR__ . '/../../app/models/ARLModel.php';

try {
    echo "=== TESTING SEGURIDAD SOCIAL MODEL ===" . PHP_EOL;
    
    $seguridadSocialModel = new SeguridadSocialModel();
    
    // Probar calcular para todos los empleados
    $calculos = $seguridadSocialModel->calcularSeguridadSocialConARLTodosEmpleados(30);
    
    echo "Total cálculos generados: " . count($calculos) . PHP_EOL;
    
    if (count($calculos) > 0) {
        echo "SUCCESS: SeguridadSocialModel funciona" . PHP_EOL;
        foreach ($calculos as $calculo) {
            echo "- " . $calculo['empleado']['nombre'] . " " . $calculo['empleado']['apellido'] . ": $" . number_format($calculo['total_devengado']) . PHP_EOL;
        }
    } else {
        echo "ERROR: No se generaron cálculos" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo 'ERROR en SeguridadSocialModel: ' . $e->getMessage() . PHP_EOL;
    echo 'Stack trace: ' . $e->getTraceAsString() . PHP_EOL;
}
?>
