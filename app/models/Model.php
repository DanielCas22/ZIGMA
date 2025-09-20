<?php
class Model {
    protected $db;
    protected $table;
    public function __construct() {
        $this->db = require __DIR__ . '/../../config/database.php';
    }

    public function save() {
        $fields = get_object_vars($this);
        unset($fields['db']);
        unset($fields['table']);
        $columns = array_keys($fields);
        $placeholders = array_map(function($col) { return ':' . $col; }, $columns);
        $sql = "INSERT INTO {$this->table} (" . implode(',', $columns) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $this->db->prepare($sql);
        foreach ($fields as $col => $val) {
            $stmt->bindValue(':' . $col, $val);
        }
        return $stmt->execute();
    }
}
