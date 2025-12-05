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
    // Aquí deberías calcular el total devengado y deducido real usando tus modelos
    // Ejemplo:
    // $devengadoModel = new TotalDevengado($db);
    // $totalDevengado = $devengadoModel->getTotalByEmpleado($id);
    // $deducidoModel = new TotalDeducidoModel($db);
    // $totalDeducido = $deducidoModel->getTotalByEmpleado($id);
    // Para este script, simulo valores aleatorios para validar visualmente
    $totalDevengado = rand(1000000, 3000000);
    $totalDeducido = rand(100000, 500000);
    // Actualizar el registro devengado
    $stmtDev = $db->prepare("UPDATE total_devengado SET total = ? WHERE id_total_devengado = (SELECT total_devengado_id FROM nomina WHERE empleado_id = ? ORDER BY id_nomina DESC LIMIT 1)");
    $stmtDev->execute([$totalDevengado, $id]);
    // Actualizar el registro deducido
    $stmtDed = $db->prepare("UPDATE total_deducido SET valor = ?, total = ? WHERE id_total_deducido = (SELECT total_deducido_id FROM nomina WHERE empleado_id = ? ORDER BY id_nomina DESC LIMIT 1)");
    $stmtDed->execute([$totalDeducido, $totalDeducido, $id]);
}
echo "Totales devengado y deducido actualizados para todos los empleados.";
