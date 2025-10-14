<?php
require_once 'config/database.php';
require_once 'app/models/Model.php';
require_once 'app/models/Empleado.php';
require_once 'app/models/DevengadoModel.php';
require_once 'app/models/SeguridadSocialModel.php';
require_once 'app/models/ARLModel.php';

try {
    echo "=== TESTING ARL COMPLETO ===" . PHP_EOL;
    
    // Primero ejecutar el script SQL manualmente, luego:
    $arlModel = new ARLModel();
    $seguridadSocialModel = new SeguridadSocialModel();
    
    // Probar ARL para un empleado específico
    $idEmpleado = 7; // Ana Torres
    $totalDevengado = 4000000;
    $auxilioTransporte = 0;
    
    echo "Probando ARL para empleado $idEmpleado..." . PHP_EOL;
    $calculoARL = $arlModel->calcularARLPorDevengado($idEmpleado, $totalDevengado, $auxilioTransporte);
    
    echo "Código de riesgo: " . $calculoARL['codigo_riesgo'] . PHP_EOL;
    echo "Clase: " . $calculoARL['clase_riesgo'] . PHP_EOL;
    echo "Descripción: " . $calculoARL['nivel_riesgo']['descripcion'] . PHP_EOL;
    echo "Porcentaje: " . $calculoARL['nivel_riesgo']['porcentaje'] . "%" . PHP_EOL;
    echo "Valor ARL: $" . number_format($calculoARL['valor_arl']) . PHP_EOL;
    echo "Fórmula: " . $calculoARL['formula'] . PHP_EOL;
    
    echo PHP_EOL . "=== TESTING SEGURIDAD SOCIAL + ARL ===" . PHP_EOL;
    
    // Probar con todos los empleados
    $calculos = $seguridadSocialModel->calcularSeguridadSocialConARLTodosEmpleados(30);
    
    echo "Total empleados procesados: " . count($calculos) . PHP_EOL;
    
    if (count($calculos) > 0) {
        echo "SUCCESS: Sistema ARL funcionando correctamente" . PHP_EOL;
        foreach (array_slice($calculos, 0, 3) as $calculo) { // Solo primeros 3
            echo "- " . $calculo['empleado']['nombre'] . ": ARL $" . number_format($calculo['arl']['valor_arl'] ?? 0) . PHP_EOL;
        }
    } else {
        echo "ERROR: No se procesaron empleados" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
    echo 'Stack trace: ' . $e->getTraceAsString() . PHP_EOL;
}
?>
