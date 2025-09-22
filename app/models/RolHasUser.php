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
}
