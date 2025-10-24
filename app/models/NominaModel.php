<?php
require_once 'Model_nuevo.php';

class NominaModel extends Model {
    public function calcularBasico($empleado, $anio, $mes) {
        $diasTrabajados = 30; // se puede parametrizar
        $salarioProporcional = ($empleado['sueldo_actual'] / 30) * $diasTrabajados;
        return [
            'dias' => $diasTrabajados,
            'salario_proporcional' => round($salarioProporcional),
        ];
    }

    public function crearNomina($userId, $anio, $mes, $valorPagar) {
        $stmt = $this->db->prepare('INSERT INTO nomina (anio, mes, dia, valor_pagar, user_id) VALUES (:anio, :mes, :dia, :valor_pagar, :user_id)');
        $stmt->execute([
            'anio' => $anio,
            'mes' => $mes,
            'dia' => date('d'),
            'valor_pagar' => $valorPagar,
            'user_id' => $userId,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function crearDevengado($nominaId, $salario, $dias, $total) {
        $stmt = $this->db->prepare('INSERT INTO total_devengado (salario, dias, total, nomina_id) VALUES (:salario, :dias, :total, :nomina_id)');
        $stmt->execute([
            'salario' => $salario,
            'dias' => (string)$dias,
            'total' => $total,
            'nomina_id' => $nominaId,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function crearDeducido($nominaId, $salario, $valor, $otros, $total) {
        $stmt = $this->db->prepare('INSERT INTO total_deducido (salario, valor, otros, total, nomina_id) VALUES (:salario, :valor, :otros, :total, :nomina_id)');
        $stmt->execute([
            'salario' => $salario,
            'valor' => $valor,
            'otros' => $otros,
            'total' => $total,
            'nomina_id' => $nominaId,
        ]);
        return (int)$this->db->lastInsertId();
    }
}
