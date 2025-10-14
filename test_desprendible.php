<?php
require_once 'config/database.php';
require_once 'app/models/Model.php';
require_once 'app/models/DesprendibleModel.php';

try {
    echo "=== TESTING DESPRENDIBLE MODEL ===" . PHP_EOL;
    
    $desprendibleModel = new DesprendibleModel();
    
    // Probar obtener empleados para desprendible
    $empleados = $desprendibleModel->obtenerEmpleadosParaDesprendible();
    
    echo "Empleados disponibles para desprendible: " . count($empleados) . PHP_EOL;
    
    if (count($empleados) > 0) {
        echo "SUCCESS: DesprendibleModel puede obtener empleados" . PHP_EOL;
        
        // Probar con el primer empleado
        $empleado = $empleados[0];
        $empleadoId = $empleado['id_empleados'] ?? $empleado['id'];
        
        echo "Probando desprendible para empleado ID: $empleadoId" . PHP_EOL;
        
        $desprendible = $desprendibleModel->obtenerDesprendible($empleadoId, '09', '2025');
        
        if ($desprendible) {
            echo "SUCCESS: Se generó desprendible correctamente" . PHP_EOL;
        } else {
            echo "WARNING: No se pudo generar desprendible (puede faltar información)" . PHP_EOL;
        }
    } else {
        echo "ERROR: No hay empleados disponibles" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
?>
