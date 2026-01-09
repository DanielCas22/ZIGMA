<?php
// config/session_config.php
// Configurar sesión SOLO si aún no se ha iniciado
if (session_status() === PHP_SESSION_NONE) {
    // Configurar parámetros ANTES de iniciar sesión
    session_set_cookie_params([
        'lifetime' => 0,  // Hasta que se cierre el navegador
        'path' => '/ZIGMA',  // Válida para toda la carpeta ZIGMA
        'domain' => '',  // Dominio actual
        'secure' => false,  // HTTP en desarrollo, true en producción
        'httponly' => true,  // No accesible desde JavaScript
        'samesite' => 'Lax'  // Permitir en redirecciones del mismo sitio
    ]);
    
    // Ahora iniciar la sesión
    session_start();
}

// Inicializar base de datos automáticamente si no está inicializada
if (!isset($_SESSION['db_initialized'])) {
    try {
        $db_config = require __DIR__ . '/database.php';
        require_once __DIR__ . '/db_init.php';
        
        $initializer = new DatabaseInitializer($db_config);
        $result = $initializer->initialize();
        
        // Marcar como inicializada solo si fue exitoso
        if ($result['success']) {
            $_SESSION['db_initialized'] = true;
        }
    } catch (Exception $e) {
        // Log silencioso en desarrollo
        error_log("Error en inicialización de BD: " . $e->getMessage());
    }
}
?>
