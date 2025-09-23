<?php
require_once 'Model_nuevo.php';

class UserAdminModel extends Model {
    public function rolesDisponibles() {
        $stmt = $this->db->query('SELECT id_rol, nombre FROM rol ORDER BY id_rol ASC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarUsuariosConRol() {
        $sql = 'SELECT 
                    u.id_doc AS user_id,
                    u.tipo_doc,
                    u.num_doc,
                    e.id_empleados,
                    e.nombre,
                    e.apellido,
                    e.usuario,
                    r.id_rol AS rol_id,
                    r.nombre AS rol_nombre
                FROM user u
                LEFT JOIN empleados e ON e.id_empleados = u.empleado_id
                LEFT JOIN user_rol ur ON ur.user_id = u.id_doc
                LEFT JOIN rol r ON r.id_rol = ur.rol_id
                ORDER BY u.id_doc ASC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function asignarRol($userId, $rolId) {
        // Asegurar que exista el usuario
        $stmt = $this->db->prepare('SELECT id_doc FROM user WHERE id_doc = :id');
        $stmt->execute(['id' => $userId]);
        if (!$stmt->fetch(PDO::FETCH_ASSOC)) return false;

        // Limpiar roles previos (manejamos 1 rol por usuario)
        $this->db->prepare('DELETE FROM user_rol WHERE user_id = :id')->execute(['id' => $userId]);

        // Insertar nuevo rol
        $stmtIns = $this->db->prepare('INSERT INTO user_rol (user_id, rol_id) VALUES (:uid, :rid)');
        return $stmtIns->execute(['uid' => $userId, 'rid' => $rolId]);
    }
}
