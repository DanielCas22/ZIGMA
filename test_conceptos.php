<?php
require 'vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    
    // Ver todos los conceptos devengado
    $sql = 'SELECT id, empleado_id, concepto, valor FROM conceptos_adicionales_prestaciones';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "=== CONCEPTOS DEVENGADO ===\n";
    echo "Total: " . count($rows) . "\n\n";
    
    foreach($rows as $row) {
        echo "ID: " . $row['id'] . "\n";
        echo "Empleado: " . $row['empleado_id'] . "\n";
        echo "Concepto: " . $row['concepto'] . "\n";
        echo "Valor: " . $row['valor'] . "\n\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
