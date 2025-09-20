<?php
class Model {
    protected $db;
    
    public function __construct() {
        $this->db = require_once __DIR__ . '/../../config/database_nuevo.php';
    }
}
