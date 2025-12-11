<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Conexión exitosa a la base de datos\n\n";
    
    // Función para verificar si una columna existe
    function columnExists($pdo, $table, $column) {
        try {
            $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='zigmaog' AND TABLE_NAME=? AND COLUMN_NAME=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$table, $column]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }
    
    echo "Verificando columnas en tablas de parámetros...\n\n";
    
    // Tablas a actualizar
    $tablas = ['parametros_legales', 'parametros_aportes', 'parametros_generales'];
    
    foreach ($tablas as $tabla) {
        echo "Tabla: $tabla\n";
        
        if (!columnExists($pdo, $tabla, 'actualizado_por')) {
            echo "  ✓ Agregando columna 'actualizado_por'...\n";
            try {
                $pdo->exec("ALTER TABLE $tabla ADD COLUMN actualizado_por INT");
                echo "    → Columna 'actualizado_por' agregada\n";
            } catch (Exception $e) {
                echo "    ⚠ Error: " . $e->getMessage() . "\n";
            }
        } else {
            echo "  ✓ Columna 'actualizado_por' ya existe\n";
        }
        
        if (!columnExists($pdo, $tabla, 'fecha_actualizacion')) {
            echo "  ✓ Agregando columna 'fecha_actualizacion'...\n";
            try {
                $pdo->exec("ALTER TABLE $tabla ADD COLUMN fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
                echo "    → Columna 'fecha_actualizacion' agregada\n";
            } catch (Exception $e) {
                echo "    ⚠ Error: " . $e->getMessage() . "\n";
            }
        } else {
            echo "  ✓ Columna 'fecha_actualizacion' ya existe\n";
        }
        
        echo "\n";
    }
    
    echo "✓✓✓ Columnas verificadas y actualizadas ✓✓✓\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
