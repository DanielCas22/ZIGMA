<?php
require_once __DIR__ . '/TarifaHora.php';
require_once __DIR__ . '/TipoHoraExtra.php';

class HorasExtras extends Model {
    protected $table = 'horas_extras';

    public function getByEmpleado($empleado_id) {
        $sql = 'SELECT he.*, e.nombre as empleado_nombre FROM horas_extras he 
                JOIN empleados e ON he.empleado_id = e.id_empleados
                WHERE he.empleado_id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$empleado_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calcularValorAutomatico($tipo, $cantidad, $fecha) {
        // Usar TarifaHora para obtener la tarifa vigente
        $tarifaModel = new TarifaHora();
        $tarifaVigente = $tarifaModel->getTarifaVigente($fecha);
        
        $valor_hora = isset($tarifaVigente['valor_hora']) ? $tarifaVigente['valor_hora'] : 0;
        if (!$valor_hora) {
            return null; // Error si no hay tarifa válida
        }

        // Usar TipoHoraExtra para obtener el porcentaje
        $tipoModel = new TipoHoraExtra();
        $tipoData = $tipoModel->getTipoPorcentaje($tipo);
        $porcentaje = isset($tipoData['porcentaje']) ? $tipoData['porcentaje'] : 0;
        if (!$porcentaje) {
            return null; // Error si no hay tipo válido
        }

        // Calcular valor automáticamente
        return $tarifaModel->calcularValorHorasExtras(
            $valor_hora, 
            $cantidad, 
            $porcentaje
        );
    }

    public function getAllWithEmpleado() {
        $sql = 'SELECT he.*, e.id_empleados, e.nombre, e.apellido FROM horas_extras he
                JOIN empleados e ON he.empleado_id = e.id_empleados';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHorasExtrasByEmpleado($empleado_id) {
        $sql = 'SELECT * FROM horas_extras WHERE empleado_id = ? ORDER BY anio DESC, mes DESC, dia DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$empleado_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        // Calcular valor automáticamente si no se proporciona
        if (!isset($data['valor']) || empty($data['valor'])) {
            $fecha = $data['anio'] . '-' . str_pad($data['mes'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($data['dia'], 2, '0', STR_PAD_LEFT);
            $data['valor'] = $this->calcularValorAutomatico($data['tipo'], $data['cantidad'], $fecha);
        }

        // Obtener porcentaje automáticamente si no se proporciona
        if (!isset($data['porcentaje']) || empty($data['porcentaje'])) {
            $tipoModel = new TipoHoraExtra();
            $tipoData = $tipoModel->getTipoPorcentaje($data['tipo']);
            $data['porcentaje'] = isset($tipoData['porcentaje']) ? $tipoData['porcentaje'] : 0;
        }

        $sql = 'INSERT INTO horas_extras (empleado_id, valor, cantidad, tipo, porcentaje, dia, mes, anio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['empleado_id'],
            $data['valor'],
            $data['cantidad'],
            $data['tipo'],
            $data['porcentaje'],
            $data['dia'],
            $data['mes'],
            $data['anio']
        ]);
    }

    public function find($id) {
        $sql = 'SELECT * FROM horas_extras WHERE id_extras = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        // Recalcular valor automáticamente si es necesario
        if (!isset($data['valor']) || empty($data['valor'])) {
            $fecha = $data['anio'] . '-' . str_pad($data['mes'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($data['dia'], 2, '0', STR_PAD_LEFT);
            $data['valor'] = $this->calcularValorAutomatico($data['tipo'], $data['cantidad'], $fecha);
        }

        // Actualizar porcentaje automáticamente si es necesario
        if (!isset($data['porcentaje']) || empty($data['porcentaje'])) {
            $tipoModel = new TipoHoraExtra();
            $tipoData = $tipoModel->getTipoPorcentaje($data['tipo']);
            $data['porcentaje'] = isset($tipoData['porcentaje']) ? $tipoData['porcentaje'] : 0;
        }

        $sql = 'UPDATE horas_extras SET empleado_id=?, valor=?, cantidad=?, tipo=?, porcentaje=?, dia=?, mes=?, anio=? WHERE id_extras=?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['empleado_id'],
            $data['valor'],
            $data['cantidad'],
            $data['tipo'],
            $data['porcentaje'],
            $data['dia'],
            $data['mes'],
            $data['anio'],
            $id
        ]);
    }

    public function delete($id) {
        $sql = 'DELETE FROM horas_extras WHERE id_extras = ?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
