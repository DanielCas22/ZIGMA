<?php
class DesprendibleController extends Controller {
    public function mostrar() {
        try {
            $tdId = isset($_GET['td_id']) ? (int)$_GET['td_id'] : 0;
            if ($tdId <= 0) { throw new InvalidArgumentException('Parámetro td_id requerido'); }

            require_once __DIR__ . '/../models/ParafiscalesModel.php';
            $paraf = new ParafiscalesModel();

            $td = $paraf->obtenerTotalDevengado($tdId);
            if (!$td) { throw new RuntimeException('Total devengado no encontrado'); }
            $aux = $paraf->obtenerAuxilioTransportePorTD($tdId);
            $aportes = $paraf->obtenerAportesPorTotalDevengado($tdId);
            if (!$aportes) {
                // si no existen guardados, calcular on-the-fly (no guardar)
                $aportes = $paraf->calcularAportesParafiscales($td['total'], $aux);
            }

            $this->view('desprendible/mostrar', [
                'total_devengado' => $td,
                'auxilio_transporte' => $aux,
                'parafiscales' => $aportes,
            ]);
        } catch (Throwable $e) {
            http_response_code(400);
            echo 'Error al mostrar desprendible: ' . htmlspecialchars($e->getMessage());
        }
    }
}
