<?php
class User extends Model {
    public function login($username, $password) {
        $stmt = $this->db->prepare('SELECT u.*, r.nombre as rol FROM user u
            JOIN rol_has_user ru ON u.id_doc = ru.user_id_doc
            JOIN rol r ON ru.rol_id_rol = r.id_rol
            WHERE u.num_doc = :username');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        return false;
    }
}
