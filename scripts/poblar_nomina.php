<?php
require_once __DIR__ . '/../app/models/Model.php';
require_once __DIR__ . '/../app/models/Empleado.php';
require_once __DIR__ . '/../app/models/TotalDevengado.php';
require_once __DIR__ . '/../app/models/TotalDeducidoModel.php';

$db = require __DIR__ . '/../config/database.php';

// Obtener todos los empleados (excepto IDs 1,2,3)
$stmt = $db->query("SELECT id_empleados FROM empleados WHERE id_empleados NOT IN (1,2,3)");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($empleados as $emp) {
    $id = $emp['id_empleados'];
    // Crear registro devengado
    $stmtDev = $db->prepare("INSERT INTO total_devengado (total) VALUES (?)");
    $stmtDev->execute([0]);
    $id_devengado = $db->lastInsertId();
    // Crear registro deducido
    $stmtDed = $db->prepare("INSERT INTO total_deducido (valor, otros, total) VALUES (?, ?, ?)");
    $stmtDed->execute([0, '{}', 0]);
    $id_deducido = $db->lastInsertId();
    // Insertar en nomina si no existe para ese empleado y fecha
    $stmtCheck = $db->prepare("SELECT COUNT(*) FROM nomina WHERE empleado_id = ? AND anio = ? AND mes = ? AND dia = ?");
    $stmtCheck->execute([$id, date('Y'), date('m'), date('d')]);
    if ($stmtCheck->fetchColumn() == 0) {
        $db->prepare("INSERT INTO nomina (anio, mes, dia, valor_pagar, total_devengado_id, total_deducido_id, empleado_id) VALUES (?, ?, ?, ?, ?, ?, ?)")
           ->execute([date('Y'), date('m'), date('d'), 0, $id_devengado, $id_deducido, $id]);
    }
}
echo "Registros de nómina creados para todos los empleados.";
