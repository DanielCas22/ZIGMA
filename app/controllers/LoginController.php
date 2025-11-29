<?php
namespace App\Controllers;

use App\Controllers\Controller;

class LoginController extends Controller {
    private function baseUrl() {
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
        $base = explode('/public', $scriptName)[0];
        return $base;
    }
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->model('User');
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $user = $userModel->login($username, $password);
            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: ' . $this->baseUrl() . '/public/index.php?url=dashboard');
                exit;
            } else {
                $error = 'Usuario o contraseña incorrectos';
                $this->view('login/index', ['error' => $error]);
                return;
            }
        }
        $this->view('login/index');
    }
    public function logout() {
        session_start();
        // Eliminar todas las variables de sesión
        $_SESSION = array();
        // Si se usa una cookie de sesión, eliminarla
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        // Redirigir siempre a la carpeta ZIGMA en la raíz
        header('Location: /ZIGMA/index.php');
        exit;
    }
}
