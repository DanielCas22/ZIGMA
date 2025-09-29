<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/database.php';
require_once 'app/models/Model.php';
require_once 'app/models/Empleado.php';

echo "<h2>🧪 Test de Modelo Empleado</h2>\n";

try {
    $empleadoModel = new Empleado();
    $empleados = $empleadoModel->getAll();
    
    echo "<h3>✅ Empleados obtenidos del modelo:</h3>\n";
    echo "<ul>\n";
    foreach ($empleados as $empleado) {
        echo "<li>ID: {$empleado['id_empleados']} - {$empleado['nombre']} {$empleado['apellido']} - Sueldo: $" . number_format($empleado['sueldo_actual'] ?? 0) . "</li>\n";
    }
    echo "</ul>\n";
    
    echo "<p><strong>Total empleados encontrados:</strong> " . count($empleados) . "</p>\n";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>\n";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>\n";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>\n";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>\n";
    echo "<pre>" . $e->getTraceAsString() . "</pre>\n";
    echo "</div>\n";
}
?>