<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DIAGNÓSTICO DE ROLES DE CLAUDIA ===\n\n";
    
    // Ver qué rol tiene Claudia
    $sql = "SELECT u.id_doc, u.username, u.empleado_id, e.nombre, e.apellido, r.nombre as rol, rhu.rol_id
            FROM empleados e
            LEFT JOIN user u ON u.empleado_id = e.id_empleados
            LEFT JOIN rol_has_user rhu ON rhu.user_id = u.id_doc
            LEFT JOIN rol r ON rhu.rol_id = r.id_rol
            WHERE e.id_empleados = 9";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($resultado as $row) {
        echo "Empleado ID: " . ($row['id_empleados'] ?? 'N/A') . "\n";
        echo "  Nombre: {$row['nombre']} {$row['apellido']}\n";
        echo "  Usuario ID: " . ($row['id_doc'] ?? 'No tiene usuario') . "\n";
        echo "  Username: " . ($row['username'] ?? 'N/A') . "\n";
        echo "  Rol: " . ($row['rol'] ?? 'Sin rol asignado') . "\n";
        echo "  Rol ID: " . ($row['rol_id'] ?? 'N/A') . "\n";
    }
    
    echo "\n=== VERIFICANDO HORAS DE CLAUDIA ===\n";
    $sql = "SELECT he.id_extras, he.estado, he.cantidad, he.tipo 
            FROM horas_extras he
            WHERE he.empleado_id = 9
            ORDER BY he.fecha_creacion DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $horas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Horas extras de Claudia: " . count($horas) . "\n";
    foreach ($horas as $h) {
        echo "  • ID: {$h['id_extras']}, Estado: {$h['estado']}, Cantidad: {$h['cantidad']} ({$h['tipo']})\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
