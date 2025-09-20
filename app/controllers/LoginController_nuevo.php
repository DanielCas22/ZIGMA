<?php
require_once 'Controller_nuevo.php';

class LoginController_nuevo extends Controller {
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = $this->model('User_nuevo');
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $userModel->login($username, $password);
            
            if ($user) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user'] = $user;
                header('Location: /ZIGMA/public_nuevo/index.php?url=dashboard');
                exit;
            } else {
                $error = 'Usuario o contraseña incorrectos';
                $this->view('login/index_nuevo', ['error' => $error]);
                return;
            }
        }
        
        $this->view('login/index_nuevo');
    }
    
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /ZIGMA/public_nuevo/index.php');
        exit;
    }
}
