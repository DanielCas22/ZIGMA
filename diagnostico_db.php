<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== DIAGNÓSTICO DE BASE DE DATOS ===\n\n";
    
    // Obtener todas las tablas
    $stmt = $pdo->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='zigmaog' ORDER BY TABLE_NAME");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tablas encontradas (" . count($tables) . "):\n";
    foreach ($tables as $table) {
        echo "  • $table\n";
    }
    
    echo "\n=== DETALLE DE COLUMNAS POR TABLA ===\n\n";
    
    foreach ($tables as $table) {
        echo "Tabla: $table\n";
        $stmt = $pdo->query("SELECT COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='zigmaog' AND TABLE_NAME='$table' ORDER BY ORDINAL_POSITION");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($columns as $col) {
            echo "  • {$col['COLUMN_NAME']} ({$col['COLUMN_TYPE']})\n";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
