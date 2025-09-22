<?php
// Script para encontrar la columna correcta de ID en la tabla user
require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    echo "<h2>Análisis de la tabla USER</h2>";
    
    // Obtener estructura de la tabla user
    $stmt = $db->query("SHOW COLUMNS FROM user");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Columnas de la tabla user:</h3>";
    echo "<ul>";
    foreach ($columns as $col) {
        $isPrimary = ($col['Key'] === 'PRI') ? ' <strong>(PRIMARY KEY)</strong>' : '';
        $isAuto = (strpos($col['Extra'], 'auto_increment') !== false) ? ' <em>(AUTO_INCREMENT)</em>' : '';
        echo "<li>" . $col['Field'] . " - " . $col['Type'] . $isPrimary . $isAuto . "</li>";
    }
    echo "</ul>";
    
    // Buscar datos de usuarios para ver qué columnas existen
    echo "<h3>Datos de ejemplo de usuarios:</h3>";
    $stmt = $db->query("SELECT * FROM user LIMIT 3");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($users)) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        // Headers
        echo "<tr>";
        foreach (array_keys($users[0]) as $header) {
            echo "<th>" . htmlspecialchars($header) . "</th>";
        }
        echo "</tr>";
        // Data
        foreach ($users as $user) {
            echo "<tr>";
            foreach ($user as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Verificar foreign keys que apuntan A la tabla user
    echo "<h3>Foreign Keys que referencian a la tabla user:</h3>";
    $stmt = $db->query("SELECT 
        TABLE_NAME,
        COLUMN_NAME,
        CONSTRAINT_NAME,
        REFERENCED_COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = 'zigmaog' 
        AND REFERENCED_TABLE_NAME = 'user'");
    
    $fks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($fks)) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Tabla que referencia</th><th>Columna</th><th>Constraint</th><th>Columna referenciada en user</th></tr>";
        foreach ($fks as $fk) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($fk['TABLE_NAME']) . "</td>";
            echo "<td>" . htmlspecialchars($fk['COLUMN_NAME']) . "</td>";
            echo "<td>" . htmlspecialchars($fk['CONSTRAINT_NAME']) . "</td>";
            echo "<td><strong>" . htmlspecialchars($fk['REFERENCED_COLUMN_NAME']) . "</strong></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No se encontraron foreign keys que referencien a la tabla user.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>