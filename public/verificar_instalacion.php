<?php
/**
 * SCRIPT DE VERIFICACIÓN DEL SISTEMA ZIGMA
 * Archivo: public/verificar_instalacion.php
 * 
 * Este script verifica que la instalación sea correcta
 * Úsalo para validar que todo está funcionando
 */

header('Content-Type: text/html; charset=utf-8');

$verificaciones = [];
$errores = [];
$advertencias = [];

// ===========================================================
// 1. VERIFICAR CONEXIÓN A BD
// ===========================================================
try {
    $pdo = new PDO('mysql:host=localhost;dbname=zigmaog;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $verificaciones[] = '✅ Conexión a BD exitosa';
} catch (Exception $e) {
    $errores[] = '❌ No se puede conectar a BD: ' . $e->getMessage();
    $pdo = null;
}

// ===========================================================
// 2. VERIFICAR TABLAS CRÍTICAS
// ===========================================================
$tablas_criticas = [
    'rol',
    'empleados',
    'user',
    'rol_has_user',
    'total_devengado',
    'total_deducido',
    'nomina',
    'horas_extras',
    'parametros_generales',
    'parametros_aportes',
    'niveles_riesgo_arl',
    'rangos_fondo_solidaridad',
    'tabla_retencion_fuente',
    'tarifas_horas',
    'tipos_horas_extras',
    'conceptos_adicionales_prestaciones',
    'conceptos_adicionales_deducibles'
];

if ($pdo) {
    foreach ($tablas_criticas as $tabla) {
        try {
            $result = $pdo->query("SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'zigmaog' AND TABLE_NAME = '{$tabla}'");
            if ($result->rowCount() > 0) {
                $verificaciones[] = "✅ Tabla '{$tabla}' existe";
            } else {
                $errores[] = "❌ Tabla '{$tabla}' NO EXISTE";
            }
        } catch (Exception $e) {
            $errores[] = "❌ Error verificando tabla '{$tabla}': " . $e->getMessage();
        }
    }
}

// ===========================================================
// 3. VERIFICAR DATOS INICIALES
// ===========================================================
if ($pdo) {
    try {
        // Verificar roles
        $result = $pdo->query("SELECT COUNT(*) as count FROM rol");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if ($row['count'] >= 3) {
            $verificaciones[] = '✅ Roles creados (3+)';
        } else {
            $advertencias[] = '⚠️ Roles: Solo ' . $row['count'] . ' encontrados (esperado 3+)';
        }
        
        // Verificar empleados del sistema
        $result = $pdo->query("SELECT COUNT(*) as count FROM empleados WHERE id_empleados IN (1,2,3)");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if ($row['count'] >= 3) {
            $verificaciones[] = '✅ Empleados del sistema creados';
        } else {
            $advertencias[] = '⚠️ Empleados del sistema: Solo ' . $row['count'] . ' encontrados';
        }
        
        // Verificar usuarios
        $result = $pdo->query("SELECT COUNT(*) as count FROM user WHERE id_doc IN (1,2,3)");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if ($row['count'] >= 3) {
            $verificaciones[] = '✅ Usuarios del sistema creados';
        } else {
            $advertencias[] = '⚠️ Usuarios del sistema: Solo ' . $row['count'] . ' encontrados';
        }
        
        // Verificar parámetros generales
        $result = $pdo->query("SELECT COUNT(*) as count FROM parametros_generales");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if ($row['count'] > 0) {
            $verificaciones[] = '✅ Parámetros generales configurados';
        } else {
            $advertencias[] = '⚠️ Parámetros generales no encontrados';
        }
        
        // Verificar ARL
        $result = $pdo->query("SELECT COUNT(*) as count FROM niveles_riesgo_arl");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if ($row['count'] >= 5) {
            $verificaciones[] = '✅ Niveles de riesgo ARL configurados';
        } else {
            $advertencias[] = '⚠️ Solo ' . $row['count'] . ' niveles de riesgo encontrados (esperado 5)';
        }
        
        // Verificar tarifas
        $result = $pdo->query("SELECT COUNT(*) as count FROM tarifas_horas");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if ($row['count'] > 0) {
            $verificaciones[] = '✅ Tarifas de horas configuradas';
        } else {
            $advertencias[] = '⚠️ Tarifas de horas no encontradas';
        }
        
    } catch (Exception $e) {
        $errores[] = '❌ Error verificando datos: ' . $e->getMessage();
    }
}

// ===========================================================
// 4. VERIFICAR ARCHIVOS CRÍTICOS
// ===========================================================
$archivos_criticos = [
    '../config/database.php' => 'Configuración de BD',
    '../config/session_config.php' => 'Configuración de sesión',
    '../config/db_init.php' => 'Inicializador de BD',
    '../core/App.php' => 'Core del router',
    '../app/controllers/Controller.php' => 'Controller base',
    '../app/models/Model.php' => 'Model base'
];

foreach ($archivos_criticos as $archivo => $descripcion) {
    $ruta_completa = __DIR__ . '/' . $archivo;
    if (file_exists($ruta_completa)) {
        $verificaciones[] = "✅ {$descripcion} existe";
    } else {
        $errores[] = "❌ {$descripcion} NO EXISTE ({$archivo})";
    }
}

// ===========================================================
// 5. VERIFICAR PERMISOS Y CONFIGURACIÓN
// ===========================================================

// Verificar si vendor/autoload.php existe
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    $verificaciones[] = '✅ Composer autoloader presente';
} else {
    $advertencias[] = '⚠️ Composer autoloader no encontrado (ejecutar: composer install)';
}

