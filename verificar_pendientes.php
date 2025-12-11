<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== VERIFICANDO HORAS EXTRAS PENDIENTES ===\n\n";
    
    // Ejecutar la consulta corregida
    $sql = 'SELECT he.*, e.nombre, e.apellido, r.nombre as rol,
                   DATE_FORMAT(he.fecha_creacion, "%d/%m/%Y %H:%i") as fecha_creacion_formatted
            FROM horas_extras he 
            INNER JOIN empleados e ON he.empleado_id = e.id_empleados 
            LEFT JOIN user u ON u.empleado_id = e.id_empleados
            LEFT JOIN rol_has_user ru ON ru.user_id = u.id_doc
            LEFT JOIN rol r ON ru.rol_id = r.id_rol
            WHERE he.estado = "pendiente"
            ORDER BY he.fecha_creacion ASC';
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pendientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($pendientes)) {
        echo "No hay horas extras pendientes para aprobación\n";
    } else {
        echo "Horas extras pendientes encontradas: " . count($pendientes) . "\n\n";
        foreach ($pendientes as $p) {
            echo "ID: {$p['id_extras']}\n";
            echo "  Empleado: {$p['nombre']} {$p['apellido']} (ID: {$p['empleado_id']})\n";
            echo "  Rol: " . ($p['rol'] ?? 'Sin rol') . "\n";
            echo "  Cantidad: {$p['cantidad']} horas\n";
            echo "  Tipo: {$p['tipo']}\n";
            echo "  Fecha: {$p['fecha_creacion_formatted']}\n\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
