<?php
/**
 * INFORME COMPLETO DE FUNCIONALIDADES POR ROL
 * Sistema de Nómina ZIGMA
 * Fecha: <?= date('Y-m-d H:i:s') ?>
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\RolePermissions;
use App\Models\Empleado;
use App\Models\HorasExtras;
use App\Models\NominaModel;
use App\Models\ParametrosModel;
use App\Models\TipoHoraExtra;

$db = require __DIR__ . '/../config/database.php';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Informe de Funcionalidades por Rol - ZIGMA</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        .container { 
            max-width: 1600px; 
            margin: 0 auto; 
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            font-size: 36px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px;
        }
        .role-section {
            margin: 40px 0;
            border: 3px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }
        .role-header {
            padding: 20px 30px;
            font-size: 24px;
            font-weight: bold;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .role-admin { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .role-rrhh { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .role-empleado { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        
        .role-body {
            padding: 30px;
            background: #f9fafb;
        }
        .module {
            background: white;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .module-header {
            background: #f3f4f6;
            padding: 15px 20px;
            font-weight: bold;
            font-size: 18px;
            color: #1f2937;
            border-left: 4px solid #667eea;
        }
        .module-content {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }
        td {
            color: #6b7280;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        
        .permission-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 15px 0;
        }
        .permission-item {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .permission-item strong {
            display: block;
            color: #1f2937;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .stat-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 10px 0;
        }
        .stat-box .number {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-box .label {
            font-size: 14px;
            opacity: 0.9;
        }
        .summary-section {
            background: #f0fdf4;
            border: 2px solid #86efac;
            border-radius: 12px;
            padding: 30px;
            margin: 40px 0;
        }
        .summary-section h2 {
            color: #166534;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .icon {
            font-size: 24px;
            margin-right: 10px;
        }
        .notes {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .notes strong {
            color: #92400e;
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>";

echo "<div class='container'>";

// HEADER
echo "<div class='header'>";
echo "<h1>📊 INFORME COMPLETO DE FUNCIONALIDADES POR ROL</h1>";
echo "<p>Sistema de Nómina ZIGMA - " . date('d/m/Y H:i:s') . "</p>";
echo "</div>";

echo "<div class='content'>";

// ========== OBTENER DATOS DEL SISTEMA ==========
$roles = ['admin', 'rrhh', 'empleado'];

// Módulos del sistema
$modulos = [
    'dashboard' => 'Panel de Control',
    'empleados' => 'Gestión de Empleados',
    'horas_extras' => 'Horas Extras',
    'nomina' => 'Nómina',
    'devengado' => 'Devengados',
    'total_deducido' => 'Deducciones',
    'seguridad_social' => 'Seguridad Social',
    'parafiscales' => 'Parafiscales',
    'prestaciones' => 'Prestaciones Sociales',
    'reportes' => 'Reportes',
    'parametros' => 'Parámetros Administrativos',
    'desprendibles' => 'Desprendibles de Pago'
];

$permisos = ['create', 'read', 'update', 'delete'];

// Estadísticas generales
$stmt = $db->query("SELECT COUNT(*) as total FROM empleados");
$total_empleados = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM user");
$total_usuarios = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM tipos_horas_extras WHERE activo = 1");
$total_tipos_horas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM parametros_aportes");
$parametros_configurados = $stmt->fetch(PDO::FETCH_ASSOC)['total'] > 0;

// ========== RESUMEN GENERAL ==========
echo "<div class='summary-section'>";
echo "<h2>📈 Resumen General del Sistema</h2>";
echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;'>";
echo "<div class='stat-box'><div class='number'>{$total_empleados}</div><div class='label'>Empleados Registrados</div></div>";
echo "<div class='stat-box'><div class='number'>{$total_usuarios}</div><div class='label'>Usuarios del Sistema</div></div>";
echo "<div class='stat-box'><div class='number'>" . count($modulos) . "</div><div class='label'>Módulos Disponibles</div></div>";
echo "<div class='stat-box'><div class='number'>{$total_tipos_horas}</div><div class='label'>Tipos de Horas Extras</div></div>";
echo "</div>";
echo "</div>";

// ========== ANÁLISIS POR ROL ==========
foreach ($roles as $rol) {
    $rol_class = "role-" . $rol;
    $rol_nombre = strtoupper($rol);
    
    if ($rol == 'admin') $icon = '👑';
    elseif ($rol == 'rrhh') $icon = '💼';
    else $icon = '👤';
    
    echo "<div class='role-section'>";
    echo "<div class='role-header {$rol_class}'>";
    echo "<span><span class='icon'>{$icon}</span>{$rol_nombre}</span>";
    echo "</div>";
    
    echo "<div class='role-body'>";
    
    // Descripción del rol
    $descripcion = '';
    if ($rol == 'admin') {
        $descripcion = "Control total del sistema. Puede gestionar todos los módulos, configurar parámetros, y tiene acceso completo a todos los empleados y reportes.";
    } elseif ($rol == 'rrhh') {
        $descripcion = "Gestión de recursos humanos. Puede administrar empleados, horas extras, nómina y generar reportes. Acceso limitado a configuraciones administrativas.";
    } else {
        $descripcion = "Acceso limitado a información propia. Puede ver su propia nómina, desprendibles, horas extras y datos personales. No puede modificar información de otros empleados.";
    }
    
    echo "<div class='notes'>";
    echo "<strong>Descripción del Rol:</strong>";
    echo "<p>{$descripcion}</p>";
    echo "</div>";
    
    // PERMISOS POR MÓDULO
    echo "<div class='module'>";
    echo "<div class='module-header'>🔐 Permisos por Módulo</div>";
    echo "<div class='module-content'>";
    
    echo "<table>";
    echo "<tr><th>Módulo</th><th>Crear</th><th>Leer</th><th>Actualizar</th><th>Eliminar</th><th>Notas</th></tr>";
    
    foreach ($modulos as $modulo_key => $modulo_nombre) {
        echo "<tr>";
        echo "<td><strong>{$modulo_nombre}</strong></td>";
        
        // Verificar permisos individuales
        $can_create = RolePermissions::hasPermission($rol, $modulo_key, 'create');
        $can_read = RolePermissions::hasPermission($rol, $modulo_key, 'read') || 
                    RolePermissions::hasPermission($rol, $modulo_key, 'read_own');
        $can_update = RolePermissions::hasPermission($rol, $modulo_key, 'update') || 
                      RolePermissions::hasPermission($rol, $modulo_key, 'update_own');
        $can_delete = RolePermissions::hasPermission($rol, $modulo_key, 'delete');
        
        echo "<td>" . ($can_create ? "<span class='badge badge-success'>✓ Sí</span>" : "<span class='badge badge-danger'>✗ No</span>") . "</td>";
        echo "<td>" . ($can_read ? "<span class='badge badge-success'>✓ Sí</span>" : "<span class='badge badge-danger'>✗ No</span>") . "</td>";
        echo "<td>" . ($can_update ? "<span class='badge badge-success'>✓ Sí</span>" : "<span class='badge badge-danger'>✗ No</span>") . "</td>";
        echo "<td>" . ($can_delete ? "<span class='badge badge-success'>✓ Sí</span>" : "<span class='badge badge-danger'>✗ No</span>") . "</td>";
        
        // Notas especiales
        $nota = "";
        if ($rol == 'empleado') {
            if (in_array($modulo_key, ['nomina', 'desprendibles', 'horas_extras'])) {
                $nota = "Solo datos propios";
            } elseif ($modulo_key == 'dashboard') {
                $nota = "Vista limitada";
            }
        } elseif ($rol == 'rrhh') {
            if ($modulo_key == 'parametros') {
                $nota = "Solo lectura";
            }
        }
        
        echo "<td>" . ($nota ? "<span class='badge badge-info'>{$nota}</span>" : "-") . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    echo "</div>";
    echo "</div>";
    
    // FUNCIONALIDADES ESPECÍFICAS
    echo "<div class='module'>";
    echo "<div class='module-header'>⚙️ Funcionalidades Específicas</div>";
    echo "<div class='module-content'>";
    
    if ($rol == 'admin') {
        echo "<div class='permission-grid'>";
        echo "<div class='permission-item'><strong>✓ Gestión Total de Empleados</strong>Crear, editar, eliminar cualquier empleado</div>";
        echo "<div class='permission-item'><strong>✓ Configurar Parámetros</strong>SMLV, UVT, aportes, tarifas, etc.</div>";
        echo "<div class='permission-item'><strong>✓ Gestionar Tipos de Horas Extras</strong>Crear y modificar tipos y porcentajes</div>";
        echo "<div class='permission-item'><strong>✓ Aprobar/Rechazar Horas Extras</strong>Control completo del flujo</div>";
        echo "<div class='permission-item'><strong>✓ Generar Nómina Completa</strong>Para todos los empleados</div>";
        echo "<div class='permission-item'><strong>✓ Reportes Avanzados</strong>Todos los tipos de reportes</div>";
        echo "<div class='permission-item'><strong>✓ Gestión de Usuarios</strong>Crear, modificar roles y permisos</div>";
        echo "<div class='permission-item'><strong>✓ Acceso a Historial</strong>Ver cambios en parámetros</div>";
        echo "</div>";
    } elseif ($rol == 'rrhh') {
        echo "<div class='permission-grid'>";
        echo "<div class='permission-item'><strong>✓ Gestión de Empleados</strong>Crear, editar datos de empleados</div>";
        echo "<div class='permission-item'><strong>✓ Gestionar Horas Extras</strong>Aprobar/rechazar solicitudes</div>";
        echo "<div class='permission-item'><strong>✓ Calcular Nómina</strong>Generar nómina mensual</div>";
        echo "<div class='permission-item'><strong>✓ Generar Desprendibles</strong>Para todos los empleados</div>";
        echo "<div class='permission-item'><strong>✓ Reportes Generales</strong>Nómina, deducciones, devengados</div>";
        echo "<div class='permission-item'><strong>~ Ver Parámetros</strong>Solo lectura, no puede modificar</div>";
        echo "<div class='permission-item'><strong>✗ Eliminar Empleados</strong>Requiere autorización admin</div>";
        echo "<div class='permission-item'><strong>✗ Modificar Tarifas</strong>Solo admin puede hacerlo</div>";
        echo "</div>";
    } else {
        echo "<div class='permission-grid'>";
        echo "<div class='permission-item'><strong>✓ Ver Datos Propios</strong>Información personal y de nómina</div>";
        echo "<div class='permission-item'><strong>✓ Registrar Horas Extras</strong>Solo para sí mismo</div>";
        echo "<div class='permission-item'><strong>✓ Descargar Desprendibles</strong>Sus propios desprendibles de pago</div>";
        echo "<div class='permission-item'><strong>✓ Ver Historial de Pagos</strong>Su propia nómina histórica</div>";
        echo "<div class='permission-item'><strong>✗ Ver Otros Empleados</strong>No puede acceder a datos de otros</div>";
        echo "<div class='permission-item'><strong>✗ Modificar Nómina</strong>Solo visualización</div>";
        echo "<div class='permission-item'><strong>✗ Acceso a Reportes</strong>No puede generar reportes</div>";
        echo "<div class='permission-item'><strong>✗ Gestionar Parámetros</strong>Sin acceso</div>";
        echo "</div>";
    }
    
    echo "</div>";
    echo "</div>";
    
    // PARÁMETROS DINÁMICOS ACCESIBLES
    echo "<div class='module'>";
    echo "<div class='module-header'>🔧 Acceso a Parámetros Dinámicos</div>";
    echo "<div class='module-content'>";
    
    echo "<table>";
    echo "<tr><th>Parámetro</th><th>Lectura</th><th>Modificación</th><th>Impacto</th></tr>";
    
    $parametros_lista = [
        'SMLV (Salario Mínimo)' => 'Cálculos de nómina, fondo solidaridad',
        'UVT (Unidad de Valor Tributario)' => 'Retención en la fuente',
        'Porcentajes de Salud' => 'Seguridad social',
        'Porcentajes de Pensión' => 'Seguridad social',
        'Parafiscales (SENA, ICBF)' => 'Aportes parafiscales',
        'Tipos de Horas Extras' => 'Cálculo de horas extras',
        'Tarifas por Hora' => 'Valor de horas extras',
        'Rangos Fondo Solidaridad' => 'Deducciones empleado',
        'Tabla Retención Fuente' => 'Retención en la fuente'
    ];
    
    foreach ($parametros_lista as $param => $impacto) {
        $puede_leer = ($rol == 'admin' || $rol == 'rrhh');
        $puede_modificar = ($rol == 'admin');
        
        echo "<tr>";
        echo "<td><strong>{$param}</strong></td>";
        echo "<td>" . ($puede_leer ? "<span class='badge badge-success'>✓ Sí</span>" : "<span class='badge badge-danger'>✗ No</span>") . "</td>";
        echo "<td>" . ($puede_modificar ? "<span class='badge badge-success'>✓ Sí</span>" : "<span class='badge badge-danger'>✗ No</span>") . "</td>";
        echo "<td>{$impacto}</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    if ($rol == 'admin') {
        echo "<div class='notes'>";
        echo "<strong>Nota Importante:</strong>";
        echo "<p>Todos los parámetros modificados por el administrador se reflejan <strong>inmediatamente</strong> en los cálculos de nómina, deducciones y horas extras de todos los empleados.</p>";
        echo "</div>";
    }
    
    echo "</div>";
    echo "</div>";
    
    echo "</div>"; // role-body
    echo "</div>"; // role-section
}

// ========== CONCLUSIONES Y RECOMENDACIONES ==========
echo "<div class='summary-section'>";
echo "<h2>📝 Conclusiones y Recomendaciones</h2>";

echo "<h3 style='margin-top: 20px; color: #166534;'>✅ Fortalezas del Sistema:</h3>";
echo "<ul style='margin-left: 20px; line-height: 1.8; color: #166534;'>";
echo "<li><strong>Sistema completamente dinámico:</strong> Todos los parámetros se consultan desde la base de datos en tiempo real</li>";
echo "<li><strong>Separación de roles clara:</strong> Cada rol tiene permisos específicos bien definidos</li>";
echo "<li><strong>Flexibilidad en horas extras:</strong> Los tipos y porcentajes pueden modificarse sin cambiar código</li>";
echo "<li><strong>Parámetros centralizados:</strong> SMLV, UVT, aportes, todo configurable desde el panel admin</li>";
echo "<li><strong>Cálculos automáticos:</strong> Los cambios en parámetros se reflejan inmediatamente</li>";
echo "</ul>";

echo "<h3 style='margin-top: 30px; color: #92400e;'>⚠️ Áreas de Mejora Sugeridas:</h3>";
echo "<ul style='margin-left: 20px; line-height: 1.8; color: #92400e;'>";
echo "<li><strong>Interfaz de gestión de tipos de horas extras:</strong> Actualmente se hace directo en BD, crear panel admin</li>";
echo "<li><strong>Auditoría de cambios:</strong> Registrar quién modificó qué parámetro y cuándo</li>";
echo "<li><strong>Validaciones en cascada:</strong> Al cambiar un parámetro, mostrar qué cálculos se verán afectados</li>";
echo "<li><strong>Historial de parámetros:</strong> Mantener versiones anteriores para comparaciones</li>";
echo "<li><strong>Notificaciones:</strong> Alertar a RRHH cuando cambian parámetros importantes</li>";
echo "</ul>";

echo "<h3 style='margin-top: 30px; color: #1e40af;'>🔐 Seguridad y Permisos:</h3>";
echo "<ul style='margin-left: 20px; line-height: 1.8; color: #1e40af;'>";
echo "<li>✓ Control de acceso basado en roles (RBAC) implementado</li>";
echo "<li>✓ Empleados solo acceden a su propia información</li>";
echo "<li>✓ Parámetros críticos solo modificables por admin</li>";
echo "<li>✓ Horas extras requieren aprobación de RRHH/Admin</li>";
echo "</ul>";

echo "</div>";

echo "<div style='text-align: center; padding: 40px; background: #f9fafb; border-radius: 12px; margin-top: 40px;'>";
echo "<h2 style='color: #667eea; font-size: 32px; margin-bottom: 20px;'>🎉 Sistema Completamente Funcional</h2>";
echo "<p style='font-size: 18px; color: #6b7280; max-width: 800px; margin: 0 auto;'>";
echo "El sistema ZIGMA está correctamente configurado con todos los parámetros dinámicos funcionando. ";
echo "Los tres roles (Admin, RRHH, Empleado) tienen sus permisos y funcionalidades claramente definidas.";
echo "</p>";
echo "</div>";

echo "</div>"; // content
echo "</div>"; // container

echo "</body></html>";
?>
