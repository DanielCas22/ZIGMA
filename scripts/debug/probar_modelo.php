<?php
// Script para probar el modelo Empleado
require_once __DIR__ . '/../../app/models/Model.php';
require_once __DIR__ . '/../../app/models/Empleado.php';

try {
    echo "<h2>Prueba del Modelo Empleado</h2>";
    
    $empleadoModel = new Empleado();
    $empleados = $empleadoModel->getAll();
    
    echo "<p>Número de empleados obtenidos: <strong>" . count($empleados) . "</strong></p>";
    
    if (!empty($empleados)) {
        echo "<h3>Empleados obtenidos por el modelo:</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Apellidos</th></tr>";
        
        foreach ($empleados as $emp) {
            echo "<tr>";
            echo "<td>" . ($emp['id_empleados'] ?? 'N/A') . "</td>";
            echo "<td>" . ($emp['nombre'] ?? 'N/A') . "</td>";
            echo "<td>" . ($emp['apellidos'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red;'>❌ El modelo no devuelve empleados</p>";
    }
    
    // Mostrar estructura de array
    echo "<h3>Estructura del primer empleado:</h3>";
    echo "<pre>";
    var_dump($empleados[0] ?? 'No hay empleados');
    echo "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
