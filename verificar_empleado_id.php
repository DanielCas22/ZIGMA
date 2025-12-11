<?php
// Script para identificar qué tabla está causando el error de empleado_id

try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== VERIFICANDO TABLAS QUE USAN empleado_id ===\n\n";
    
    // Tablas que DEBERÍAN tener empleado_id
    $tablasQueDebenTener = [
        'nomina',
        'horas_extras',
        'user',
        'total_devengado',
        'total_deducido',
        'conceptos_adicionales_deducibles',
        'desprendible_nomina'
    ];
    
    echo "Verificando columna 'empleado_id' en tablas:\n\n";
    
    foreach ($tablasQueDebenTener as $table) {
        $stmt = $pdo->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='zigmaog' AND TABLE_NAME='$table'");
        $tableExists = $stmt->rowCount() > 0;
        
        if ($tableExists) {
            $stmt = $pdo->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='zigmaog' AND TABLE_NAME='$table' AND COLUMN_NAME='empleado_id'");
            $columnExists = $stmt->rowCount() > 0;
            
            if ($columnExists) {
                echo "✓ Tabla '$table': SÍ tiene 'empleado_id'\n";
            } else {
                echo "✗ Tabla '$table': NO tiene 'empleado_id' - FALTA AGREGARLA\n";
                // Crear la columna
                try {
                    $pdo->exec("ALTER TABLE $table ADD COLUMN empleado_id INT");
                    echo "  → Columna 'empleado_id' agregada a '$table'\n";
                } catch (Exception $e) {
                    echo "  ⚠ Error al agregar: " . $e->getMessage() . "\n";
                }
            }
        } else {
            echo "⚠ Tabla '$table': NO EXISTE en la base de datos\n";
        }
    }
    
    echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
