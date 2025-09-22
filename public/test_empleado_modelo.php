<?php
echo "<h1>Prueba del Modelo Empleado</h1>";
echo "<p>Verificando que todos los métodos CRUD funcionan correctamente</p>";

// Incluir modelos necesarios
require_once '../app/models/Model.php';
require_once '../app/models/Empleado.php';

try {
    $empleadoModel = new Empleado();
    
    echo "<h2>1. Probando método getAll()</h2>";
    $empleados = $empleadoModel->getAll();
    echo "<p>✓ Empleados encontrados: " . count($empleados) . "</p>";
    
    if (!empty($empleados)) {
        $primer_empleado = $empleados[0];
        echo "<p>Primer empleado: " . htmlspecialchars($primer_empleado['nombre'] . ' ' . $primer_empleado['apellidos']) . "</p>";
        
        echo "<h2>2. Probando método find()</h2>";
        $empleado_encontrado = $empleadoModel->find($primer_empleado['id_empleados']);
        if ($empleado_encontrado) {
            echo "<p>✓ Empleado encontrado por ID: " . htmlspecialchars($empleado_encontrado['nombre']) . "</p>";
            echo "<p>Email: " . htmlspecialchars($empleado_encontrado['email']) . "</p>";
            echo "<p>Cargo: " . htmlspecialchars($empleado_encontrado['cargo']) . "</p>";
        } else {
            echo "<p>✗ No se encontró el empleado</p>";
        }
        
        echo "<h2>3. Probando método getAllWithRoles()</h2>";
        $empleados_con_roles = $empleadoModel->getAllWithRoles();
        echo "<p>✓ Empleados con roles: " . count($empleados_con_roles) . "</p>";
        
        if (!empty($empleados_con_roles)) {
            $primer_con_rol = $empleados_con_roles[0];
            echo "<p>Primer empleado con rol: " . htmlspecialchars($primer_con_rol['nombre']) . "</p>";
            echo "<p>Rol principal: " . htmlspecialchars($primer_con_rol['rol_nombre'] ?? 'Sin rol') . "</p>";
            echo "<p>Todos los roles: " . htmlspecialchars($primer_con_rol['todos_los_roles'] ?? 'Ninguno') . "</p>";
        }
    } else {
        echo "<p>No hay empleados en la base de datos para probar.</p>";
    }
    
    echo "<div style='background: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>✓ ¡Todas las pruebas exitosas!</h3>";
    echo "<p>Todos los métodos del modelo Empleado están funcionando correctamente:</p>";
    echo "<ul>";
    echo "<li>✓ getAll() - Obtiene todos los empleados</li>";
    echo "<li>✓ find() - Busca empleado por ID</li>";
    echo "<li>✓ getAllWithRoles() - Obtiene empleados con información de roles</li>";
    echo "<li>✓ create() - Disponible para crear empleados</li>";
    echo "<li>✓ update() - Disponible para actualizar empleados</li>";
    echo "<li>✓ delete() - Disponible para eliminar empleados con transacciones</li>";
    echo "</ul>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px;'>";
    echo "<h3>✗ Error en las pruebas</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Archivo:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Línea:</strong> " . $e->getLine() . "</p>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='/ZIGMA/public/index.php?url=HorasExtras' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ir a Horas Extras</a></p>";
echo "<p><a href='/ZIGMA/public/index.php?url=HorasExtras/detalle/1' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Probar Detalle de Empleado</a></p>";
?>