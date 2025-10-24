<?php
require_once 'Model_nuevo.php';

class HoraExtraModel extends Model {
    private $table = 'horas_extras';

    public function registrar($data) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (valor, cantidad, tipo, porcentaje, dia, mes, anio, empleado_id, aprobado) VALUES (:valor, :cantidad, :tipo, :porcentaje, :dia, :mes, :anio, :empleado_id, 0)");
        $stmt->execute([
            'valor' => $data['valor'] ?? 0,
            'cantidad' => $data['cantidad'],
            'tipo' => $data['tipo'],
            'porcentaje' => $this->porcentajePorTipo($data['tipo']),
            'dia' => $data['dia'],
            'mes' => $data['mes'],
            'anio' => $data['anio'],
            'empleado_id' => $data['empleado_id'],
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function listarPendientes() {
        $sql = "SELECT he.*, e.nombre, e.apellido FROM {$this->table} he JOIN empleados e ON e.id_empleados = he.empleado_id WHERE he.aprobado = 0 ORDER BY he.id_extras DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function aprobar($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET aprobado = 1 WHERE id_extras = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function horasAprobadasPorPeriodo($empleadoId, $anio, $mes) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE empleado_id = :eid AND anio = :anio AND mes = :mes AND aprobado = 1");
        $stmt->execute(['eid' => $empleadoId, 'anio' => $anio, 'mes' => $mes]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calcularValorHoraExtra($salarioMensual, $cantidad, $tipo) {
        $valorHora = ($salarioMensual / 30) / 8; // valor hora estándar
        $factor = 1.0 + $this->porcentajePorTipo($tipo); // ejemplo: 0.25 para HED
        return round($valorHora * $cantidad * $factor);
    }

    private function porcentajePorTipo($tipo) {
        $map = [
            'HED' => 0.25, // Hora Extra Diurna 25%
            'HEN' => 0.75, // Hora Extra Nocturna 75%
            'HEFD' => 1.0, // Hora Extra Festiva Diurna 100%
            'HEFN' => 1.5, // Hora Extra Festiva Nocturna 150%
        ];
        return $map[$tipo] ?? 0.25;
    }
}
