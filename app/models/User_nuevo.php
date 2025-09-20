<?php
require_once 'Model_nuevo.php';

class User_nuevo extends Model {
    public function login($username, $password) {
        $stmt = $this->db->prepare("
            SELECT u.*, r.nombre as rol_nombre, e.nombre as empleado_nombre 
            FROM user u
            JOIN user_rol ur ON u.id_doc = ur.user_id
            JOIN rol r ON ur.rol_id = r.id_rol
            JOIN empleados e ON u.empleado_id = e.id_empleados
            WHERE u.username = :username
        ");
        
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // No devolver la contraseña
            return $user;
        }
        
        return false;
    }
}
