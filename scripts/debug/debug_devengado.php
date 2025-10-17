<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/models/Model.php';
require_once __DIR__ . '/../../app/models/Empleado.php';
require_once __DIR__ . '/../../app/models/DevengadoModel.php';

try {
    echo "=== TESTING DEVENGADO MODEL ===" . PHP_EOL;
    
    $devengadoModel = new DevengadoModel();
    $empleadoModel = new Empleado();
    
    // Obtener empleados con sueldo > 0
    $empleados = $empleadoModel->getAll();
    
    foreach ($empleados as $empleado) {
        if (!empty($empleado['sueldo_actual']) && $empleado['sueldo_actual'] > 0) {
            echo "Probando con empleado: " . $empleado['nombre'] . " " . $empleado['apellido'] . " (Sueldo: $" . number_format($empleado['sueldo_actual']) . ")" . PHP_EOL;
            
            try {
                $calculo = $devengadoModel->calcularDevengadoCompleto($empleado['id_empleados']);
                echo "Total devengado: $" . number_format($calculo['resumen']['total_devengado']) . PHP_EOL;
                echo "SUCCESS: DevengadoModel funciona para este empleado" . PHP_EOL;
                break; // Solo probamos con uno
            } catch (Exception $e) {
                echo "Error con empleado " . $empleado['id_empleados'] . ": " . $e->getMessage() . PHP_EOL;
                continue;
            }
        }
    }
    
} catch (Exception $e) {
    echo 'ERROR en DevengadoModel: ' . $e->getMessage() . PHP_EOL;
}
?>
