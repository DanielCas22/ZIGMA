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
    
    echo "Actualizando tabla 'historial_parametros'...\n";
    
    if (!columnExists($pdo, 'historial_parametros', 'actualizado_por')) {
        echo "  ✓ Agregando columna 'actualizado_por'...\n";
        try {
            $pdo->exec("ALTER TABLE historial_parametros ADD COLUMN actualizado_por INT");
            echo "    → Columna 'actualizado_por' agregada\n";
        } catch (Exception $e) {
            echo "    ⚠ Error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "  ✓ Columna 'actualizado_por' ya existe\n";
    }
    
    if (!columnExists($pdo, 'historial_parametros', 'accion')) {
        echo "  ✓ Agregando columna 'accion'...\n";
        try {
            $pdo->exec("ALTER TABLE historial_parametros ADD COLUMN accion VARCHAR(255)");
            echo "    → Columna 'accion' agregada\n";
        } catch (Exception $e) {
            echo "    ⚠ Error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "  ✓ Columna 'accion' ya existe\n";
    }
    
    if (!columnExists($pdo, 'historial_parametros', 'detalle')) {
        echo "  ✓ Agregando columna 'detalle'...\n";
        try {
            $pdo->exec("ALTER TABLE historial_parametros ADD COLUMN detalle TEXT");
            echo "    → Columna 'detalle' agregada\n";
        } catch (Exception $e) {
            echo "    ⚠ Error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "  ✓ Columna 'detalle' ya existe\n";
    }
    
    if (!columnExists($pdo, 'historial_parametros', 'fecha')) {
        echo "  ✓ Agregando columna 'fecha'...\n";
        try {
            $pdo->exec("ALTER TABLE historial_parametros ADD COLUMN fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
            echo "    → Columna 'fecha' agregada\n";
        } catch (Exception $e) {
            echo "    ⚠ Error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "  ✓ Columna 'fecha' ya existe\n";
    }
    
    echo "\n✓✓✓ Tabla actualizada exitosamente ✓✓✓\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
