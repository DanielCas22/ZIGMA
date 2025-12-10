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
?>
