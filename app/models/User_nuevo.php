<?php
require_once 'Model_nuevo.php';

class User_nuevo extends Model {
    public function login($username, $password) {
        // Autenticación basada en tabla empleados (usuario/contrasena) y rol por relación en user/user_rol
        $stmt = $this->db->prepare(
            'SELECT e.*, u.id_doc as user_id, r.nombre as rol_nombre 
             FROM empleados e
             LEFT JOIN user u ON u.empleado_id = e.id_empleados
             LEFT JOIN user_rol ur ON ur.user_id = u.id_doc
             LEFT JOIN rol r ON r.id_rol = ur.rol_id
             WHERE e.usuario = :usuario'
        );

        $stmt->execute(['usuario' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && !empty($user['contrasena']) && password_verify($password, $user['contrasena'])) {
            // Preparar estructura de sesión
            return [
                'user_id' => $user['user_id'],
                'empleado_id' => $user['id_empleados'],
                'empleado_nombre' => $user['nombre'],
                'rol_nombre' => $user['rol_nombre'] ?? 'empleado',
                'username' => $user['usuario'],
            ];
        }
        return false;
    }
}
