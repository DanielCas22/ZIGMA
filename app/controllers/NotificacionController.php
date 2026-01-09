<?php
namespace App\Controllers;

class NotificacionController extends Controller {
    public function index() {
        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            header('Location: /ZIGMA/public/index.php');
            exit;
        }
        $usuario_id = $_SESSION['user']['id_doc'];
        $notiModel = $this->model('NotificacionModel');
        $notificaciones = $notiModel->obtenerTodas($usuario_id);
        $this->view('notificaciones/index', [
            'title' => 'Bandeja de Notificaciones',
            'notificaciones' => $notificaciones
        ]);
    }
    public function leer($id) {
        $notiModel = $this->model('NotificacionModel');
        $notiModel->marcarLeida($id);
        header('Location: /ZIGMA/public/index.php?url=Notificacion/index');
        exit;
    }
}
