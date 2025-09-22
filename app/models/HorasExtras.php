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
        
        if (!$tarifaVigente) {
            return null; // Error si no hay tarifa
        }

        // Usar TipoHoraExtra para obtener el porcentaje
        $tipoModel = new TipoHoraExtra();
        $tipoData = $tipoModel->getTipoPorcentaje($tipo);
        
        if (!$tipoData) {
            return null; // Error si no hay tipo
        }

        // Calcular valor automáticamente
        return $tarifaModel->calcularValorHorasExtras(
            $tarifaVigente['valor_hora'], 
            $cantidad, 
            $tipoData['porcentaje']
        );
    }

    public function getAllWithEmpleado() {
        $sql = 'SELECT e.id_empleados, e.nombre, e.apellidos 
                FROM empleados e 
                WHERE e.es_usuario_sistema = FALSE 
                ORDER BY e.nombre';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHorasExtrasByEmpleado($empleado_id) {
        $sql = 'SELECT * FROM horas_extras WHERE empleado_id = ? ORDER BY año DESC, mes DESC, dia DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$empleado_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        // Calcular valor automáticamente si no se proporciona
        if (!isset($data['valor']) || empty($data['valor'])) {
            $fecha = $data['año'] . '-' . str_pad($data['mes'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($data['dia'], 2, '0', STR_PAD_LEFT);
            $data['valor'] = $this->calcularValorAutomatico($data['tipo'], $data['cantidad'], $fecha);
        }

        // Obtener porcentaje automáticamente si no se proporciona
        if (!isset($data['porcentaje']) || empty($data['porcentaje'])) {
            $tipoModel = new TipoHoraExtra();
            $tipoData = $tipoModel->getTipoPorcentaje($data['tipo']);
            $data['porcentaje'] = $tipoData ? $tipoData['porcentaje'] : 0;
        }

        $sql = 'INSERT INTO horas_extras (empleado_id, valor, cantidad, tipo, porcentaje, dia, mes, año) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['empleado_id'],
            $data['valor'],
            $data['cantidad'],
            $data['tipo'],
            $data['porcentaje'],
            $data['dia'],
            $data['mes'],
            $data['año']
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
            $fecha = $data['año'] . '-' . str_pad($data['mes'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($data['dia'], 2, '0', STR_PAD_LEFT);
            $data['valor'] = $this->calcularValorAutomatico($data['tipo'], $data['cantidad'], $fecha);
        }

        // Actualizar porcentaje automáticamente si es necesario
        if (!isset($data['porcentaje']) || empty($data['porcentaje'])) {
            $tipoModel = new TipoHoraExtra();
            $tipoData = $tipoModel->getTipoPorcentaje($data['tipo']);
            $data['porcentaje'] = $tipoData ? $tipoData['porcentaje'] : 0;
        }

        $sql = 'UPDATE horas_extras SET empleado_id=?, valor=?, cantidad=?, tipo=?, porcentaje=?, dia=?, mes=?, año=? WHERE id_extras=?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['empleado_id'],
            $data['valor'],
            $data['cantidad'],
            $data['tipo'],
            $data['porcentaje'],
            $data['dia'],
            $data['mes'],
            $data['año'],
            $id
        ]);
    }

    public function delete($id) {
        $sql = 'DELETE FROM horas_extras WHERE id_extras = ?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
