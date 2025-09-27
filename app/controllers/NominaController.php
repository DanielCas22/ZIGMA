<?php
class NominaController extends Controller {
    public function calcularNomina() {
        try {
            $empleadoId = isset($_GET['empleado_id']) ? (int)$_GET['empleado_id'] : 0;
            $anio = $_GET['anio'] ?? date('Y');
            $mes = $_GET['mes'] ?? date('m');
            $diasTrabajados = isset($_GET['dias']) ? (int)$_GET['dias'] : 30;
            if ($empleadoId <= 0) { throw new InvalidArgumentException('empleado_id es requerido'); }

            // Obtener conexión
            $db = require __DIR__ . '/../../config/database.php';
            $db->beginTransaction();

            // Obtener datos del empleado (sueldo_actual)
            $stmtEmp = $db->prepare('SELECT id_empleados, nombre, apellido, sueldo_actual FROM empleados WHERE id_empleados = ?');
            $stmtEmp->execute([$empleadoId]);
            $empleado = $stmtEmp->fetch(PDO::FETCH_ASSOC);
            if (!$empleado) { throw new RuntimeException('Empleado no encontrado'); }

            $salario = (int)($empleado['sueldo_actual'] ?? 0);
            if ($salario <= 0) { throw new RuntimeException('Salario inválido para el empleado'); }

            // Sumar horas extras del periodo
            $stmtHE = $db->prepare('SELECT COALESCE(SUM(valor),0) FROM horas_extras WHERE empleado_id = ? AND mes = ? AND anio = ?');
            $stmtHE->execute([$empleadoId, $mes, $anio]);
            $totalHE = (int)$stmtHE->fetchColumn();

            // Auxilio de transporte usando configuración 2025
            $cfg = require __DIR__ . '/../../config/nomina.php';
            $confAnio = $cfg[$anio] ?? null;
            $auxilioTransporte = 0;
            if ($confAnio && ($confAnio['smlmv'] ?? 0) > 0 && ($confAnio['auxilio_transporte'] ?? 0) > 0) {
                $elegible = $salario <= (2 * (int)$confAnio['smlmv']);
                if ($elegible) {
                    $baseAux = (int)$confAnio['auxilio_transporte'];
                    if (!empty($confAnio['prorrateo_por_dias'])) {
                        $dias = max(0, min(30, $diasTrabajados));
                        $auxilioTransporte = (int)round($baseAux * ($dias / 30));
                    } else {
                        $auxilioTransporte = $baseAux;
                    }
                }
            }

            // Total devengado
            $totalDevengado = $salario + $totalHE + $auxilioTransporte;
            if ($totalDevengado <= 0) { throw new RuntimeException('Total devengado no puede ser 0'); }

            // Crear registro en total_devengado
            $stmtTD = $db->prepare('INSERT INTO total_devengado (salario, dias, total) VALUES (?,?,?)');
            $stmtTD->execute([$salario, (string)$diasTrabajados, $totalDevengado]);
            $totalDevengadoId = (int)$db->lastInsertId();

            // Guardar auxilio_transporte asociado al total_devengado
            $stmtAT = $db->prepare('INSERT INTO auxilio_transporte (valor, total_devengado_id) VALUES (?, ?)');
            $stmtAT->execute([$auxilioTransporte, $totalDevengadoId]);

            // Calcular y guardar parafiscales
            require_once __DIR__ . '/../models/ParafiscalesModel.php';
            $parafModel = new ParafiscalesModel();
            $parafModel->setConnection($db);

            $aportes = $parafModel->calcularAportesParafiscales($totalDevengado, $auxilioTransporte);
            $parafiscalesId = $parafModel->guardarAportesParafiscales($totalDevengadoId, $aportes);

            // Crear registro en nomina (valor_pagar placeholder: total_devengado)
            $stmtNom = $db->prepare('INSERT INTO nomina (anio, mes, dia, valor_pagar, total_devengado_id, total_deducido_id, empleado_id) VALUES (?,?,?,?,?,?,?)');
            $stmtNom->execute([$anio, $mes, date('d'), $totalDevengado, $totalDevengadoId, null, $empleadoId]);
            $nominaId = (int)$db->lastInsertId();

            $db->commit();

            // Preparar datos para vista
            $this->view('nomina/resumen', [
                'empleado' => $empleado,
                'anio' => $anio,
                'mes' => $mes,
                'salario' => $salario,
                'dias_trabajados' => $diasTrabajados,
                'total_horas_extras' => $totalHE,
                'auxilio_transporte' => $auxilioTransporte,
                'total_devengado' => $totalDevengado,
                'parafiscales' => $aportes,
                'parafiscales_id' => $parafiscalesId,
                'total_devengado_id' => $totalDevengadoId,
                'nomina_id' => $nominaId,
            ]);
        } catch (Throwable $e) {
            if (isset($db) && $db instanceof PDO && $db->inTransaction()) {
                $db->rollBack();
            }
            http_response_code(400);
            echo 'Error al calcular nómina: ' . htmlspecialchars($e->getMessage());
        }
    }
}
