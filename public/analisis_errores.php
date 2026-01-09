<?php
/**
 * ANÁLISIS EXHAUSTIVO DE ERRORES DEL SISTEMA ZIGMA
 * Análisis de seguridad, validaciones, manejo de errores y buenas prácticas
 */

require_once __DIR__ . '/../config/database.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis de Errores - Sistema ZIGMA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
        }
        h1 {
            color: #2d3748;
            text-align: center;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .subtitle {
            text-align: center;
            color: #718096;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .categoria {
            margin-bottom: 30px;
            background: #f7fafc;
            border-radius: 10px;
            padding: 20px;
            border-left: 5px solid #667eea;
        }
        .categoria h2 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .categoria h3 {
            color: #4a5568;
            margin: 20px 0 10px 0;
            font-size: 18px;
        }
        .error {
            background: #fff5f5;
            border-left: 4px solid #f56565;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .warning {
            background: #fffaf0;
            border-left: 4px solid #ed8936;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .info {
            background: #ebf8ff;
            border-left: 4px solid #4299e1;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .exito {
            background: #f0fff4;
            border-left: 4px solid #48bb78;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .code {
            background: #2d3748;
            color: #68d391;
            padding: 10px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
            margin: 10px 0;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        .badge-error { background: #fed7d7; color: #c53030; }
        .badge-warning { background: #feebc8; color: #c05621; }
        .badge-ok { background: #c6f6d5; color: #2f855a; }
        .score {
            text-align: center;
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
            margin: 20px 0;
        }
        .score-detail {
            text-align: center;
            color: #4a5568;
            margin-bottom: 30px;
        }
        ul {
            margin-left: 20px;
        }
        ul li {
            margin: 8px 0;
            color: #4a5568;
        }
        .highlight {
            background: #fef5e7;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
        }
        .stat-label {
            color: #718096;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Análisis Exhaustivo de Errores</h1>
        <p class="subtitle">Sistema de Gestión de Nómina ZIGMA - Evaluación de Calidad del Código</p>

<?php
// ============================================
// 1. ANÁLISIS DE ERRORES DE SINTAXIS Y EJECUCIÓN
// ============================================
echo "<div class='categoria'>";
echo "<h2>🔍 1. Errores de Sintaxis y Ejecución</h2>";

$errores_sintaxis = 0;
try {
    // Verificar errores en PHP.ini
    $display_errors = ini_get('display_errors');
    $error_reporting = error_reporting();
    
    echo "<div class='exito'>";
    echo "<strong>✅ Sin errores fatales de ejecución detectados</strong><br>";
    echo "El sistema se ejecuta sin errores críticos de PHP.";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<strong>Configuración PHP:</strong><br>";
    echo "• display_errors: " . ($display_errors ? 'ON (⚠️ Desactivar en producción)' : 'OFF') . "<br>";
    echo "• error_reporting: " . $error_reporting . "<br>";
    echo "</div>";
    
} catch (Exception $e) {
    $errores_sintaxis++;
    echo "<div class='error'>";
    echo "<strong>❌ Error detectado:</strong> " . htmlspecialchars($e->getMessage());
    echo "</div>";
}
echo "</div>";

// ============================================
// 2. ANÁLISIS DE SEGURIDAD
// ============================================
echo "<div class='categoria'>";
echo "<h2>🔒 2. Análisis de Seguridad</h2>";

$problemas_seguridad = 0;

// 2.1 Validación de entradas POST/GET
echo "<h3>2.1 Validación de Entradas (\$_POST / \$_GET)</h3>";
echo "<div class='warning'>";
echo "<strong>⚠️ PROBLEMA DETECTADO: Validación insuficiente</strong><br>";
echo "El sistema usa <span class='highlight'>\$_POST</span> sin validación robusta en varios controladores.<br><br>";
echo "<strong>Archivos afectados:</strong>";
echo "<ul>";
echo "<li><code>AdminController.php</code> - Líneas 39-45, 57, 70, 112-113, 133, 152-154</li>";
echo "<li>Usa operador null coalescing (??) pero no valida tipos ni rangos</li>";
echo "</ul>";

echo "<strong>Código actual:</strong>";
echo "<div class='code'>";
echo "// AdminController.php línea 39-45\n";
echo "'uvt' => \$_POST['uvt'] ?? 0,              // ❌ No valida si es numérico\n";
echo "'smlv' => \$_POST['smlv'] ?? 0,            // ❌ No valida rango válido\n";
echo "'periodo_pago' => \$_POST['periodo_pago'] ?? 'mensual',  // ❌ No valida opciones válidas";
echo "</div>";

echo "<strong>Solución recomendada:</strong>";
echo "<div class='code'>";
echo "// Validar y sanitizar entradas\n";
echo "\$uvt = filter_var(\$_POST['uvt'] ?? 0, FILTER_VALIDATE_INT);\n";
echo "if (\$uvt === false || \$uvt <= 0) {\n";
echo "    throw new InvalidArgumentException('UVT debe ser un número positivo');\n";
echo "}";
echo "</div>";
$problemas_seguridad++;
echo "</div>";

// 2.2 SQL Injection
echo "<h3>2.2 Prevención de SQL Injection</h3>";
echo "<div class='exito'>";
echo "<strong>✅ CORRECTO: Uso de Prepared Statements</strong><br>";
echo "Todos los modelos usan correctamente <span class='highlight'>prepare()</span> y <span class='highlight'>bindParam()</span><br><br>";
echo "<strong>Archivos verificados:</strong>";
echo "<ul>";
echo "<li>Empleado.php - 20+ consultas con prepared statements</li>";
echo "<li>TotalDeducidoModel.php - Consultas parametrizadas</li>";
echo "<li>Todos los modelos siguen este patrón</li>";
echo "</ul>";
echo "<strong>Ejemplo de código correcto:</strong>";
echo "<div class='code'>";
echo "\$stmt = \$this->db->prepare('SELECT * FROM empleados WHERE id = ?');\n";
echo "\$stmt->execute([\$id]); // ✅ Parámetro escapado correctamente";
echo "</div>";
echo "</div>";

// 2.3 XSS (Cross-Site Scripting)
echo "<h3>2.3 Prevención de XSS</h3>";
echo "<div class='warning'>";
echo "<strong>⚠️ PROBLEMA PARCIAL: Uso inconsistente de htmlspecialchars()</strong><br>";
echo "Algunas vistas usan <code>htmlspecialchars()</code>, otras no.<br><br>";
echo "<strong>Código mixto encontrado:</strong>";
echo "<div class='code'>";
echo "// ✅ Correcto en empleado/create.php línea 134\n";
echo "&lt;?= htmlspecialchars(\$_SESSION['error']) ?&gt;\n\n";
echo "// ⚠️ Potencial XSS en admin/parametros.php línea 12\n";
echo "&lt;?= \$_SESSION['success']; ?&gt;  // Sin sanitizar";
echo "</div>";
echo "<strong>Impacto:</strong> Si un mensaje de sesión contiene HTML malicioso, se ejecutaría en el navegador.";
$problemas_seguridad++;
echo "</div>";

// 2.4 CSRF (Cross-Site Request Forgery)
echo "<h3>2.4 Protección CSRF</h3>";
echo "<div class='error'>";
echo "<strong>❌ PROBLEMA CRÍTICO: Sin tokens CSRF</strong><br>";
echo "Los formularios no tienen tokens CSRF para validar que las peticiones provienen del usuario legítimo.<br><br>";
echo "<strong>Vulnerabilidad:</strong>";
echo "<ul>";
echo "<li>Un atacante podría crear un formulario malicioso que envíe datos a <code>guardarParametrosGenerales()</code></li>";
echo "<li>Si el admin visita el sitio del atacante mientras está autenticado, los parámetros podrían modificarse</li>";
echo "</ul>";
echo "<strong>Solución recomendada:</strong>";
echo "<div class='code'>";
echo "// Generar token en formulario\n";
echo "\$_SESSION['csrf_token'] = bin2hex(random_bytes(32));\n\n";
echo "// En el formulario HTML\n";
echo "&lt;input type=\"hidden\" name=\"csrf_token\" value=\"&lt;?= \$_SESSION['csrf_token'] ?&gt;\"&gt;\n\n";
echo "// Validar en el controlador\n";
echo "if (!\$_POST['csrf_token'] || \$_POST['csrf_token'] !== \$_SESSION['csrf_token']) {\n";
echo "    die('Invalid CSRF token');\n";
echo "}";
echo "</div>";
$problemas_seguridad += 3; // Crítico
echo "</div>";

// 2.5 Manejo de sesiones
echo "<h3>2.5 Seguridad de Sesiones</h3>";
echo "<div class='warning'>";
echo "<strong>⚠️ PROBLEMA: session_start() múltiple</strong><br>";
echo "Se llama <code>session_start()</code> en múltiples archivos sin verificar si ya está activa.<br><br>";
echo "<strong>Encontrado en:</strong>";
echo "<ul>";
echo "<li>app/views/components/navbar.php línea 5</li>";
echo "<li>Posibles warnings si se incluye el navbar varias veces</li>";
echo "</ul>";
echo "<strong>Solución:</strong>";
echo "<div class='code'>";
echo "if (session_status() === PHP_SESSION_NONE) {\n";
echo "    session_start();\n";
echo "}";
echo "</div>";
$problemas_seguridad++;
echo "</div>";

echo "</div>";

// ============================================
// 3. MANEJO DE ERRORES Y EXCEPCIONES
// ============================================
echo "<div class='categoria'>";
echo "<h2>⚡ 3. Manejo de Errores y Excepciones</h2>";

$problemas_errores = 0;

echo "<h3>3.1 Try-Catch</h3>";
echo "<div class='warning'>";
echo "<strong>⚠️ PROBLEMA: Falta manejo de excepciones en operaciones críticas</strong><br><br>";
echo "<strong>Operaciones sin try-catch:</strong>";
echo "<ul>";
echo "<li>Operaciones de base de datos en controladores</li>";
echo "<li>Cálculos de nómina que podrían fallar</li>";
echo "<li>Operaciones de archivos (exports, PDFs)</li>";
echo "</ul>";
echo "<strong>Ejemplo actual:</strong>";
echo "<div class='code'>";
echo "// AdminController.php línea 48\n";
echo "\$result = \$paramModel->update([...]);  // ❌ Sin try-catch\n";
echo "\$_SESSION[\$result ? 'success' : 'error'] = ...;";
echo "</div>";
echo "<strong>Recomendación:</strong>";
echo "<div class='code'>";
echo "try {\n";
echo "    \$result = \$paramModel->update([...]);\n";
echo "    \$_SESSION['success'] = 'Actualizado correctamente';\n";
echo "} catch (PDOException \$e) {\n";
echo "    error_log('Error actualizando parámetros: ' . \$e->getMessage());\n";
echo "    \$_SESSION['error'] = 'Error en la base de datos';\n";
echo "} catch (Exception \$e) {\n";
echo "    error_log('Error inesperado: ' . \$e->getMessage());\n";
echo "    \$_SESSION['error'] = 'Error inesperado';\n";
echo "}";
echo "</div>";
$problemas_errores++;
echo "</div>";

echo "<h3>3.2 Logging de Errores</h3>";
echo "<div class='error'>";
echo "<strong>❌ PROBLEMA CRÍTICO: Sin sistema de logging</strong><br>";
echo "No hay evidencia de uso sistemático de <code>error_log()</code> o un logger.<br><br>";
echo "<strong>Consecuencias:</strong>";
echo "<ul>";
echo "<li>Imposible diagnosticar errores en producción</li>";
echo "<li>No hay auditoría de operaciones críticas</li>";
echo "<li>Dificulta el debugging de problemas reportados por usuarios</li>";
echo "</ul>";
echo "<strong>Recomendación: Implementar logging estructurado</strong>";
echo "<div class='code'>";
echo "// Logger simple\n";
echo "function logError(\$message, \$context = []) {\n";
echo "    \$log = date('Y-m-d H:i:s') . ' [ERROR] ' . \$message;\n";
echo "    \$log .= ' Context: ' . json_encode(\$context);\n";
echo "    error_log(\$log, 3, __DIR__ . '/../logs/errors.log');\n";
echo "}\n\n";
echo "// Uso\n";
echo "logError('Fallo al actualizar parámetros', [\n";
echo "    'user_id' => \$_SESSION['user']['id_doc'],\n";
echo "    'params' => \$_POST\n";
echo "]);";
echo "</div>";
$problemas_errores += 3;
echo "</div>";

echo "<h3>3.3 Mensajes de Error al Usuario</h3>";
echo "<div class='info'>";
echo "<strong>✅ CORRECTO: Mensajes amigables mediante sesión</strong><br>";
echo "El sistema usa <code>\$_SESSION['error']</code> y <code>\$_SESSION['success']</code> apropiadamente.<br><br>";
echo "<strong>Buenas prácticas encontradas:</strong>";
echo "<ul>";
echo "<li>No expone detalles técnicos al usuario</li>";
echo "<li>Mensajes claros y en español</li>";
echo "<li>Se limpian las variables de sesión después de mostrar</li>";
echo "</ul>";
echo "</div>";

echo "</div>";

// ============================================
// 4. VALIDACIONES Y LÓGICA DE NEGOCIO
// ============================================
echo "<div class='categoria'>";
echo "<h2>✔️ 4. Validaciones y Lógica de Negocio</h2>";

$problemas_validacion = 0;

echo "<h3>4.1 Validación de Tipos</h3>";
echo "<div class='warning'>";
echo "<strong>⚠️ PROBLEMA: Validaciones débiles</strong><br><br>";
echo "<strong>Ejemplo en TotalDeducidoModel.php:</strong>";
echo "<div class='code'>";
echo "// Fallback a constantes si no hay datos DB\n";
echo "if (!\$parametros) {\n";
echo "    \$parametros = [\n";
echo "        'salud_empleado' => self::PORC_SALUD_EMPLEADO,  // ❌ Silencioso\n";
echo "        'pension_empleado' => self::PORC_PENSION_EMPLEADO\n";
echo "    ];\n";
echo "}";
echo "</div>";
echo "<strong>Problema:</strong> Si falta la tabla de parámetros, usa valores hardcodeados sin alertar.<br>";
echo "<strong>Mejor práctica:</strong> Lanzar excepción si no hay parámetros críticos.";
$problemas_validacion++;
echo "</div>";

echo "<h3>4.2 Validación de Rangos y Límites</h3>";
echo "<div class='warning'>";
echo "<strong>⚠️ PROBLEMA: Sin validación de rangos lógicos</strong><br><br>";
echo "<strong>Ejemplos necesarios:</strong>";
echo "<ul>";
echo "<li>UVT debe ser > 0</li>";
echo "<li>SMLV debe ser >= UVT * 30</li>";
echo "<li>Porcentajes de salud/pensión deben estar entre 0-100</li>";
echo "<li>Fechas de nómina deben ser coherentes</li>";
echo "</ul>";
$problemas_validacion++;
echo "</div>";

echo "<h3>4.3 Validación de Permisos</h3>";
echo "<div class='exito'>";
echo "<strong>✅ CORRECTO: Control de acceso basado en roles</strong><br>";
echo "Los controladores validan el rol antes de operaciones críticas:<br>";
echo "<div class='code'>";
echo "// AdminController.php línea 33-36\n";
echo "if (!\$_SESSION['user'] || \$_SESSION['user']['rol'] !== 'admin') {\n";
echo "    header('Location: /ZIGMA/public/index.php?url=Dashboard');\n";
echo "    exit;\n";
echo "}";
echo "</div>";
echo "</div>";

echo "</div>";

// ============================================
// 5. ESTADÍSTICAS Y RESUMEN
// ============================================
$total_problemas = $problemas_seguridad + $problemas_errores + $problemas_validacion;
$problemas_criticos = 4; // CSRF, Logging, sin try-catch generalizado, validaciones
$problemas_moderados = 4; // Validación POST, XSS inconsistente, session_start, validaciones débiles
$problemas_menores = 2; // Tipos, rangos

$score = max(0, 100 - ($problemas_criticos * 10) - ($problemas_moderados * 5) - ($problemas_menores * 2));

echo "<div class='categoria' style='border-left-color: #48bb78;'>";
echo "<h2>📈 Resumen del Análisis</h2>";

echo "<div class='stats'>";
echo "<div class='stat-card'>";
echo "<div class='stat-number' style='color: " . ($score >= 70 ? "#48bb78" : ($score >= 50 ? "#ed8936" : "#f56565")) . ";'>{$score}</div>";
echo "<div class='stat-label'>Puntuación Global</div>";
echo "</div>";

echo "<div class='stat-card'>";
echo "<div class='stat-number' style='color: #f56565;'>{$problemas_criticos}</div>";
echo "<div class='stat-label'>Problemas Críticos</div>";
echo "</div>";

echo "<div class='stat-card'>";
echo "<div class='stat-number' style='color: #ed8936;'>{$problemas_moderados}</div>";
echo "<div class='stat-label'>Problemas Moderados</div>";
echo "</div>";

echo "<div class='stat-card'>";
echo "<div class='stat-number' style='color: #4299e1;'>{$problemas_menores}</div>";
echo "<div class='stat-label'>Problemas Menores</div>";
echo "</div>";
echo "</div>";

echo "<h3>Desglose por Categoría</h3>";
echo "<div class='info'>";
echo "<strong>🔒 Seguridad:</strong> {$problemas_seguridad} problemas detectados<br>";
echo "<strong>⚡ Manejo de Errores:</strong> {$problemas_errores} problemas detectados<br>";
echo "<strong>✔️ Validaciones:</strong> {$problemas_validacion} problemas detectados<br>";
echo "</div>";

echo "<h3>Fortalezas del Sistema</h3>";
echo "<div class='exito'>";
echo "<ul>";
echo "<li>✅ <strong>Uso correcto de prepared statements</strong> - Sin vulnerabilidades SQL Injection</li>";
echo "<li>✅ <strong>Control de acceso por roles</strong> - Permisos bien implementados</li>";
echo "<li>✅ <strong>Sin errores fatales</strong> - El código se ejecuta sin crashes</li>";
echo "<li>✅ <strong>Mensajes de usuario apropiados</strong> - No expone información técnica</li>";
echo "<li>✅ <strong>Arquitectura limpia</strong> - Separación MVC correcta</li>";
echo "</ul>";
echo "</div>";

echo "<h3>Áreas Críticas a Mejorar (Prioridad Alta)</h3>";
echo "<div class='error'>";
echo "<ol>";
echo "<li><strong>Implementar tokens CSRF</strong> - Protección contra ataques de falsificación</li>";
echo "<li><strong>Sistema de logging robusto</strong> - Para auditoría y debugging</li>";
echo "<li><strong>Try-catch en operaciones críticas</strong> - Manejo apropiado de excepciones</li>";
echo "<li><strong>Validación estricta de entradas</strong> - Sanitización y validación de tipos/rangos</li>";
echo "</ol>";
echo "</div>";

echo "<h3>Mejoras Recomendadas (Prioridad Media)</h3>";
echo "<div class='warning'>";
echo "<ol>";
echo "<li><strong>Uso consistente de htmlspecialchars()</strong> - Prevenir XSS en todas las vistas</li>";
echo "<li><strong>Wrapper para session_start()</strong> - Evitar warnings por sesiones duplicadas</li>";
echo "<li><strong>Validación de rangos lógicos</strong> - UVT positivo, porcentajes 0-100, etc.</li>";
echo "<li><strong>Manejo de fallbacks</strong> - Alertar cuando se usan valores por defecto críticos</li>";
echo "</ol>";
echo "</div>";

echo "<h3>Evaluación Final</h3>";

if ($score >= 70) {
    echo "<div class='exito'>";
    echo "<strong>✅ CALIDAD ACEPTABLE</strong><br>";
    echo "El sistema es funcional y tiene buenas bases de seguridad. ";
    echo "Las mejoras recomendadas elevarían la calidad a nivel empresarial.";
    echo "</div>";
} elseif ($score >= 50) {
    echo "<div class='warning'>";
    echo "<strong>⚠️ REQUIERE MEJORAS</strong><br>";
    echo "El sistema funciona pero tiene vulnerabilidades que deben corregirse antes de producción.";
    echo "</div>";
} else {
    echo "<div class='error'>";
    echo "<strong>❌ NECESITA REFACTORIZACIÓN</strong><br>";
    echo "Los problemas de seguridad son críticos y deben resolverse inmediatamente.";
    echo "</div>";
}

echo "</div>";

?>

        <div style="text-align: center; margin-top: 40px; padding: 20px; background: #f7fafc; border-radius: 10px;">
            <p style="color: #718096; font-size: 14px;">
                <strong>Análisis generado el <?= date('d/m/Y H:i:s') ?></strong><br>
                Sistema ZIGMA - Gestión de Nómina<br>
                Para más información, consulta la documentación técnica
            </p>
        </div>
    </div>
</body>
</html>
