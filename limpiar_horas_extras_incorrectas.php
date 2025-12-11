<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== LIMPIEZA DE HORAS EXTRAS INCORRECTAS ===\n\n";
    
    // Borrar las horas extras que están mal asignadas
    // Las que están asignadas a empleados con rol RRHH o Admin cuando no deberían
    echo "Eliminando horas extras de empleados con rol admin/rrhh...\n";
    
    $sql = "DELETE FROM horas_extras 
            WHERE empleado_id IN (
                SELECT e.id_empleados
                FROM empleados e
                LEFT JOIN rol_has_user ru ON ru.user_id = (SELECT id_doc FROM user WHERE empleado_id = e.id_empleados)
                LEFT JOIN rol r ON ru.rol_id = r.id_rol
                WHERE LOWER(r.nombre) IN ('admin', 'rrhh')
                AND e.id_empleados IN (1, 2)
            )";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $deleted = $pdo->query("SELECT ROW_COUNT()")->fetchColumn();
    echo "  ✓ $deleted registros eliminados\n";
    
    // Limpiar notificaciones huérfanas (sin usuario correspondiente)
    echo "\nEliminando notificaciones huérfanas...\n";
    $sql = "DELETE FROM notificaciones 
            WHERE usuario_id NOT IN (SELECT id_doc FROM user)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    echo "  ✓ Notificaciones huérfanas eliminadas\n";
    
    echo "\n✓✓✓ Limpieza completada ✓✓✓\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
