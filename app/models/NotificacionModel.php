<?php
class NotificacionModel {
    protected $db;
    public function __construct() {
        $this->db = require __DIR__ . '/../../config/database.php';
    }
    // Registrar una notificación
    public function registrar($usuario_id, $tipo, $mensaje, $url = null) {
        $sql = 'INSERT INTO notificaciones (usuario_id, tipo, mensaje, url) VALUES (?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuario_id, $tipo, $mensaje, $url]);
    }
    // Obtener notificaciones no leídas para el usuario actual
    public function obtenerNoLeidas($usuario_id) {
        $sql = 'SELECT * FROM notificaciones WHERE usuario_id = ? AND leida = 0 ORDER BY fecha_creacion DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Obtener el conteo de notificaciones no leídas para el usuario
    public function obtenerNoLeidasCount($usuario_id) {
        $sql = 'SELECT COUNT(*) FROM notificaciones WHERE usuario_id = ? AND leida = 0';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchColumn();
    }
    // Marcar notificación como leída
    public function marcarLeida($id) {
        $sql = 'UPDATE notificaciones SET leida = 1 WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    // Obtener todas las notificaciones
    public function obtenerTodas($usuario_id) {
        $sql = 'SELECT * FROM notificaciones WHERE usuario_id = ? ORDER BY fecha_creacion DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
