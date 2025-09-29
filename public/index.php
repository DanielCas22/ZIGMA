<?php
session_start();
// Definir constante URL_ROOT
define('URL_ROOT', '/ZIGMA/public/index.php?url');

require_once '../core/App.php';
require_once '../app/controllers/Controller.php';
require_once '../app/models/Model.php';
$app = new App();
