-- Tabla para notificaciones generales
CREATE TABLE IF NOT EXISTS notificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    mensaje TEXT NOT NULL,
    url VARCHAR(255),
    leida TINYINT(1) DEFAULT 0,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES user(id_doc) ON DELETE CASCADE
);

-- Índice para consultas rápidas
CREATE INDEX idx_notificaciones_usuario_leida ON notificaciones(usuario_id, leida);
