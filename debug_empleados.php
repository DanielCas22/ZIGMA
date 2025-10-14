<?php
require_once 'config/database.php';
require_once 'app/models/Model.php';
require_once 'app/models/Empleado.php';

try {
    $empleadoModel = new Empleado();
    $empleados = $empleadoModel->getAll();
    
    echo 'Total empleados encontrados: ' . count($empleados) . PHP_EOL;
    
    if (count($empleados) > 0) {
        echo 'Empleados:' . PHP_EOL;
        foreach ($empleados as $emp) {
            echo '- ID: ' . $emp['id_empleados'] . ', Nombre: ' . $emp['nombre'] . ' ' . $emp['apellido'] . ', Sueldo: ' . $emp['sueldo_actual'] . PHP_EOL;
        }
    } else {
        echo 'No se encontraron empleados.' . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
?>
