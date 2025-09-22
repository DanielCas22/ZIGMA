<?php
// Script para probar la eliminación de empleados
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/Model.php';
require_once __DIR__ . '/app/models/Empleado.php';

try {
    echo "<h2>Prueba de Eliminación de Empleados</h2>";
    
    $database = new Database();
    $empleadoModel = new Empleado();
    
    echo "<h3>Empleados actuales:</h3>";
    $empleados = $empleadoModel->getAll();
    
    if (empty($empleados)) {
        echo "<p>No hay empleados para mostrar.</p>";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nombre</th><th>Apellidos</th></tr>";
        foreach ($empleados as $emp) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($emp['id_empleados']) . "</td>";
            echo "<td>" . htmlspecialchars($emp['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($emp['apellidos']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<h3>Verificación de Estructura de BD:</h3>";
    
    // Verificar horas extras
    $db = $database->getConnection();
    $stmt = $db->query("SELECT COUNT(*) as total FROM horas_extras");
    $horasExtras = $stmt->fetch();
    echo "<p>Total horas extras: " . $horasExtras['total'] . "</p>";
    
    // Verificar usuarios
    $stmt = $db->query("SELECT COUNT(*) as total FROM user");
    $users = $stmt->fetch();
    echo "<p>Total usuarios: " . $users['total'] . "</p>";
    
    // Verificar roles_has_user
    $stmt = $db->query("SELECT COUNT(*) as total FROM rol_has_user");
    $rolesUsers = $stmt->fetch();
    echo "<p>Total asignaciones de roles: " . $rolesUsers['total'] . "</p>";
    
    echo "<p style='color: green;'>✅ Sistema listo para pruebas de eliminación</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>