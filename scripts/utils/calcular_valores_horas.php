<?php
// Script para calcular valores automáticos de horas extras
require_once __DIR__ . '/../../app/models/Model.php';
require_once __DIR__ . '/../../app/models/HorasExtras.php';
require_once __DIR__ . '/../../app/models/TarifaHora.php';

$pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');

// Obtener todas las horas extras con valor NULL
$sql = "SELECT he.id_extras, he.empleado_id, he.cantidad, he.tipo, he.porcentaje, he.dia, he.mes, he.año, e.salario 
        FROM horas_extras he 
        JOIN empleados e ON he.empleado_id = e.id_empleados 
        WHERE he.valor IS NULL";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$horasExtras = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "🔍 Encontrados " . count($horasExtras) . " registros sin valor calculado\n\n";

$actualizar = $pdo->prepare("UPDATE horas_extras SET valor = ? WHERE id_extras = ?");

foreach ($horasExtras as $hora) {
    // Construir fecha
    $fecha = sprintf("%04d-%02d-%02d", $hora['año'], $hora['mes'], $hora['dia']);
    
    // Obtener salario del empleado
    $salario = floatval($hora['salario']);
    
    // Calcular valor hora base (salario mensual / 240 horas mensuales según ley colombiana)
    $valor_hora_base = $salario / 240;
    
    // Aplicar porcentaje de hora extra
    $porcentaje = floatval($hora['porcentaje']);
    $valor_hora_extra = $valor_hora_base * (1 + $porcentaje / 100);
    
    // Calcular valor total
    $cantidad = floatval($hora['cantidad']);
    $valor_total = $valor_hora_extra * $cantidad;
    
    // Actualizar en base de datos
    $actualizar->execute([round($valor_total), $hora['id_extras']]);
    
    echo "✓ ID: {$hora['id_extras']} | Empleado: {$hora['empleado_id']} | ";
    echo "{$cantidad}h {$hora['tipo']} ({$porcentaje}%) | ";
    echo "Valor: $" . number_format($valor_total, 0, ',', '.') . " | Fecha: {$fecha}\n";
}

echo "\n🎉 Todos los valores han sido calculados automáticamente\n";
echo "💰 Fórmula: (Salario ÷ 240 horas) × (1 + porcentaje/100) × cantidad_horas\n";
echo "📅 Basado en legislación laboral colombiana 2025\n";
?>
