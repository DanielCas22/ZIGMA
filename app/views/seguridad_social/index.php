<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Seguridad Social'); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .form-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-inline {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .resumen-total {
            background-color: #e9f7ff;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .highlight {
            background-color: #fff3cd;
            padding: 10px;
            border-radius: 4px;
            margin: 5px 0;
        }
        .currency {
            font-family: monospace;
            font-weight: bold;
        }
        .nav-links {
            margin-bottom: 20px;
        }
        .nav-links a {
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($title ?? 'Cálculo de Seguridad Social + ARL'); ?></h1>
        
        <div style="margin-bottom: 20px;">
            <a href="/ZIGMA/public/index.php?url=Dashboard/index" class="btn" style="background-color: #007bff; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block;">
                🏠 Volver al inicio
            </a>
        </div>
        
        <div class="nav-links">
            <a href="/seguridad_social" class="btn">Vista Principal</a>
        </div>
        
        <?php /* 
        <div style="background-color: #e8f5e8; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #28a745;">
            <h3>💼 Sistema Basado en Salarios Individuales</h3>
            <p><strong>Los cálculos se realizan usando el salario individual asignado a cada empleado.</strong></p>
            <p>• Cada empleado tiene su propio salario en el campo <code>sueldo_actual</code></p>
            <p>• Los roles son solo informativos y no afectan los cálculos</p>
            <p>• Para modificar salarios, edita directamente la información del empleado</p>
        </div>
        */ ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Formulario para actualizar días trabajados -->
        <div class="form-section">
            <h3>⏱️ Configurar Período de Cálculo</h3>
            <p style="font-size: 14px; color: #666; margin-bottom: 15px;">
                Ajuste los días trabajados para recalcular automáticamente los valores de seguridad social y ARL.
            </p>
            <form method="POST" action="/ZIGMA/public/index.php?url=SeguridadSocial/actualizarDias" class="form-inline">
                <label for="dias_trabajados" style="margin-right: 10px; font-weight: bold;">Días trabajados:</label>
                <input type="number" name="dias_trabajados" id="dias_trabajados" 
                       value="<?php echo $dias_trabajados ?? 30; ?>" 
                       min="1" max="31" required
                       style="margin-right: 10px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 80px; text-align: center;">
                <button type="submit" class="btn" style="padding: 8px 15px;">
                    🔄 Recalcular
                </button>
                <small style="margin-left: 15px; color: #666;">
                    (Valores entre 1 y 31 días)
                </small>
            </form>
        </div>

        <?php if (!empty($calculos_empleados)): ?>
            <!-- Tabla de cálculos por empleado -->
            <h2>📊 Cálculos de Seguridad Social + ARL por Empleado</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Empleado</th>
                        <th>Roles</th>
                        <th>Salario Individual</th>
                        <th>Días Trabajados</th>
                        <th>Salario Proporcional</th>
                        <th>Salud (4%)</th>
                        <th>Pensión (4%)</th>
                        <th style="background-color: #fff3cd;">Riesgo ARL</th>
                        <th style="background-color: #fff3cd;">ARL (%)</th>
                        <th style="background-color: #fff3cd;">Valor ARL</th>
                        <th style="background-color: #e8f5e8; font-weight: bold;">Total S.S. + ARL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($calculos_empleados as $calculo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($calculo['empleado']['id']); ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($calculo['empleado']['nombre'] . ' ' . $calculo['empleado']['apellido']); ?></strong>
                            </td>
                            <td>
                                <?php 
                                    $roles = $calculo['empleado']['roles'];
                                    // Debug temporal - mostrar qué contiene roles
                                    // echo "<!-- Debug: " . print_r($roles, true) . " -->";
                                    
                                    $rolesFormateados = [];
                                    
                                    // Verificar si roles es array
                                    if (is_array($roles)) {
                                        // Siempre incluir empleado primero
                                        $rolesFormateados[] = 'empleado';
                                        
                                        // Agregar otros roles (excluyendo empleado para evitar duplicación)
                                        foreach ($roles as $rol) {
                                            if (strtolower(trim($rol)) !== 'empleado') {
                                                $rolesFormateados[] = trim($rol);
                                            }
                                        }
                                    } else {
                                        // Si no es array, mostrar como string
                                        $rolesFormateados[] = is_string($roles) ? $roles : 'empleado';
                                    }
                                    
                                    // Mostrar roles separados por "/"
                                    echo htmlspecialchars(implode(' / ', $rolesFormateados));
                                ?>
                            </td>
                            <td class="text-right currency">
                                $<?php echo number_format($calculo['total_devengado'] ?? 0, 2); ?>
                            </td>
                            <td class="text-right">
                                30 <!-- Días fijos según nueva lógica -->
                            </td>
                            <td class="text-right currency">
                                $<?php echo number_format($calculo['base_calculo'] ?? 0, 2); ?>
                            </td>
                            <td class="text-right currency">
                                $<?php echo number_format(($calculo['seguridad_social']['empleado']['salud']['valor'] ?? 0), 2); ?>
                            </td>
                            <td class="text-right currency">
                                $<?php echo number_format(($calculo['seguridad_social']['empleado']['pension']['valor'] ?? 0), 2); ?>
                            </td>
                            <td class="text-right" style="background-color: #fff3cd;">
                                <span style="padding: 2px 6px; border-radius: 3px; background-color: 
                                    <?php 
                                        $colors = ['#e8f5e8', '#fff3cd', '#ffeaa7', '#fab1a0', '#e17055'];
                                        echo $colors[($calculo['arl']['codigo_riesgo'] ?? 2) - 1];
                                    ?>; font-size: 11px; font-weight: bold;">
                                    Clase <?php echo ['I', 'II', 'III', 'IV', 'V'][($calculo['arl']['codigo_riesgo'] ?? 2) - 1]; ?>
                                </span>
                            </td>
                            <td class="text-right currency" style="background-color: #fff3cd;">
                                <?php echo number_format(($calculo['arl']['porcentaje_arl'] ?? 0), 3); ?>%
                            </td>
                            <td class="text-right currency" style="background-color: #fff3cd;">
                                $<?php echo number_format(($calculo['arl']['valor_arl'] ?? 0), 2); ?>
                            </td>
                            <td class="text-right currency" style="background-color: #e8f5e8; font-weight: bold;">
                                <strong>$<?php echo number_format(($calculo['totales']['total_empleado'] ?? 0), 2); ?></strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Resumen total -->
            <?php if (!empty($resumen_total)): ?>
                <div class="resumen-total">
                    <h2>📈 Resumen Total - Seguridad Social + ARL</h2>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                        <div>
                            <h3>Totales Generales</h3>
                            <div class="highlight">
                                <strong>Total Empleados:</strong> <?php echo $resumen_total['total_empleados']; ?>
                            </div>
                            <div class="highlight">
                                <strong>Total Aportes Salud:</strong> 
                                <span class="currency">$<?php echo number_format($resumen_total['totales']['salud'], 2); ?></span>
                            </div>
                            <div class="highlight">
                                <strong>Total Aportes Pensión:</strong> 
                                <span class="currency">$<?php echo number_format($resumen_total['totales']['pension'], 2); ?></span>
                            </div>
                            <div class="highlight" style="background-color: #fff3cd;">
                                <strong>🛡️ Total Aportes ARL:</strong> 
                                <span class="currency">$<?php echo number_format($resumen_total['totales']['arl'], 2); ?></span>
                            </div>
                            <div class="highlight" style="background-color: #e8f5e8; border: 2px solid #28a745;">
                                <strong>💰 TOTAL SEGURIDAD SOCIAL + ARL:</strong> 
                                <span class="currency" style="font-size: 1.2em; color: #155724;">
                                    $<?php echo number_format($resumen_total['totales']['total_seguridad_social'], 2); ?>
                                </span>
                            </div>
                        </div>
                        <div>
                            <h3>Promedios por Empleado</h3>
                            <div class="highlight">
                                <strong>Promedio Aportes Salud:</strong> 
                                <span class="currency">$<?php echo number_format($resumen_total['promedios']['salud'], 2); ?></span>
                            </div>
                            <div class="highlight">
                                <strong>Promedio Aportes Pensión:</strong> 
                                <span class="currency">$<?php echo number_format($resumen_total['promedios']['pension'], 2); ?></span>
                            </div>
                            <div class="highlight" style="background-color: #fff3cd;">
                                <strong>🛡️ Promedio Aportes ARL:</strong> 
                                <span class="currency">$<?php echo number_format($resumen_total['promedios']['arl'], 2); ?></span>
                            </div>
                            <div class="highlight" style="background-color: #e8f5e8;">
                                <strong>Promedio Total S.S. + ARL:</strong> 
                                <span class="currency">$<?php echo number_format($resumen_total['promedios']['total_seguridad_social'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-error">
                No se pudieron calcular los aportes de seguridad social. Verifique que existan empleados registrados.
            </div>
        <?php endif; ?>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
            <p><strong>Porcentajes aplicados:</strong></p>
            <ul>
                <li>Salud Empleado: 4.0%</li>
                <li>Pensión Empleado: 4.0%</li>
                <li>🛡️ ARL según riesgo:</li>
                <ul style="margin-left: 20px;">
                    <li>Clase I (Mínimo): 0.522%</li>
                    <li>Clase II (Bajo): 1.044%</li>
                    <li>Clase III (Medio): 2.436%</li>
                    <li>Clase IV (Alto): 4.350%</li>
                    <li>Clase V (Máximo): 6.960%</li>
                </ul>
                <li><strong>Total Empleado: 8.0% + ARL variable</strong></li>
            </ul>
            <p><em>Cálculos basados en el PROM de Seguridad Social + ARL - Generado el <?php echo date('Y-m-d H:i:s'); ?></em></p>
        </div>
    </div>
</body>
</html>