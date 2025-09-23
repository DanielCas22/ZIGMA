<?php
class Model {
    protected $db;
    protected static $sharedDb = null;
    
    public function __construct() {
        if (self::$sharedDb instanceof PDO) {
            $this->db = self::$sharedDb;
            return;
        }
        // Incluir el archivo de conexión y obtener PDO (usar require para obtener el return real)
        $pdo = require __DIR__ . '/../../config/database_nuevo.php';
        if ($pdo instanceof PDO) {
            self::$sharedDb = $pdo;
            $this->db = $pdo;
            return;
        }
        // Fallback si el include no devuelve PDO (por ejemplo por require_once previo)
        if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASS')) {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            self::$sharedDb = $pdo;
            $this->db = $pdo;
            return;
        }
        // Si llegamos aquí, hay un problema de configuración
        throw new RuntimeException('No se pudo inicializar la conexión a la base de datos.');
    }

    public function getDb() {
        return $this->db;
    }
}
