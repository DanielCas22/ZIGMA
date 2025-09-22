<?php
// Script para verificar la estructura de las tablas
require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "<h2>Estructura de las Tablas</h2>";
    
    // Verificar estructura de la tabla user
    echo "<h3>Tabla: user</h3>";
    $stmt = $db->query("DESCRIBE user");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Key</th><th>Extra</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($col['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Extra'] ?? '') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Verificar estructura de la tabla rol_has_user
    echo "<h3>Tabla: rol_has_user</h3>";
    $stmt = $db->query("DESCRIBE rol_has_user");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Key</th><th>Extra</th></tr>";
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($col['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Extra'] ?? '') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Verificar foreign keys
    echo "<h3>Foreign Keys de rol_has_user</h3>";
    $stmt = $db->query("SELECT 
        CONSTRAINT_NAME,
        COLUMN_NAME,
        REFERENCED_TABLE_NAME,
        REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = 'zigmaog' 
        AND TABLE_NAME = 'rol_has_user' 
        AND REFERENCED_TABLE_NAME IS NOT NULL");
    
    $fks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Constraint</th><th>Column</th><th>References Table</th><th>References Column</th></tr>";
    foreach ($fks as $fk) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($fk['CONSTRAINT_NAME']) . "</td>";
        echo "<td>" . htmlspecialchars($fk['COLUMN_NAME']) . "</td>";
        echo "<td>" . htmlspecialchars($fk['REFERENCED_TABLE_NAME']) . "</td>";
        echo "<td>" . htmlspecialchars($fk['REFERENCED_COLUMN_NAME']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>