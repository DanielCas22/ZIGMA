<?php
require 'vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    
    // Ver estructura de tabla conceptos_adicionales_prestaciones
    $sql1 = 'DESCRIBE conceptos_adicionales_prestaciones';
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
    $estructura1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    
    // Ver estructura de tabla conceptos_adicionales_deducibles
    $sql2 = 'DESCRIBE conceptos_adicionales_deducibles';
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute();
    $estructura2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    echo "=== ESTRUCTURA: conceptos_adicionales_prestaciones ===\n";
    foreach ($estructura1 as $col) {
        echo $col['Field'] . " (" . $col['Type'] . ") " . ($col['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
    }
    
    echo "\n=== ESTRUCTURA: conceptos_adicionales_deducibles ===\n";
    foreach ($estructura2 as $col) {
        echo $col['Field'] . " (" . $col['Type'] . ") " . ($col['Null'] === 'NO' ? 'NOT NULL' : 'NULL') . "\n";
    }
    
    // Contar registros
    $count1 = $pdo->query('SELECT COUNT(*) FROM conceptos_adicionales_prestaciones')->fetchColumn();
    $count2 = $pdo->query('SELECT COUNT(*) FROM conceptos_adicionales_deducibles')->fetchColumn();
    
    echo "\n=== REGISTRO ===\n";
    echo "Conceptos devengado: $count1\n";
    echo "Conceptos deducible: $count2\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
