<?php
namespace App\Models;

use PDO;

class RetencionFuenteModel extends Model {
    private $uvt;
    private $rangos;
    private $tarifas;

    public function __construct() {
        parent::__construct();
        $conf = require __DIR__ . '/../../config/uvt.php';
        $this->uvt = $conf['UVT_2025'];
        $this->rangos = $conf['RANGOS_UVT']; // [95,150,360]
        $this->tarifas = $conf['TARIFAS_MARGINALES']; // [0,0.19,0.28,0.33]
    }

    public function calcularProcedimiento1($salario, array $p = []) {
        $salario = floatval($salario);
        $uvt = $this->uvt;
        // Entradas con valores por defecto
        $pension = floatval($p['pension'] ?? 0);
        $fsp = floatval($p['fondo_solidaridad'] ?? 0);
        $pension_voluntaria = floatval($p['pension_voluntaria'] ?? 0);
        $afc = floatval($p['afc'] ?? ($p['aporte_afc'] ?? 0));
        $cert_dependientes = floatval($p['certificado_dependientes'] ?? 0);
        $salud_prepagados = floatval($p['salud_prepagados'] ?? 0);
        $intereses_vivienda = floatval($p['pago_interes_vivienda'] ?? 0);

        // Topes en UVT
        $dependientes_capped = min($cert_dependientes, 32 * $uvt);
        $salud_prepagada_capped = min($salud_prepagados, 16 * $uvt);
        $intereses_vivienda_capped = min($intereses_vivienda, 100 * $uvt);

        // Rentas exentas base (con límite 30% del salario)
        $rentas_exentas_base = $pension + $fsp + $pension_voluntaria + $afc;
        $limite_30 = 0.30 * $salario;
        $subtotal_1 = min($rentas_exentas_base, $limite_30);

        // Subtotal 2 con topes de UVT adicionales
        $subtotal_2 = $subtotal_1 + $dependientes_capped + $salud_prepagada_capped + $intereses_vivienda_capped;

        $renta_exenta = $subtotal_2; // según alcance solicitado
        $base_retencion = max(0, $salario - $renta_exenta);
        $base_uvt = $uvt > 0 ? $base_retencion / $uvt : 0;

        $retencion_cop = $this->calcularProgresivoArt383($base_uvt) * $uvt;

        return [
            'limite_30_salario' => $limite_30,
            'subtotal_1' => $subtotal_1,
            'dependientes_uvt_32' => $dependientes_capped,
            'salud_prepagada_16_uvt' => $salud_prepagada_capped,
            'intereses_vivienda_100_uvt' => $intereses_vivienda_capped,
            'subtotal_2' => $subtotal_2,
            'renta_exenta' => $renta_exenta,
            'base_retencion' => $base_retencion,
            'base_retencion_uvt' => $base_uvt,
            'retencion_art383' => $retencion_cop
        ];
    }

