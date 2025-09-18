<?php
class DashboardController extends Controller {
    private function baseUrl() {
        // Obtiene la URL base del proyecto
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        $base = explode('/public', $scriptName)[0];
        return $base;
    }
    public function index() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl() . '/public/index.php');
            exit;
        }
        $this->view('dashboard/index', ['user' => $_SESSION['user']]);
    }
}
