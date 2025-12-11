<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DIAGNÓSTICO DE HORAS EXTRAS ===\n\n";
    
    // Ver horas extras pendientes
    echo "1. Horas Extras Pendientes:\n";
    $stmt = $pdo->query("SELECT he.id_extras, he.empleado_id, e.nombre, e.apellido, he.estado, he.fecha_creacion
                         FROM horas_extras he
                         JOIN empleados e ON he.empleado_id = e.id_empleados
                         WHERE he.estado = 'pendiente'
                         ORDER BY he.fecha_creacion DESC");
    
    $pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($pendientes)) {
        echo "   No hay horas extras pendientes\n";
    } else {
        foreach ($pendientes as $p) {
            echo "   • ID: {$p['id_extras']}, Empleado: {$p['nombre']} {$p['apellido']} (ID: {$p['empleado_id']}), Estado: {$p['estado']}\n";
        }
    }
    
    echo "\n2. Empleados con sus IDs:\n";
    $stmt = $pdo->query("SELECT id_empleados, nombre, apellido FROM empleados ORDER BY id_empleados");
    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($empleados as $e) {
        echo "   • ID: {$e['id_empleados']}, Nombre: {$e['nombre']} {$e['apellido']}\n";
    }
    
    echo "\n3. Usuarios con sus empleado_id:\n";
    $stmt = $pdo->query("SELECT id_doc, username, empleado_id FROM user ORDER BY id_doc");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($usuarios as $u) {
        echo "   • ID: {$u['id_doc']}, Username: {$u['username']}, Empleado ID: {$u['empleado_id']}\n";
    }
    
    echo "\n4. Notificaciones pendientes:\n";
    $stmt = $pdo->query("SELECT id, usuario_id, tipo, leida, fecha_creacion FROM notificaciones WHERE leida = 0 LIMIT 10");
    $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($notificaciones)) {
        echo "   No hay notificaciones pendientes\n";
    } else {
        foreach ($notificaciones as $n) {
            echo "   • Usuario ID: {$n['usuario_id']}, Tipo: {$n['tipo']}, Leída: {$n['leida']}\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
