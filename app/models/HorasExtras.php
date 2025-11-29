<?php
namespace App\Models;

use PDO;

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
        $tarifaModel = new TarifaHora();
        $tarifaVigente = $tarifaModel->getTarifaVigente($fecha);
        
        $valor_hora = isset($tarifaVigente['valor_hora']) ? $tarifaVigente['valor_hora'] : 0;
        if (!$valor_hora) {
            return null; // Error si no hay tarifa válida
        }

        $tipoModel = new TipoHoraExtra();
        $tipoData = $tipoModel->getTipoPorcentaje($tipo);
        $porcentaje = isset($tipoData['porcentaje']) ? $tipoData['porcentaje'] : 0;
        if (!$porcentaje) {
            return null; // Error si no hay tipo válido
        }

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
        if (!isset($data['valor']) || empty($data['valor'])) {
            $fecha = $data['anio'] . '-' . str_pad($data['mes'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($data['dia'], 2, '0', STR_PAD_LEFT);
            $data['valor'] = $this->calcularValorAutomatico($data['tipo'], $data['cantidad'], $fecha);
        }

        if (!isset($data['porcentaje']) || empty($data['porcentaje'])) {
            $tipoModel = new TipoHoraExtra();
            $tipoData = $tipoModel->getTipoPorcentaje($data['tipo']);
            $data['porcentaje'] = isset($tipoData['porcentaje']) ? $tipoData['porcentaje'] : 0;
        }

        $estado = 'pendiente';
        $sql = 'INSERT INTO horas_extras (empleado_id, valor, cantidad, tipo, porcentaje, dia, mes, anio, estado, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())';
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
            $estado
        ]);
    }

    public function find($id) {
        $sql = 'SELECT * FROM horas_extras WHERE id_extras = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        if (!isset($data['valor']) || empty($data['valor'])) {
            $fecha = $data['anio'] . '-' . str_pad($data['mes'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($data['dia'], 2, '0', STR_PAD_LEFT);
            $data['valor'] = $this->calcularValorAutomatico($data['tipo'], $data['cantidad'], $fecha);
        }

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

    /**
     * Aprobar horas extras
     */
    public function aprobar($id, $aprobado_por, $comentario = null) {
        $sql = 'UPDATE horas_extras SET 
                estado = "aprobada", 
                fecha_aprobacion = NOW(), 
                aprobado_por = ?, 
                comentario_aprobacion = ? 
                WHERE id_extras = ?';
        $stmt = $this->db->prepare($sql);
        $resultado = $stmt->execute([$aprobado_por, $comentario, $id]);

        // Registrar notificación
        if ($resultado) {
            $horasExtras = $this->find($id);
            if ($horasExtras && isset($horasExtras['empleado_id']) && $horasExtras['empleado_id']) {
                $this->registrarNotificacionHorasExtras($horasExtras['empleado_id'], 'aprobada');
            }
        }

        return $resultado;
    }
    
    /**
     * Rechazar horas extras
     */
    public function rechazar($id, $aprobado_por, $comentario = null) {
        $sql = 'UPDATE horas_extras SET 
                estado = "rechazada", 
                fecha_aprobacion = NOW(), 
                aprobado_por = ?, 
                comentario_aprobacion = ? 
                WHERE id_extras = ?';
        $stmt = $this->db->prepare($sql);
        $resultado = $stmt->execute([$aprobado_por, $comentario, $id]);

        // Registrar notificación
        if ($resultado) {
            $horasExtras = $this->find($id);
            if ($horasExtras && isset($horasExtras['empleado_id']) && $horasExtras['empleado_id']) {
                $this->registrarNotificacionHorasExtras($horasExtras['empleado_id'], 'rechazada');
            }
        }

        return $resultado;
    }
    
    /**
     * Obtener horas extras pendientes de aprobación
     */
    public function getPendientes($empleadoId = null) {
        $sql = 'SELECT he.*, e.nombre, e.apellido, r.nombre as rol,
                       DATE_FORMAT(he.fecha_creacion, "%d/%m/%Y %H:%i") as fecha_creacion_formatted
                FROM horas_extras he 
                INNER JOIN empleados e ON he.empleado_id = e.id_empleados 
                LEFT JOIN rol_has_user ru ON ru.user_id = (SELECT id_doc FROM user WHERE empleado_id = e.id_empleados)
                LEFT JOIN rol r ON ru.rol_id = r.id_rol
                WHERE he.estado = "pendiente"';
        $params = [];
        if ($empleadoId) {
            $sql .= ' AND he.empleado_id = ?';
            $params[] = $empleadoId;
        }
        $sql .= ' ORDER BY he.fecha_creacion ASC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener horas extras con información de aprobación
     */
    public function getByEmpleadoConAprobacion($empleado_id) {
        $sql = 'SELECT he.*, e.nombre as empleado_nombre, 
                       u.username as aprobado_por_usuario,
                       DATE_FORMAT(he.fecha_aprobacion, "%d/%m/%Y %H:%i") as fecha_aprobacion_formatted,
                       DATE_FORMAT(he.fecha_creacion, "%d/%m/%Y %H:%i") as fecha_creacion_formatted
                FROM horas_extras he 
                JOIN empleados e ON he.empleado_id = e.id_empleados
                LEFT JOIN user u ON he.aprobado_por = u.id_doc
                WHERE he.empleado_id = ?
                ORDER BY he.fecha_creacion DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$empleado_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener todas las horas extras con información de aprobación
     */
    public function getAllConAprobacion() {
        $sql = 'SELECT he.*, 
                       CONCAT(e.nombre, " ", e.apellido) as empleado_nombre,
                       u.username as aprobado_por_usuario,
                       DATE_FORMAT(he.fecha_aprobacion, "%d/%m/%Y %H:%i") as fecha_aprobacion_formatted,
                       DATE_FORMAT(he.fecha_creacion, "%d/%m/%Y %H:%i") as fecha_creacion_formatted
                FROM horas_extras he 
                JOIN empleados e ON he.empleado_id = e.id_empleados
                LEFT JOIN user u ON he.aprobado_por = u.id_doc
                ORDER BY he.fecha_creacion DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarNotificacionHorasExtras($empleadoId, $estado, $comentario = null) {
        require_once __DIR__ . '/NotificacionModel.php';
        $noti = new NotificacionModel();
        // Buscar el usuario con ese empleado_id
        $sql = 'SELECT id_doc FROM user WHERE empleado_id = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$empleadoId]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$usuario || !isset($usuario['id_doc'])) {
            // No se encontró usuario, no registrar notificación
            return false;
        }
        $usuarioId = $usuario['id_doc'];
        $mensaje = $estado === 'aprobada' ?
            "Tus horas extras han sido aprobadas." :
            "Tus horas extras han sido rechazadas. Comentario: $comentario";
        $url = "/ZIGMA/public/index.php?url=HorasExtras/historial/$empleadoId";
        return $noti->registrar($usuarioId, 'horas_extras', $mensaje, $url);
    }
}
?>
