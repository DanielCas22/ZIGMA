<?php
class RolHasUser extends Model {
    protected $table = 'rol_has_user';
    
    public function assign($user_id, $rol_id) {
        $sql = 'INSERT INTO rol_has_user (user_id, rol_id) VALUES (?, ?)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$user_id, $rol_id]);
    }

    public function removeAllByUserId($user_id) {
        $sql = 'DELETE FROM rol_has_user WHERE user_id = ?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$user_id]);
    }
    
    /**
     * Obtener los roles de un usuario por su ID
     * @param int $user_id ID del usuario
     * @return array Array con los nombres de los roles
     */
    public function getRolesByUserId($user_id) {
        $sql = 'SELECT r.nombre 
                FROM rol_has_user rhu 
                INNER JOIN rol r ON rhu.rol_id = r.id 
                WHERE rhu.user_id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $roles ? $roles : ['empleado']; // Rol por defecto si no tiene roles asignados
    }
    
    /**
     * Obtener información completa de roles de un usuario
     * @param int $user_id ID del usuario
     * @return array Array con información completa de los roles
     */
    public function getRolesInfoByUserId($user_id) {
        $sql = 'SELECT r.* 
                FROM rol_has_user rhu 
                INNER JOIN rol r ON rhu.rol_id = r.id 
                WHERE rhu.user_id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
