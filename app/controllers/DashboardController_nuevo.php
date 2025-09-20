<?php
require_once 'Controller_nuevo.php';

class DashboardController_nuevo extends Controller {
    public function index() {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /ZIGMA/public_nuevo/index.php');
            exit;
        }
        
        $this->view('dashboard/index_nuevo', ['user' => $_SESSION['user']]);
    }
}
