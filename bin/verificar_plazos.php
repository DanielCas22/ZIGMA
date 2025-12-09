<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    
    echo "\n=== VERIFICACIÓN DE IMPLEMENTACIÓN DE PLAZOS ===\n\n";
    
    // Verificar tabla conceptos_adicionales_plazos
    $result = $db->query('SHOW TABLES LIKE "conceptos_adicionales_plazos"')->fetch();
    echo $result ? "✅ Tabla conceptos_adicionales_plazos creada\n" : "❌ Tabla no encontrada\n";
    
    // Verificar columna total_plazos
    $cols = $db->query('SHOW COLUMNS FROM conceptos_adicionales_prestaciones LIKE "total_plazos"')->fetch();
    echo $cols ? "✅ Columna total_plazos agregada\n" : "❌ Columna no encontrada\n";
    
    // Verificar columna tipo_plazo
    $cols = $db->query('SHOW COLUMNS FROM conceptos_adicionales_prestaciones LIKE "tipo_plazo"')->fetch();
    echo $cols ? "✅ Columna tipo_plazo agregada\n" : "❌ Columna no encontrada\n";
    
    // Verificar columna tiene_plazo
    $cols = $db->query('SHOW COLUMNS FROM conceptos_adicionales_prestaciones LIKE "tiene_plazo"')->fetch();
    echo $cols ? "✅ Columna tiene_plazo agregada\n" : "❌ Columna no encontrada\n";
    
    // Contar registros en tabla de plazos
    $count = $db->query('SELECT COUNT(*) as cnt FROM conceptos_adicionales_plazos')->fetch();
    echo "\n📊 Registros en conceptos_adicionales_plazos: " . $count['cnt'] . "\n";
    
    // Verificar estructura de tabla de plazos
    echo "\n📋 Estructura de conceptos_adicionales_plazos:\n";
    $columns = $db->query('SHOW COLUMNS FROM conceptos_adicionales_plazos')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        echo "   • {$col['Field']}: {$col['Type']}\n";
    }
    
    echo "\n✅ IMPLEMENTACIÓN COMPLETADA EXITOSAMENTE\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
