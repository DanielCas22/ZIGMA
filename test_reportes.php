<?php
require 'vendor/autoload.php';
require 'config/database.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    
    // Contar conceptos devengado
    $sql1 = 'SELECT COUNT(*) as count FROM conceptos_adicionales_prestaciones';
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
    $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    
    // Contar conceptos deducible
    $sql2 = 'SELECT COUNT(*) as count FROM conceptos_adicionales_deducibles';
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute();
    $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    
    // Ver ejemplo de conceptos devengado
    $sql3 = 'SELECT id_concepto, empleado_id, concepto, valor FROM conceptos_adicionales_prestaciones LIMIT 3';
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute();
    $devengados = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    
    // Ver ejemplo de conceptos deducible
    $sql4 = 'SELECT id, empleado_id, concepto, valor FROM conceptos_adicionales_deducibles LIMIT 3';
    $stmt4 = $pdo->prepare($sql4);
    $stmt4->execute();
    $deducibles = $stmt4->fetchAll(PDO::FETCH_ASSOC);
    
    echo "=== TEST DE REPORTES ===\n";
    echo "Conceptos devengado (prestaciones): " . $result1['count'] . "\n";
    echo "Conceptos deducibles: " . $result2['count'] . "\n\n";
    
    echo "=== Primeros Conceptos Devengado ===\n";
    echo json_encode($devengados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
    
    echo "=== Primeros Conceptos Deducibles ===\n";
    echo json_encode($deducibles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