    public function calcularProcedimiento2($ingreso_laboral, array $p = []) {
        $uvt = $this->uvt;
        $salud = floatval($p['salud'] ?? 0);
        $pension = floatval($p['pension'] ?? 0);
        $fsp = floatval($p['fondo_solidaridad'] ?? 0);
        $deducciones_legales = $salud + $pension + $fsp;

        $base_gravable = max(0, floatval($ingreso_laboral) - $deducciones_legales);
        $base_uvt = $uvt > 0 ? $base_gravable / $uvt : 0;

        // Consultar tabla retención mínima
        $stmt = $this->db->prepare('SELECT valor_retencion_uvt FROM tabla_retencion_minima WHERE desde_uvt <= ? AND (hasta_uvt IS NULL OR ? <= hasta_uvt) ORDER BY desde_uvt DESC LIMIT 1');
        $stmt->execute([$base_uvt, $base_uvt]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $retencion_uvt = $row ? floatval($row['valor_retencion_uvt']) : 0;
        $retencion_cop = $retencion_uvt * $uvt;

        return [
            'base_gravable' => $base_gravable,
            'base_uvt' => $base_uvt,
            'retencion_minima_uvt' => $retencion_uvt,
            'retencion_minima_cop' => $retencion_cop
        ];
    }

    public function obtenerTablaRetencionMinima() {
        $stmt = $this->db->prepare('SELECT * FROM tabla_retencion_minima ORDER BY desde_uvt');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarCalculoRetencion(array $d) {
        // Inserta un registro en retencion_fuente con los campos relevantes
        $sql = 'INSERT INTO retencion_fuente (
                    sueldo, limite_30_salario, promedio_anio_anterior_salud, aporte_afc, certificado_dependientes,
                    salud_prepagados, pago_interes_vivienda, rentas_extensas, pension_voluntaria, afc,
                    subtotal_1, dependientes_uvt_32, salud_prepagada_16_uvt, intereses_vivienda_100_uvt,
                    subtotal_2, renta_exenta, base_retencion, base_retencion_uvt, retencion_art833, total_deducido_id
                ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            floatval($d['sueldo'] ?? 0),
            floatval($d['limite_30_salario'] ?? 0),
            floatval($d['promedio_anio_anterior_salud'] ?? 0),
            floatval($d['aporte_afc'] ?? 0),
            floatval($d['certificado_dependientes'] ?? 0),
            floatval($d['salud_prepagados'] ?? 0),
            floatval($d['pago_interes_vivienda'] ?? 0),
            floatval($d['rentas_extensas'] ?? ($d['renta_exenta'] ?? 0)),
            floatval($d['pension_voluntaria'] ?? 0),
            floatval($d['afc'] ?? ($d['aporte_afc'] ?? 0)),
            floatval($d['subtotal_1'] ?? 0),
            floatval($d['dependientes_uvt_32'] ?? 0),
            floatval($d['salud_prepagada_16_uvt'] ?? 0),
            floatval($d['intereses_vivienda_100_uvt'] ?? 0),
            floatval($d['subtotal_2'] ?? 0),
            floatval($d['renta_exenta'] ?? 0),
            floatval($d['base_retencion'] ?? 0),
            floatval($d['base_retencion_uvt'] ?? 0),
            floatval($d['retencion_art833'] ?? ($d['retencion_art383'] ?? 0)),
            isset($d['total_deducido_id']) ? intval($d['total_deducido_id']) : null
        ]);
    }

    private function calcularProgresivoArt383($base_uvt) {
        $r = $this->rangos; // [95,150,360,640,945,2300]
        $t = $this->tarifas; // [0,0.19,0.28,0.33,0.35,0.37,0.39]
        $b = max(0, floatval($base_uvt));
        if ($b <= $r[0]) return 0; // 0%
        if ($b <= $r[1]) return ($b - $r[0]) * $t[1];
        if ($b <= $r[2]) return ($r[1] - $r[0]) * $t[1] + ($b - $r[1]) * $t[2];
        if ($b <= $r[3]) return ($r[1] - $r[0]) * $t[1] + ($r[2] - $r[1]) * $t[2] + ($b - $r[2]) * $t[3];
        if ($b <= $r[4]) return ($r[1] - $r[0]) * $t[1] + ($r[2] - $r[1]) * $t[2] + ($r[3] - $r[2]) * $t[3] + ($b - $r[3]) * $t[4];
        if ($b <= $r[5]) return ($r[1] - $r[0]) * $t[1] + ($r[2] - $r[1]) * $t[2] + ($r[3] - $r[2]) * $t[3] + ($r[4] - $r[3]) * $t[4] + ($b - $r[4]) * $t[5];
        // Mayor a 2300 UVT
        return ($r[1] - $r[0]) * $t[1] + ($r[2] - $r[1]) * $t[2] + ($r[3] - $r[2]) * $t[3] + ($r[4] - $r[3]) * $t[4] + ($r[5] - $r[4]) * $t[5] + ($b - $r[5]) * $t[6];
    }
}
