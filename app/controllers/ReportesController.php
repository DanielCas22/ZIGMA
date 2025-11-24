<?php
namespace App\Controllers;

use App\Controllers\Controller;

class ReportesController extends Controller
{
    public function index()
    {
        // Verifica si el usuario está autenticado
        if (!isset($_SESSION['user'])) {
            header('Location: /Login');
            exit();
        }
        // Carga la vista de reportes
        $this->view('reportes/index');
    }
}
