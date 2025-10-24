<?php
class Controller {
    public function model($model) {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model();
    }
    
    public function view($view, $data = []) {
        require_once __DIR__ . '/../views/' . $view . '.php';
    }

    protected function flash($key, $message) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['flash'][$key] = $message;
    }

    protected function getFlash($key) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        if (!empty($_SESSION['flash'][$key])) {
            $m = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $m;
        }
        return null;
    }

    protected function setOld($data) {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['old'] = $data;
    }

    protected function old($key, $default = '') {
        if (session_status() == PHP_SESSION_NONE) session_start();
        return $_SESSION['old'][$key] ?? $default;
    }
}
