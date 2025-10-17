<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/Model.php';
require_once __DIR__ . '/../../app/models/Empleado.php';
require_once __DIR__ . '/../../app/models/DevengadoModel.php';
require_once __DIR__ . '/../../app/models/SeguridadSocialModel.php';

try {
    echo "=== TESTING VERSIÓN TEMPORAL SIN ARL ===" . PHP_EOL;
    
    $seguridadSocialModel = new SeguridadSocialModel();
    
    // Probar calcular para todos los empleados sin ARL
    $calculos = $seguridadSocialModel->calcularSeguridadSocialConARLTodosEmpleadosTemporal(30);
    
    echo "Total cálculos generados: " . count($calculos) . PHP_EOL;
    
    if (count($calculos) > 0) {
        echo "SUCCESS: SeguridadSocialModel funciona (versión temporal)" . PHP_EOL;
        foreach ($calculos as $calculo) {
            echo "- " . $calculo['empleado']['nombre'] . " " . $calculo['empleado']['apellido'] . ": $" . number_format($calculo['total_devengado']) . PHP_EOL;
        }
    } else {
        echo "ERROR: No se generaron cálculos" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
?>
