<?php
namespace App\Models;

use PDO;

class NotificacionModel {
    protected $db;
    public function __construct() {
        $this->db = require __DIR__ . '/../../config/database.php';
        // Asegurarse de que la tabla `notificaciones` exista. Si no existe, crearla.
        try {
            $stmt = $this->db->query("SHOW TABLES LIKE 'notificaciones'");
            $exists = $stmt && $stmt->rowCount() > 0;
            if (!$exists) {
                $createSql = "CREATE TABLE IF NOT EXISTS notificaciones (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    usuario_id INT NOT NULL,
                    tipo VARCHAR(50) NOT NULL,
                    mensaje TEXT NOT NULL,
                    url VARCHAR(255),
                    leida TINYINT(1) DEFAULT 0,
                    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                // Ejecutar la creación de la tabla
                $this->db->exec($createSql);
                // Crear índice (si no existe, el intento puede fallar y lo capturamos)
                try {
                    $this->db->exec("CREATE INDEX idx_notificaciones_usuario_leida ON notificaciones(usuario_id, leida)");
                } catch (PDOException $ie) {
                    // índice posiblemente ya existe o DB no lo permite; ignorar
                }
            }
        } catch (PDOException $e) {
            // No interrumpir la ejecución por este chequeo; dejar que otros errores se manejen normalmente.
            error_log('NotificacionModel table check error: ' . $e->getMessage());
        }
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
