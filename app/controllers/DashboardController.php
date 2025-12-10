<?php
namespace App\Controllers;
require_once __DIR__ . '/../models/RolePermissions.php';

use App\Controllers\Controller;
use App\Models\RolePermissions;

class DashboardController extends Controller {
    protected function baseUrl() {
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
        
        $empleadoModel = $this->model('Empleado');
        $empleados = $empleadoModel->getAll();
        
        // Obtener notificaciones de horas extras pendientes para admin y RRHH
        $pendingCount = RolePermissions::getPendingHoursCount();
        $pendingHours = RolePermissions::getPendingHours();
        
        $this->view('dashboard/index', [
            'user' => $_SESSION['user'],
            'empleados' => $empleados,
            'pendingHoursCount' => $pendingCount,
            'pendingHours' => $pendingHours
        ]);
    }
}
