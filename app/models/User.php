<?php
namespace App\Models;

class User extends Model {
    public function create($username, $password, $empleado_id) {
        $sql = 'INSERT INTO user (username, password, empleado_id) VALUES (?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username, $password, $empleado_id]);
        return $this->db->lastInsertId();
    }

    public function getByEmpleadoId($empleado_id) {
        $sql = 'SELECT * FROM user WHERE empleado_id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$empleado_id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare('SELECT u.*, r.nombre as rol FROM user u
            JOIN rol_has_user ru ON u.id_doc = ru.user_id
            JOIN rol r ON ru.rol_id = r.id_rol
            WHERE u.username = :username');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($user && (password_verify($password, $user['password']) || $password === $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return false;
    }
}
