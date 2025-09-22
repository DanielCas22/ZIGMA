<?php
require_once 'Controller_nuevo.php';

class NominaController extends Controller {
    public function calcular() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) { header('Location: /ZIGMA/public_nuevo/index.php'); exit; }

        $empleadoModel = $this->model('EmpleadoModel');
        $heModel = $this->model('HoraExtraModel');
        $nominaModel = $this->model('NominaModel');

        $empleado = $empleadoModel->find($_SESSION['user']['empleado_id']);
        $anio = $_GET['anio'] ?? date('Y');
        $mes = $_GET['mes'] ?? date('m');

        // Cálculo salario proporcional
        $base = $nominaModel->calcularBasico($empleado, $anio, $mes);

        // Horas extras aprobadas
        $horas = $heModel->horasAprobadasPorPeriodo($empleado['id_empleados'], $anio, $mes);
        $totalHE = 0;
        foreach ($horas as $h) {
            $totalHE += $heModel->calcularValorHoraExtra($empleado['sueldo_actual'], (int)$h['cantidad'], $h['tipo']);
        }

        // Auxilio transporte condicional (ejemplo: SMLV ~ 1'300.000; aux 162.000)
        $SMLV = 1300000; $AUX = 162000; // TODO: parametrizar
        $auxTrans = ($empleado['sueldo_actual'] <= $SMLV) ? $AUX : 0;

        $devengadoTotal = $base['salario_proporcional'] + $totalHE + $auxTrans;

        // Deducciones: salud 4%, pensión 4% sobre salario base
        $salud = round($empleado['sueldo_actual'] * 0.04);
        $pension = round($empleado['sueldo_actual'] * 0.04);
        $deducidoTotal = $salud + $pension;

        $valorPagar = $devengadoTotal - $deducidoTotal;

        // Persistencia básica
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
