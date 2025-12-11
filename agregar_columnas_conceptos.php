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
    
    // Columnas que debería tener conceptos_adicionales_prestaciones
    $columns = [
        'concepto' => 'VARCHAR(255)',
        'descripcion' => 'TEXT',
        'valor' => 'DECIMAL(15,2)',
        'nombre' => 'VARCHAR(100)',
        'fecha_vigencia' => 'DATE'
    ];
    
    echo "Verificando tabla 'conceptos_adicionales_prestaciones'...\n";
    
    foreach ($columns as $column => $type) {
        if (!columnExists($pdo, 'conceptos_adicionales_prestaciones', $column)) {
            echo "  ✓ Agregando columna '$column' ($type)...\n";
            try {
                $pdo->exec("ALTER TABLE conceptos_adicionales_prestaciones ADD COLUMN $column $type");
                echo "    → Columna '$column' agregada exitosamente\n";
            } catch (Exception $e) {
                echo "    ⚠ Error: " . $e->getMessage() . "\n";
            }
        } else {
            echo "  ✓ Columna '$column' ya existe\n";
        }
    }
    
    echo "\n✓✓✓ Tabla actualizada exitosamente ✓✓✓\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
