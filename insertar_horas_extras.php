<?php
// Script para insertar horas extras variadas
require_once 'config/database.php';

$pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');

// Datos de horas extras para cada empleado
$horasExtras = [
    // Laura Ospina (ID: 19) - Empleado regular
    [19, 3, 'Extra diurna', 25, 20, 9, 2025],
    [19, 2, 'Extra nocturna', 75, 18, 9, 2025],
    [19, 1, 'Extra diurna', 25, 15, 9, 2025],
    [19, 4, 'Extra diurna dominical/festiva', 105, 22, 9, 2025],
    
    // Carlos Mendoza (ID: 20) - Empleado regular  
    [20, 4, 'Extra nocturna', 75, 19, 9, 2025],
    [20, 6, 'Extra diurna dominical/festiva', 105, 22, 9, 2025],
    [20, 2, 'Extra diurna', 25, 16, 9, 2025],
    [20, 3, 'Extra nocturna dominical/festiva', 155, 8, 9, 2025],
    
    // María González (ID: 21) - Empleado regular
    [21, 5, 'Extra diurna', 25, 21, 9, 2025],
    [21, 4, 'Extra diurna dominical/festiva', 105, 15, 9, 2025],
    [21, 1, 'Extra nocturna', 75, 17, 9, 2025],
    [21, 2, 'Extra diurna', 25, 14, 9, 2025],
    
    // Juan David Martínez (ID: 22) - Admin (salario alto)
    [22, 5, 'Extra nocturna dominical/festiva', 155, 22, 9, 2025],
    [22, 3, 'Extra diurna dominical/festiva', 105, 15, 9, 2025],
    [22, 2, 'Extra nocturna', 75, 20, 9, 2025],
    [22, 4, 'Extra diurna', 25, 18, 9, 2025],
    [22, 1, 'Extra nocturna', 75, 12, 9, 2025],
    
    // Romero Quiñones (ID: 24) - RRHH (salario medio)
    [24, 3, 'Extra diurna', 25, 19, 9, 2025],
    [24, 2, 'Extra nocturna', 75, 21, 9, 2025],
    [24, 7, 'Extra diurna dominical/festiva', 105, 22, 9, 2025],
    [24, 1, 'Extra diurna', 25, 14, 9, 2025],
    [24, 2, 'Extra nocturna dominical/festiva', 155, 1, 9, 2025]
];

$sql = "INSERT INTO horas_extras (empleado_id, cantidad, tipo, porcentaje, dia, mes, año) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

$insertados = 0;

foreach ($horasExtras as $hora) {
    try {
        $stmt->execute($hora);
        $insertados++;
        echo "✓ Insertado: Empleado {$hora[0]} - {$hora[1]} horas {$hora[2]} ({$hora[3]}%) - {$hora[4]}/{$hora[5]}/{$hora[6]}\n";
    } catch (Exception $e) {
        echo "✗ Error: " . $e->getMessage() . "\n";
    }
}

echo "\n🎉 Total insertados: $insertados registros de horas extras\n";
echo "📊 Empleados con horas extras asignadas: Laura, Carlos, María, Juan David, Romero\n";
echo "⏰ Tipos incluidos: Diurnas (25%), Nocturnas (75%), Dominicales (105%), Nocturnas dominicales (155%)\n";
?>