// ===========================================================
// GENERAR REPORTE HTML
// ===========================================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Instalación - ZIGMA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px;
        }
        
        .status-section {
            margin-bottom: 30px;
        }
        
        .status-section h2 {
            font-size: 20px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
            color: #333;
        }
        
        .status-item {
            padding: 12px;
            margin-bottom: 8px;
            border-radius: 5px;
            font-size: 16px;
            display: flex;
            align-items: center;
        }
        
        .status-item:before {
            display: inline-block;
            margin-right: 10px;
        }
        
        .success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .warning {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        
        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
        }
        
        .summary-box {
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            background: #f9f9f9;
        }
        
        .summary-box .number {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .summary-box .label {
            font-size: 14px;
            color: #666;
        }
        
        .success .number {
            color: #28a745;
        }
        
        .error .number {
            color: #dc3545;
        }
        
        .warning .number {
            color: #ffc107;
        }
        
        .action-button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            border: none;
            font-size: 16px;
            transition: background 0.3s;
        }
        
        .action-button:hover {
            background: #764ba2;
        }
        
        .footer {
            background: #f9f9f9;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Verificación de Instalación</h1>
            <p>Sistema ZIGMA - Nómina y Prestaciones</p>
        </div>
        
        <div class="content">
            <!-- VERIFICACIONES EXITOSAS -->
            <div class="status-section">
                <h2>✅ Verificaciones Exitosas (<?php echo count($verificaciones); ?>)</h2>
                <?php foreach ($verificaciones as $verif): ?>
                    <div class="status-item success">
                        <?php echo $verif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- ADVERTENCIAS -->
            <?php if (count($advertencias) > 0): ?>
            <div class="status-section">
                <h2>⚠️ Advertencias (<?php echo count($advertencias); ?>)</h2>
                <?php foreach ($advertencias as $adv): ?>
                    <div class="status-item warning">
                        <?php echo $adv; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <!-- ERRORES -->
            <?php if (count($errores) > 0): ?>
            <div class="status-section">
                <h2>❌ Errores Encontrados (<?php echo count($errores); ?>)</h2>
                <?php foreach ($errores as $err): ?>
                    <div class="status-item error">
                        <?php echo $err; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <!-- RESUMEN -->
            <div class="summary">
                <div class="summary-box success">
                    <div class="number"><?php echo count($verificaciones); ?></div>
                    <div class="label">Verificaciones Exitosas</div>
                </div>
                <div class="summary-box warning">
                    <div class="number"><?php echo count($advertencias); ?></div>
                    <div class="label">Advertencias</div>
                </div>
                <div class="summary-box error">
                    <div class="number"><?php echo count($errores); ?></div>
                    <div class="label">Errores</div>
                </div>
            </div>
            
            <!-- BOTONES DE ACCIÓN -->
            <div style="text-align: center; margin-top: 30px;">
                <?php if (count($errores) == 0 && count($advertencias) == 0): ?>
                    <p style="color: #28a745; font-size: 18px; margin-bottom: 20px;">
                        🎉 ¡Sistema listo para usar!
                    </p>
                    <a href="index.php" class="action-button">Ir al Sistema →</a>
                <?php elseif (count($errores) == 0): ?>
                    <p style="color: #ffc107; font-size: 16px; margin-bottom: 20px;">
                        El sistema funcionará, pero se recomienda revisar las advertencias
                    </p>
                    <a href="index.php" class="action-button">Ir al Sistema →</a>
                <?php else: ?>
                    <p style="color: #dc3545; font-size: 16px; margin-bottom: 20px;">
                        Hay errores que deben ser corregidos antes de usar el sistema
                    </p>
                    <a href="javascript:location.reload()" class="action-button">Reintentar →</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="footer">
            <p>Verificación realizada: <?php echo date('d/m/Y H:i:s'); ?></p>
            <p>Sistema ZIGMA v1.0 - Producción Ready</p>
        </div>
    </div>
</body>
</html>
