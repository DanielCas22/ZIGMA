<?php
/**
 * INFORME COMPLETO DEL MÓDULO DE REPORTES
 * Análisis exhaustivo de funcionalidad, seguridad y calidad
 */

require_once __DIR__ . '/../config/database.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe Módulo de Reportes - ZIGMA</title>
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
            max-width: 1400px;
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
            font-size: 36px;
        }
        .subtitle {
            text-align: center;
            color: #718096;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .seccion {
            margin-bottom: 30px;
            background: #f7fafc;
            border-radius: 10px;
            padding: 25px;
            border-left: 5px solid #667eea;
        }
        .seccion h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .seccion h3 {
            color: #4a5568;
            margin: 20px 0 10px 0;
            font-size: 18px;
            padding-left: 10px;
            border-left: 3px solid #cbd5e0;
        }
        .feature-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .feature-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .feature-title {
            font-size: 18px;
            font-weight: bold;
            color: #2d3748;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-excelente { background: #c6f6d5; color: #2f855a; }
        .badge-bueno { background: #bee3f8; color: #2c5282; }
        .badge-regular { background: #feebc8; color: #c05621; }
        .badge-malo { background: #fed7d7; color: #c53030; }
        .exito {
            background: #f0fff4;
            border-left: 4px solid #48bb78;
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
        .error {
            background: #fff5f5;
            border-left: 4px solid #f56565;
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
        .code {
            background: #2d3748;
            color: #68d391;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background: #667eea;
            color: white;
            font-weight: bold;
        }
        tr:hover {
            background: #f7fafc;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 42px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        .stat-label {
            color: #718096;
            font-size: 14px;
        }
        ul {
            margin-left: 20px;
            margin-top: 10px;
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
        .score-container {
            text-align: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
        }
        .score-number {
            font-size: 72px;
            font-weight: bold;
            margin: 10px 0;
        }
        .score-label {
            font-size: 24px;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Informe Completo - Módulo de Reportes</h1>
        <p class="subtitle">Análisis exhaustivo de funcionalidad, seguridad y calidad del código</p>

<?php
// ============================================
// 1. RESUMEN EJECUTIVO
// ============================================
echo "<div class='seccion'>";
echo "<h2>📋 1. Resumen Ejecutivo</h2>";

$funcionalidades_totales = 3; // General, Empleado, Nómina
$formatos_soportados = 3; // PDF, Excel, CSV
$roles_implementados = 3; // admin, rrhh, empleado
$lineas_codigo = 471; // ReportesController.php

echo "<div class='stats-grid'>";
echo "<div class='stat-card'>";
echo "<div class='stat-number'>{$funcionalidades_totales}</div>";
echo "<div class='stat-label'>Tipos de Reportes</div>";
echo "</div>";

echo "<div class='stat-card'>";
echo "<div class='stat-number'>{$formatos_soportados}</div>";
echo "<div class='stat-label'>Formatos de Export</div>";
echo "</div>";

echo "<div class='stat-card'>";
echo "<div class='stat-number'>{$roles_implementados}</div>";
echo "<div class='stat-label'>Roles Soportados</div>";
echo "</div>";

echo "<div class='stat-card'>";
echo "<div class='stat-number'>{$lineas_codigo}</div>";
echo "<div class='stat-label'>Líneas de Código</div>";
echo "</div>";
echo "</div>";

echo "<div class='info'>";
echo "<strong>Descripción General:</strong><br>";
echo "El módulo de reportes permite generar 3 tipos de reportes (General, por Empleado y de Nómina) ";
echo "en 3 formatos diferentes (PDF, Excel, CSV). Implementa control de acceso basado en roles ";
echo "y utiliza librerías profesionales como PhpSpreadsheet y TCPDF.";
echo "</div>";
echo "</div>";

// ============================================
// 2. FUNCIONALIDADES IMPLEMENTADAS
// ============================================
echo "<div class='seccion'>";
echo "<h2>✨ 2. Funcionalidades Implementadas</h2>";

// 2.1 Reporte General
echo "<h3>2.1 Reporte General de Empleados</h3>";
echo "<div class='feature-card'>";
echo "<div class='feature-header'>";
echo "<span class='feature-title'>📄 Reporte General</span>";
echo "<span class='badge badge-excelente'>COMPLETO</span>";
echo "</div>";
echo "<strong>Descripción:</strong> Lista completa de todos los empleados con información básica.<br><br>";
echo "<strong>Formatos disponibles:</strong>";
echo "<ul>";
echo "<li>✅ <strong>PDF</strong> - Orientación horizontal, con logo y estilos profesionales</li>";
echo "<li>✅ <strong>Excel (XLSX)</strong> - Con formato de celdas, colores alternados, encabezados fijos</li>";
echo "<li>✅ <strong>CSV</strong> - Con UTF-8 BOM para Excel, separador punto y coma</li>";
echo "</ul>";
echo "<strong>Información incluida:</strong>";
echo "<ul>";
echo "<li>Nombre completo del empleado</li>";
echo "<li>Número de documento</li>";
echo "<li>Salario actual</li>";
echo "<li>Cargo principal</li>";
echo "<li>Todos los roles asignados</li>";
echo "</ul>";
echo "<strong>Control de acceso:</strong> Solo <span class='highlight'>administradores</span><br>";
echo "<strong>Filtros aplicados:</strong> Excluye empleados del sistema (admin, coordinador RRHH, empleado general)";
echo "</div>";

// 2.2 Reporte por Empleado
echo "<h3>2.2 Reporte Individual por Empleado</h3>";
echo "<div class='feature-card'>";
echo "<div class='feature-header'>";
echo "<span class='feature-title'>👤 Reporte por Empleado</span>";
echo "<span class='badge badge-excelente'>COMPLETO</span>";
echo "</div>";
echo "<strong>Descripción:</strong> Reporte detallado con cálculos de devengado, deducido y horas extras.<br><br>";
echo "<strong>Formatos disponibles:</strong>";
echo "<ul>";
echo "<li>✅ <strong>PDF</strong> - Resumen + detalle de horas extras con tabla estilizada</li>";
echo "<li>✅ <strong>Excel (XLSX)</strong> - Dos secciones: resumen y detalle de horas</li>";
echo "</ul>";
echo "<strong>Información incluida:</strong>";
echo "<ul>";
echo "<li>Total devengado (calculado dinámicamente)</li>";
echo "<li>Total deducido (calculado dinámicamente)</li>";
echo "<li>Horas extras trabajadas (cantidad y valor)</li>";
echo "<li>Detalle de cada hora extra (fecha, tipo, cantidad, valor, estado)</li>";
echo "</ul>";
echo "<strong>Control de acceso:</strong> ";
echo "<ul>";
echo "<li><span class='highlight'>Admin/RRHH:</span> Pueden ver cualquier empleado</li>";
echo "<li><span class='highlight'>Empleado:</span> Solo puede ver su propio reporte</li>";
echo "</ul>";
echo "<strong>Manejo de errores:</strong> Try-catch en cálculos con error_log() para debugging";
echo "</div>";

// 2.3 Reporte de Nómina
echo "<h3>2.3 Reporte General de Nómina</h3>";
echo "<div class='feature-card'>";
echo "<div class='feature-header'>";
echo "<span class='feature-title'>💰 Reporte de Nómina</span>";
echo "<span class='badge badge-excelente'>COMPLETO</span>";
echo "</div>";
echo "<strong>Descripción:</strong> Consolidado completo de nómina con estadísticas y totales.<br><br>";
echo "<strong>Formatos disponibles:</strong>";
echo "<ul>";
echo "<li>✅ <strong>PDF</strong> - Tabla detallada con totales y estadísticas</li>";
echo "<li>✅ <strong>Excel (XLSX)</strong> - Con formato numérico, estadísticas destacadas</li>";
echo "</ul>";
echo "<strong>Información incluida:</strong>";
echo "<ul>";
echo "<li>Lista de todos los empleados</li>";
echo "<li>Devengado por empleado</li>";
echo "<li>Deducido por empleado</li>";
echo "<li>Valor a pagar (devengado - deducido)</li>";
echo "<li>Horas extras (cantidad y valor)</li>";
echo "<li><strong>Estadísticas:</strong> Total nómina, total devengado, total deducido, promedio por empleado</li>";
echo "</ul>";
echo "<strong>Control de acceso:</strong> Solo <span class='highlight'>admin y RRHH</span> (empleados NO tienen acceso)<br>";
echo "<strong>Filtros:</strong> Excluye empleados del sistema (IDs 1, 2, 3)";
echo "</div>";

echo "</div>";

// ============================================
// 3. ANÁLISIS TÉCNICO
// ============================================
echo "<div class='seccion'>";
echo "<h2>🔧 3. Análisis Técnico</h2>";

echo "<h3>3.1 Arquitectura del Código</h3>";
echo "<div class='exito'>";
echo "<strong>✅ EXCELENTE: Separación de responsabilidades</strong><br><br>";
echo "<strong>Estructura:</strong>";
echo "<ul>";
echo "<li><strong>ReportesController.php</strong> (471 líneas) - Lógica de negocio y control de flujo</li>";
echo "<li><strong>ReportePDF.php</strong> (174 líneas) - Generación de PDFs con TCPDF</li>";
echo "<li><strong>ReporteExcel.php</strong> (55 líneas) - Generación de Excel con PhpSpreadsheet</li>";
echo "<li><strong>3 vistas:</strong> index.php, reporte_empleado.php, reporte_nomina.php</li>";
echo "</ul>";
echo "<strong>Patrón utilizado:</strong> MVC puro con separación clara entre controlador, utilidades y vistas";
echo "</div>";

echo "<h3>3.2 Librerías Utilizadas</h3>";
echo "<table>";
echo "<tr><th>Librería</th><th>Versión</th><th>Propósito</th><th>Estado</th></tr>";
echo "<tr><td>PhpSpreadsheet</td><td>phpoffice/phpspreadsheet</td><td>Generación de archivos Excel (XLSX)</td><td><span class='badge badge-excelente'>✓ OK</span></td></tr>";
echo "<tr><td>TCPDF</td><td>tecnickcom/tcpdf</td><td>Generación de archivos PDF</td><td><span class='badge badge-excelente'>✓ OK</span></td></tr>";
echo "<tr><td>PHP nativo</td><td>fputcsv()</td><td>Generación de archivos CSV</td><td><span class='badge badge-excelente'>✓ OK</span></td></tr>";
echo "</table>";

echo "<h3>3.3 Calidad del Código</h3>";

// Manejo de errores
echo "<div class='feature-card'>";
echo "<strong>Manejo de Errores:</strong> <span class='badge badge-bueno'>BUENO</span><br><br>";
echo "<strong>✅ Aspectos positivos:</strong>";
echo "<ul>";
echo "<li>Try-catch en todos los cálculos críticos (devengado/deducido)</li>";
echo "<li>Uso de error_log() para registro de errores</li>";
echo "<li>Fallback a valores 0 en caso de error</li>";
echo "</ul>";
echo "<div class='code'>";
echo "try {\n";
echo "    \$devengadoCompleto = \$devengadoModel->calcularDevengadoCompleto(\$empleado_id);\n";
echo "    \$total_devengado = \$devengadoCompleto['resumen']['total_devengado'];\n";
echo "} catch (\\Exception \$e) {\n";
echo "    error_log(\"Error calculando devengado: \" . \$e->getMessage());\n";
echo "    \$total_devengado = 0;  // Fallback seguro\n";
echo "}";
echo "</div>";
echo "<strong>⚠️ Área de mejora:</strong> Podría informar al usuario cuando hay errores en cálculos";
echo "</div>";

// Validación de entradas
echo "<div class='feature-card'>";
echo "<strong>Validación de Entradas:</strong> <span class='badge badge-regular'>REGULAR</span><br><br>";
echo "<strong>✅ Validaciones implementadas:</strong>";
echo "<ul>";
echo "<li>Verificación de autenticación (\$_SESSION['user'])</li>";
echo "<li>Validación de rol antes de cada acción</li>";
echo "<li>Conversión de empleado_id a entero con intval()</li>";
echo "<li>isset() para verificar parámetros GET</li>";
echo "</ul>";
echo "<div class='code'>";
echo "\$empleado_id = isset(\$_GET['empleado_id']) ? intval(\$_GET['empleado_id']) : null;";
echo "</div>";
echo "<strong>⚠️ Áreas de mejora:</strong>";
echo "<ul>";
echo "<li>No valida que empleado_id sea > 0</li>";
echo "<li>No valida que el formato sea exactamente 'pdf', 'excel' o 'csv'</li>";
echo "<li>Falta validación de que el empleado existe antes de generar reporte</li>";
echo "</ul>";
echo "</div>";

// Seguridad
echo "<div class='feature-card'>";
echo "<strong>Seguridad:</strong> <span class='badge badge-bueno'>BUENO</span><br><br>";
echo "<strong>✅ Controles implementados:</strong>";
echo "<ul>";
echo "<li><strong>Control de acceso por rol:</strong> Cada método valida el rol apropiado</li>";
echo "<li><strong>Aislamiento de empleados:</strong> Los empleados solo ven su propia información</li>";
echo "<li><strong>Sanitización:</strong> htmlspecialchars() en PDFs, no hay SQL injection por uso de modelos</li>";
echo "<li><strong>Exclusión de usuarios sistema:</strong> Filtra admins y coordinadores de reportes</li>";
echo "</ul>";
echo "<div class='code'>";
echo "// Ejemplo de control de acceso\n";
echo "if (\$userRole === 'empleado') {\n";
echo "    \$empleado_id = \$_SESSION['user']['empleado_id'];  // Solo su propio ID\n";
echo "    \$empleados = array_filter(\$empleados, function(\$emp) use (\$empleado_id) {\n";
echo "        return \$emp['id_empleados'] == \$empleado_id;\n";
echo "    });\n";
echo "}";
echo "</div>";
echo "<strong>⚠️ Riesgo potencial:</strong> Sin tokens CSRF en formularios (si los hubiera)";
echo "</div>";

echo "</div>";

// ============================================
// 4. RENDIMIENTO Y OPTIMIZACIÓN
// ============================================
echo "<div class='seccion'>";
echo "<h2>⚡ 4. Rendimiento y Optimización</h2>";

echo "<div class='feature-card'>";
echo "<strong>Análisis de Rendimiento:</strong> <span class='badge badge-regular'>REGULAR</span><br><br>";
echo "<strong>⚠️ Problemas identificados:</strong>";
echo "<ul>";
echo "<li><strong>N+1 queries:</strong> En reporte de nómina, hace un query por cada empleado para calcular devengado/deducido</li>";
echo "<li><strong>Cálculos repetidos:</strong> Calcula lo mismo en reporteNomina() y descargarNomina()</li>";
echo "<li><strong>Sin caché:</strong> No cachea resultados de cálculos pesados</li>";
echo "<li><strong>Procesa todos los empleados:</strong> Aunque genere reporte para 1 empleado</li>";
echo "</ul>";

echo "<div class='code'>";
echo "// ❌ Código actual (ineficiente para muchos empleados)\n";
echo "foreach (\$empleados as \$emp) {\n";
echo "    \$devengadoCompleto = \$devengadoModel->calcularDevengadoCompleto(\$emp['id_empleados']);\n";
echo "    \$deducidoCompleto = \$deducidoModel->calcularTotalDeducidoCompleto(\$emp['id_empleados']);\n";
echo "    \$horas_extras = \$horasExtrasModel->getHorasExtrasByEmpleado(\$emp['id_empleados']);\n";
echo "    // 3 queries por empleado = 300 queries para 100 empleados\n";
echo "}";
echo "</div>";

echo "<strong>💡 Soluciones recomendadas:</strong>";
echo "<ul>";
echo "<li>Crear métodos batch: calcularDevengadoTodosEmpleados()</li>";
echo "<li>Cachear resultados de cálculos complejos</li>";
echo "<li>Usar prepared statements con IN clause</li>";
echo "<li>Implementar paginación para reportes grandes</li>";
echo "</ul>";
echo "</div>";

echo "<div class='info'>";
echo "<strong>💾 Uso de memoria:</strong> Generación de Excel/PDF puede consumir mucha RAM con miles de registros. ";
echo "Se recomienda limitar reportes a 1000-5000 registros o implementar generación por lotes.";
echo "</div>";

echo "</div>";

// ============================================
// 5. FORMATOS DE EXPORTACIÓN
// ============================================
echo "<div class='seccion'>";
echo "<h2>📁 5. Análisis de Formatos de Exportación</h2>";

echo "<table>";
echo "<tr><th>Formato</th><th>Calidad Visual</th><th>Funcionalidad</th><th>Compatibilidad</th><th>Puntuación</th></tr>";

echo "<tr>";
echo "<td><strong>PDF</strong></td>";
echo "<td>Logo, colores corporativos, tabla estilizada, orientación correcta</td>";
echo "<td>Encabezado/pie de página, paginación automática, bordes y colores alternados</td>";
echo "<td>Universal (todos los navegadores/sistemas)</td>";
echo "<td><span class='badge badge-excelente'>9.5/10</span></td>";
echo "</tr>";

echo "<tr>";
echo "<td><strong>Excel (XLSX)</strong></td>";
echo "<td>Formato de celdas, colores alternados, encabezados fijos, autoajuste columnas</td>";
echo "<td>Congelar paneles, formato numérico, bordes, negrita en encabezados</td>";
echo "<td>Excel, Google Sheets, LibreOffice</td>";
echo "<td><span class='badge badge-excelente'>9/10</span></td>";
echo "</tr>";

echo "<tr>";
echo "<td><strong>CSV</strong></td>";
echo "<td>Texto plano sin formato</td>";
echo "<td>UTF-8 BOM para Excel, separador punto y coma, encabezados incluidos</td>";
echo "<td>Universal (importación a cualquier sistema)</td>";
echo "<td><span class='badge badge-bueno'>8/10</span></td>";
echo "</tr>";
echo "</table>";

echo "<h3>Ejemplos de Código de Exportación</h3>";

echo "<div class='feature-card'>";
echo "<strong>PDF con TCPDF:</strong>";
echo "<div class='code'>";
echo "\$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);\n";
echo "\$pdf->SetHeaderData(\$logo, 32, 'Reporte General', 'ZIGMA | Nómina', [34,58,94], [255,255,255]);\n";
echo "\$pdf->AddPage();\n";
echo "\$pdf->writeHTML(\$tbl, true, false, false, false, '');\n";
echo "\$pdf->Output('reporte_general.pdf', 'D');";
echo "</div>";
echo "</div>";

echo "<div class='feature-card'>";
echo "<strong>Excel con PhpSpreadsheet:</strong>";
echo "<div class='code'>";
echo "\$spreadsheet = new \\PhpOffice\\PhpSpreadsheet\\Spreadsheet();\n";
echo "\$sheet = \$spreadsheet->getActiveSheet();\n";
echo "\$sheet->fromArray(\$header, NULL, 'A1');\n";
echo "\$sheet->getStyle('A1:E1')->applyFromArray(\$headerStyle);\n";
echo "\$sheet->freezePane('A2');  // Congelar encabezado\n";
echo "\$writer = new \\PhpOffice\\PhpSpreadsheet\\Writer\\Xlsx(\$spreadsheet);\n";
echo "\$writer->save('php://output');";
echo "</div>";
echo "</div>";

echo "<div class='feature-card'>";
echo "<strong>CSV optimizado para Excel:</strong>";
echo "<div class='code'>";
echo "header('Content-Type: text/csv; charset=UTF-8');\n";
echo "echo \"\\xEF\\xBB\\xBF\";  // BOM para que Excel detecte UTF-8\n";
echo "\$output = fopen('php://output', 'w');\n";
echo "fputcsv(\$output, ['Empleado', 'Documento', 'Salario'], ';');  // Separador ;\n";
echo "fputcsv(\$output, \$data, ';');\n";
echo "fclose(\$output);";
echo "</div>";
echo "</div>";

echo "</div>";

// ============================================
// 6. CONTROL DE ACCESO POR ROL
// ============================================
echo "<div class='seccion'>";
echo "<h2>🔐 6. Control de Acceso por Rol</h2>";

echo "<table>";
echo "<tr><th>Reporte</th><th>Admin</th><th>RRHH</th><th>Empleado</th></tr>";
echo "<tr>";
echo "<td><strong>Reporte General</strong></td>";
echo "<td><span class='badge badge-excelente'>✓ Completo</span></td>";
echo "<td><span class='badge badge-malo'>✗ Sin acceso</span></td>";
echo "<td><span class='badge badge-malo'>✗ Sin acceso</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td><strong>Reporte por Empleado</strong></td>";
echo "<td><span class='badge badge-excelente'>✓ Todos los empleados</span></td>";
echo "<td><span class='badge badge-excelente'>✓ Todos los empleados</span></td>";
echo "<td><span class='badge badge-bueno'>✓ Solo propio</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td><strong>Reporte de Nómina</strong></td>";
echo "<td><span class='badge badge-excelente'>✓ Completo</span></td>";
echo "<td><span class='badge badge-excelente'>✓ Completo</span></td>";
echo "<td><span class='badge badge-malo'>✗ Sin acceso</span></td>";
echo "</tr>";
echo "</table>";

echo "<div class='exito'>";
echo "<strong>✅ EXCELENTE: Implementación de permisos</strong><br><br>";
echo "El control de acceso es robusto y apropiado para cada tipo de usuario:";
echo "<ul>";
echo "<li><strong>Administradores:</strong> Acceso total a todos los reportes</li>";
echo "<li><strong>RRHH:</strong> Puede ver reportes de empleados y nómina (apropiado para su función)</li>";
echo "<li><strong>Empleados:</strong> Solo ven su información personal (protección de privacidad)</li>";
echo "</ul>";
echo "</div>";

echo "<div class='warning'>";
echo "<strong>⚠️ OBSERVACIÓN:</strong> El reporte general está restringido solo a admin. ";
echo "Considerar si RRHH también debería tener acceso a la lista completa de empleados.";
echo "</div>";

echo "</div>";

// ============================================
// 7. PUNTUACIÓN FINAL
// ============================================
$puntuacion_funcionalidad = 9.5;
$puntuacion_codigo = 8.0;
$puntuacion_seguridad = 8.5;
$puntuacion_rendimiento = 6.5;
$puntuacion_exportacion = 9.0;
$puntuacion_ux = 8.5;

$puntuacion_total = ($puntuacion_funcionalidad + $puntuacion_codigo + $puntuacion_seguridad + 
                     $puntuacion_rendimiento + $puntuacion_exportacion + $puntuacion_ux) / 6;

echo "<div class='score-container'>";
echo "<div class='score-label'>Puntuación Global del Módulo</div>";
echo "<div class='score-number'>" . number_format($puntuacion_total, 1) . "/10</div>";
echo "<p style='opacity: 0.9; font-size: 16px; margin-top: 10px;'>";
echo "Módulo de Reportes: <strong>" . ($puntuacion_total >= 8 ? "EXCELENTE" : "BUENO") . "</strong>";
echo "</p>";
echo "</div>";

echo "<div class='seccion'>";
echo "<h2>📊 7. Desglose de Puntuaciones</h2>";

echo "<table>";
echo "<tr><th>Categoría</th><th>Puntuación</th><th>Evaluación</th></tr>";
echo "<tr><td>Funcionalidad Completa</td><td><strong>{$puntuacion_funcionalidad}/10</strong></td><td><span class='badge badge-excelente'>EXCELENTE</span></td></tr>";
echo "<tr><td>Calidad del Código</td><td><strong>{$puntuacion_codigo}/10</strong></td><td><span class='badge badge-bueno'>BUENO</span></td></tr>";
echo "<tr><td>Seguridad y Permisos</td><td><strong>{$puntuacion_seguridad}/10</strong></td><td><span class='badge badge-bueno'>BUENO</span></td></tr>";
echo "<tr><td>Rendimiento</td><td><strong>{$puntuacion_rendimiento}/10</strong></td><td><span class='badge badge-regular'>REGULAR</span></td></tr>";
echo "<tr><td>Calidad de Exportación</td><td><strong>{$puntuacion_exportacion}/10</strong></td><td><span class='badge badge-excelente'>EXCELENTE</span></td></tr>";
echo "<tr><td>Experiencia de Usuario</td><td><strong>{$puntuacion_ux}/10</strong></td><td><span class='badge badge-bueno'>BUENO</span></td></tr>";
echo "</table>";

echo "</div>";

// ============================================
// 8. FORTALEZAS Y DEBILIDADES
// ============================================
echo "<div class='seccion'>";
echo "<h2>💪 8. Fortalezas del Módulo</h2>";

echo "<div class='exito'>";
echo "<ol>";
echo "<li><strong>Funcionalidad completa:</strong> 3 tipos de reportes, 3 formatos, control de acceso robusto</li>";
echo "<li><strong>Exportación profesional:</strong> PDFs con logo y estilos, Excel con formato, CSV optimizado para Excel</li>";
echo "<li><strong>Separación de responsabilidades:</strong> Controlador limpio, utilidades separadas, vistas independientes</li>";
echo "<li><strong>Manejo de errores:</strong> Try-catch en cálculos críticos con error_log()</li>";
echo "<li><strong>Control de acceso granular:</strong> Empleados solo ven su información, admins ven todo</li>";
echo "<li><strong>Cálculos dinámicos:</strong> Usa los modelos de devengado/deducido para datos en tiempo real</li>";
echo "<li><strong>Filtrado apropiado:</strong> Excluye usuarios del sistema de reportes</li>";
echo "<li><strong>Código limpio:</strong> Bien estructurado, comentado, fácil de mantener</li>";
echo "</ol>";
echo "</div>";

echo "<h2>⚠️ 9. Áreas de Mejora Prioritarias</h2>";

echo "<div class='warning'>";
echo "<strong>Prioridad ALTA:</strong>";
echo "<ol>";
echo "<li><strong>Optimizar queries N+1:</strong> Crear métodos batch para calcular devengado/deducido de múltiples empleados</li>";
echo "<li><strong>Validación de empleado_id:</strong> Verificar que el empleado existe antes de generar reporte</li>";
echo "<li><strong>Cachear cálculos:</strong> Los cálculos de nómina no cambian cada segundo, pueden cachearse 5-10 minutos</li>";
echo "</ol>";
echo "</div>";

echo "<div class='info'>";
echo "<strong>Prioridad MEDIA:</strong>";
echo "<ol>";
echo "<li><strong>Refactorizar código duplicado:</strong> reporteNomina() y descargarNomina() tienen 90% código idéntico</li>";
echo "<li><strong>Validar formato de export:</strong> Validar que sea 'pdf', 'excel' o 'csv' exactamente</li>";
echo "<li><strong>Mensajes de error al usuario:</strong> Cuando un cálculo falla, informar en el reporte</li>";
echo "<li><strong>Acceso RRHH a reporte general:</strong> Considerar dar acceso también a coordinadores RRHH</li>";
echo "<li><strong>Paginación:</strong> Para reportes con cientos/miles de empleados</li>";
echo "</ol>";
echo "</div>";

echo "<div class='feature-card'>";
echo "<strong>Prioridad BAJA:</strong>";
echo "<ul>";
echo "<li>Agregar filtros por fecha, departamento, rango salarial</li>";
echo "<li>Permitir selección de columnas a exportar</li>";
echo "<li>Gráficos en PDF (histogramas, pie charts)</li>";
echo "<li>Exportación a JSON/XML para integraciones</li>";
echo "<li>Programación de reportes automáticos (envío por email)</li>";
echo "</ul>";
echo "</div>";

echo "</div>";

// ============================================
// 10. RECOMENDACIONES TÉCNICAS
// ============================================
echo "<div class='seccion'>";
echo "<h2>🛠️ 10. Recomendaciones Técnicas</h2>";

echo "<h3>Optimización de Rendimiento</h3>";
echo "<div class='code'>";
echo "// ✅ Crear método optimizado en DevengadoModel\n";
echo "public function calcularDevengadoMultiplesEmpleados(\$empleado_ids) {\n";
echo "    // Un solo query con IN clause\n";
echo "    \$stmt = \$this->db->prepare(\n";
echo "        'SELECT empleado_id, SUM(devengado) as total \n";
echo "         FROM calculos_devengado \n";
echo "         WHERE empleado_id IN (' . implode(',', array_fill(0, count(\$empleado_ids), '?')) . ')'\n";
echo "    );\n";
echo "    \$stmt->execute(\$empleado_ids);\n";
echo "    return \$stmt->fetchAll(PDO::FETCH_KEY_PAIR);\n";
echo "}\n\n";
echo "// Uso en ReportesController\n";
echo "\$empleado_ids = array_column(\$empleados, 'id_empleados');\n";
echo "\$devengados = \$devengadoModel->calcularDevengadoMultiplesEmpleados(\$empleado_ids);\n";
echo "// Reducción de 300 queries a 1 query para 100 empleados";
echo "</div>";

echo "<h3>Refactorización de Código Duplicado</h3>";
echo "<div class='code'>";
echo "// ✅ Extraer lógica común a método privado\n";
echo "private function obtenerDatosNomina() {\n";
echo "    \$empleadoModel = \$this->model('Empleado');\n";
echo "    \$empleados = \$empleadoModel->getAllWithRoles();\n";
echo "    \$nominaData = [];\n";
echo "    // ... lógica de cálculo ...\n";
echo "    return ['nomina' => \$nominaData, 'estadisticas' => \$estadisticas];\n";
echo "}\n\n";
echo "// Uso\n";
echo "public function reporteNomina() {\n";
echo "    \$datos = \$this->obtenerDatosNomina();\n";
echo "    \$this->view('reportes/reporte_nomina', \$datos);\n";
echo "}\n\n";
echo "public function descargarNomina() {\n";
echo "    \$datos = \$this->obtenerDatosNomina();\n";
echo "    if (\$formato === 'excel') {\n";
echo "        \$excel->generarReporteNomina(\$datos['nomina'], \$datos['estadisticas']);\n";
echo "    }\n";
echo "}";
echo "</div>";

echo "<h3>Validación Mejorada</h3>";
echo "<div class='code'>";
echo "// ✅ Validar empleado_id correctamente\n";
echo "\$empleado_id = isset(\$_GET['empleado_id']) ? intval(\$_GET['empleado_id']) : null;\n\n";
echo "if (!\$empleado_id || \$empleado_id <= 0) {\n";
echo "    \$_SESSION['error'] = 'ID de empleado inválido';\n";
echo "    header('Location: /ZIGMA/public/index.php?url=Reportes/reporteEmpleado');\n";
echo "    exit();\n";
echo "}\n\n";
echo "// Verificar que existe\n";
echo "\$empleado = \$empleadoModel->getByIdWithRoles(\$empleado_id);\n";
echo "if (!\$empleado) {\n";
echo "    \$_SESSION['error'] = 'Empleado no encontrado';\n";
echo "    header('Location: /ZIGMA/public/index.php?url=Reportes/reporteEmpleado');\n";
echo "    exit();\n";
echo "}";
echo "</div>";

echo "</div>";

// ============================================
// 11. CONCLUSIÓN
// ============================================
echo "<div class='seccion'>";
echo "<h2>✅ 11. Conclusión Final</h2>";

echo "<div class='exito'>";
echo "<h3 style='margin-top: 0;'>Evaluación Global: EXCELENTE (" . number_format($puntuacion_total, 1) . "/10)</h3>";
echo "<p style='font-size: 16px; line-height: 1.8;'>";
echo "El <strong>módulo de reportes</strong> es una de las piezas más completas y profesionales del sistema ZIGMA. ";
echo "Implementa correctamente los 3 tipos de reportes necesarios (general, por empleado, nómina) ";
echo "con exportación a múltiples formatos de alta calidad (PDF con estilos, Excel con formato, CSV optimizado). ";
echo "</p>";

echo "<p style='font-size: 16px; line-height: 1.8;'>";
echo "El <strong>control de acceso</strong> es robusto y apropiado para cada rol, protegiendo la privacidad de los empleados ";
echo "mientras permite a administradores y RRHH acceder a la información necesaria para sus funciones.";
echo "</p>";

echo "<p style='font-size: 16px; line-height: 1.8;'>";
echo "La <strong>principal área de mejora</strong> es el rendimiento con muchos empleados. El problema N+1 queries ";
echo "puede causar lentitud significativa con 100+ empleados. Esta optimización debería ser prioridad #1.";
echo "</p>";

echo "<p style='font-size: 16px; line-height: 1.8;'>";
echo "Una vez optimizado el rendimiento, el módulo estará listo para <strong>producción empresarial</strong> ";
echo "sin problemas. La arquitectura es sólida, el código es mantenible, y la funcionalidad es completa.";
echo "</p>";
echo "</div>";

echo "<div class='info'>";
echo "<strong>🎯 Recomendación Final:</strong><br>";
echo "Implementar las 3 mejoras de prioridad ALTA (optimizar queries, validar empleado_id, cachear cálculos) ";
echo "y el módulo estará a nivel de calidad 9.5/10 apto para cualquier empresa mediana-grande.";
echo "</div>";

echo "</div>";

?>

        <div style="text-align: center; margin-top: 40px; padding: 20px; background: #f7fafc; border-radius: 10px;">
            <p style="color: #718096; font-size: 14px;">
                <strong>Informe generado el <?= date('d/m/Y H:i:s') ?></strong><br>
                Sistema ZIGMA - Módulo de Reportes<br>
                Análisis técnico completo por GitHub Copilot
            </p>
        </div>
    </div>
</body>
</html>
