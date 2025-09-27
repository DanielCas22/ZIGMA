<?php
class NominaController extends Controller {
    private function baseUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        return $protocol . $_SERVER['HTTP_HOST'] . '/ZIGMA';
    }

    // Punto de entrada para calcular y mostrar retención de un empleado
    public function integrarCalculoRetencionFuente() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }

        $empleado_id = isset($_GET['empleado_id']) ? intval($_GET['empleado_id']) : (isset($_POST['empleado_id']) ? intval($_POST['empleado_id']) : 0);
        if ($empleado_id <= 0) {
            header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index&error=no_empleado');
            exit;
        }

        // Datos del empleado
        $empleadoModel = $this->model('Empleado');
        $empleado = $empleadoModel->getById($empleado_id);
        if (!$empleado) {
            header('Location: ' . $this->baseUrl() . '/public/index.php?url=Empleado/index&error=notfound');
            exit;
        }

        $salario = floatval($empleado['sueldo_actual'] ?? 0);

        // Obtener parámetros de deducciones/exenciones
        $params = $this->manejarDatosEmpleadoRetencion();

        // Calcular ambos procedimientos
        $retencionModel = $this->model('RetencionFuenteModel');
        $proc1 = $retencionModel->calcularProcedimiento1($salario, $params);
        $proc2 = $retencionModel->calcularProcedimiento2($salario, $params);

        // Elegir mayor valor
        $valor_final = max(floatval($proc1['retencion_art383']), floatval($proc2['retencion_minima_cop']));
        $procedimiento_aplicado = ($valor_final == floatval($proc1['retencion_art383'])) ? 'ART_383' : 'ART_384_MINIMA';

        // Guardar cálculo (opcional enlazar a total_deducido si existe)
        $retencionModel->guardarCalculoRetencion(array_merge($params, [
            'sueldo' => $salario,
            'limite_30_salario' => $proc1['limite_30_salario'],
            'subtotal_1' => $proc1['subtotal_1'],
            'dependientes_uvt_32' => $proc1['dependientes_uvt_32'],
            'salud_prepagada_16_uvt' => $proc1['salud_prepagada_16_uvt'],
            'intereses_vivienda_100_uvt' => $proc1['intereses_vivienda_100_uvt'],
            'subtotal_2' => $proc1['subtotal_2'],
            'renta_exenta' => $proc1['renta_exenta'],
            'base_retencion' => $proc1['base_retencion'],
            'base_retencion_uvt' => $proc1['base_retencion_uvt'],
            'retencion_art833' => $valor_final // almacenar el resultado aplicado
        ]));

        // Render de resumen
        $this->view('nomina/resumen', [
            'empleado' => $empleado,
            'salario' => $salario,
            'proc1' => $proc1,
            'proc2' => $proc2,
            'valor_final' => $valor_final,
            'procedimiento_aplicado' => $procedimiento_aplicado
        ]);
    }

    // Recolecta datos desde POST/GET con valores por defecto 0
    public function manejarDatosEmpleadoRetencion() {
        $src = $_POST + $_GET;
        return [
            'salud' => isset($src['salud']) ? floatval($src['salud']) : 0,
            'pension' => isset($src['pension']) ? floatval($src['pension']) : 0,
            'fondo_solidaridad' => isset($src['fondo_solidaridad']) ? floatval($src['fondo_solidaridad']) : 0,
            'pension_voluntaria' => isset($src['pension_voluntaria']) ? floatval($src['pension_voluntaria']) : 0,
            'afc' => isset($src['afc']) ? floatval($src['afc']) : (isset($src['aporte_afc']) ? floatval($src['aporte_afc']) : 0),
            'certificado_dependientes' => isset($src['certificado_dependientes']) ? floatval($src['certificado_dependientes']) : 0,
            'salud_prepagados' => isset($src['salud_prepagados']) ? floatval($src['salud_prepagados']) : 0,
            'pago_interes_vivienda' => isset($src['pago_interes_vivienda']) ? floatval($src['pago_interes_vivienda']) : 0,
            'promedio_anio_anterior_salud' => isset($src['promedio_anio_anterior_salud']) ? floatval($src['promedio_anio_anterior_salud']) : 0,
            'total_deducido_id' => isset($src['total_deducido_id']) ? intval($src['total_deducido_id']) : null,
        ];
    }
}
