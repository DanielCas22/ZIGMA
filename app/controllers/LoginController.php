<?php
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
        session_destroy();
        header('Location: ' . $this->baseUrl() . '/public/index.php');
        exit;
    }
}
