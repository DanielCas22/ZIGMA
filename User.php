<?php
// models/User.php
require_once __DIR__ . '/../config.php';
class User {
    public $id_doc;
    public $tipo_doc;
    public $num_doc;
    public function __construct($id_doc, $tipo_doc, $num_doc) {
        $this->id_doc = $id_doc;
        $this->tipo_doc = $tipo_doc;
        $this->num_doc = $num_doc;
    }
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT * FROM user');
        return $stmt->fetchAll();
    }
}
?>