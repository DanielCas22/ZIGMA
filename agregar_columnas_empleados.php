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
    
    echo "Actualizando tabla 'empleados'...\n";
    
    $columnas = [
        'auxilio_transporte' => 'DECIMAL(10, 2) DEFAULT 163000',
        'documento' => 'VARCHAR(45)',
        'cargo' => 'VARCHAR(100)'
    ];
    
    foreach ($columnas as $columna => $tipo) {
        if (!columnExists($pdo, 'empleados', $columna)) {
            echo "  ✓ Agregando columna '$columna' ($tipo)...\n";
            try {
                $pdo->exec("ALTER TABLE empleados ADD COLUMN $columna $tipo");
                echo "    → Columna '$columna' agregada\n";
            } catch (Exception $e) {
                echo "    ⚠ Error: " . $e->getMessage() . "\n";
            }
        } else {
            echo "  ✓ Columna '$columna' ya existe\n";
        }
    }
    
    echo "\n✓✓✓ Tabla actualizada exitosamente ✓✓✓\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
