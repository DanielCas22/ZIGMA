<?php
require_once 'Controller_nuevo.php';

class NominaController_nuevo extends Controller {
    public function index() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }
        $rol = strtolower($_SESSION['user']['rol_nombre'] ?? '');
        $empleados = [];
        if (in_array($rol, ['admin','rrhh'])) {
            $empleados = $this->model('EmpleadoModel')->all();
        }
        $this->view('nomina/form', [
            'empleados' => $empleados,
            'rol' => $rol,
            'self_empleado_id' => $_SESSION['user']['empleado_id'] ?? null,
        ]);
    }

    public function calcular() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }

        $empleadoModel = $this->model('EmpleadoModel');
        $heModel = $this->model('HoraExtraModel');
        $nominaModel = $this->model('NominaModel');
        $paramModel = $this->model('ParametroModel');

        $rol = strtolower($_SESSION['user']['rol_nombre'] ?? '');
        $selfEmpleadoId = (int)($_SESSION['user']['empleado_id'] ?? 0);
        $empleadoId = isset($_GET['empleado_id']) ? (int)$_GET['empleado_id'] : $selfEmpleadoId;
        if (!in_array($rol, ['admin','rrhh']) && $empleadoId !== $selfEmpleadoId) {
            // No autorizado a calcular para otros
            header('Location: /ZIGMA/public_nuevo/index.php?url=nomina');
            exit;
        }

        $empleado = $empleadoModel->find($empleadoId);
        if (!$empleado) { $this->flash('error', 'Empleado no encontrado'); header('Location: /ZIGMA/public_nuevo/index.php?url=nomina'); exit; }

        $anio = $_GET['anio'] ?? date('Y');
        $mes = $_GET['mes'] ?? date('m');

        $base = $nominaModel->calcularBasico($empleado, $anio, $mes);

        $horas = $heModel->horasAprobadasPorPeriodo($empleado['id_empleados'], $anio, $mes);
        $totalHE = 0;
        foreach ($horas as $h) {
            $totalHE += $heModel->calcularValorHoraExtra($empleado['sueldo_actual'], (int)$h['cantidad'], $h['tipo']);
        }

        $SMLV = $paramModel->get('SMLV', 1300000);
        $AUX = $paramModel->get('AUXILIO_TRANSPORTE', 162000);
        $auxTrans = ($empleado['sueldo_actual'] <= $SMLV) ? $AUX : 0;

        $devengadoTotal = $base['salario_proporcional'] + $totalHE + $auxTrans;

        $salud = round($empleado['sueldo_actual'] * 0.04);
        $pension = round($empleado['sueldo_actual'] * 0.04);
        $deducidoTotal = $salud + $pension;

        $valorPagar = $devengadoTotal - $deducidoTotal;

        $nominaId = $nominaModel->crearNomina($_SESSION['user']['user_id'] ?? null, $anio, $mes, $valorPagar);
        $nominaModel->crearDevengado($nominaId, (int)$empleado['sueldo_actual'], $base['dias'], $devengadoTotal);
        $nominaModel->crearDeducido($nominaId, (int)$empleado['sueldo_actual'], $salud + $pension, 'Salud 4% + Pensión 4%', $deducidoTotal);

        $this->view('nomina/resultado', [
            'empleado' => $empleado,
            'anio' => $anio,
            'mes' => $mes,
            'base' => $base,
            'total_he' => $totalHE,
            'aux_transporte' => $auxTrans,
            'salud' => $salud,
            'pension' => $pension,
            'valor_pagar' => $valorPagar,
        ]);
    }
}
