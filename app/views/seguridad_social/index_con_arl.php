<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Seguridad Social + ARL'); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #28a745;
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
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #218838;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }
        .btn-warning:hover {
            background-color: #e0a800;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            position: sticky;
            top: 0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .resumen-total {
            background-color: #e8f5e8;
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
        .risk-badge {
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            color: white;
        }
        .risk-1 { background-color: #28a745; }
        .risk-2 { background-color: #17a2b8; }
        .risk-3 { background-color: #ffc107; color: #212529; }
        .risk-4 { background-color: #fd7e14; }
        .risk-5 { background-color: #dc3545; }
        .arl-column {
            background-color: #fff3cd;
        }
        .total-column {
            background-color: #d4edda;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛡️ <?php echo htmlspecialchars($title ?? 'Cálculo de Seguridad Social + ARL'); ?></h1>
        
        <div class="nav-links">
            <a href="/seguridad_social" class="btn btn-secondary">Seguridad Social</a>
            <a href="/seguridad_social/indexConARL" class="btn">Seguridad Social + ARL</a>
            <a href="/seguridad_social/gestionRiesgos" class="btn btn-warning">Gestión Riesgos</a>
            <a href="/seguridad_social/calculoARLPuro" class="btn btn-secondary">ARL PROM</a>
            <a href="/seguridad_social/configuracion" class="btn btn-secondary">Configuración</a>
        </div>
        
        <div style="background-color: #e8f5e8; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #28a745;">
            <h3>💼 Sistema Integrado por Empleado Individual</h3>
            <p><strong>Seguridad Social + ARL calculados sobre el salario individual de cada empleado.</strong></p>
            <p>• Salario individual: Campo <code>sueldo_actual</code> de cada empleado</p>
            <p>• Riesgo ARL: Asignado individualmente a cada empleado</p>
            <p>• Los roles son informativos, no afectan cálculos</p>
        </div>

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
            <h3>⚙️ Configurar Período de Cálculo</h3>
            <form method="POST" action="/seguridad_social/actualizarDiasConARL" class="form-inline">
                <label for="dias_trabajados">Días trabajados:</label>
                <input type="number" name="dias_trabajados" id="dias_trabajados" 
                       value="<?php echo $dias_trabajados ?? 30; ?>" 
                       min="1" max="31" required>
                <button type="submit" class="btn">Recalcular con ARL</button>
            </form>
        </div>

        <?php if (!empty($calculos_empleados)): ?>
            <!-- Tabla de cálculos por empleado -->
            <h2>📊 Cálculos de Seguridad Social + ARL por Empleado</h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2">ID</th>
                            <th rowspan="2">Empleado</th>
                            <th rowspan="2">Roles</th>
                            <th rowspan="2">Salario<br>Individual</th>
                            <th rowspan="2">Días<br>Trab.</th>
                            <th rowspan="2">Salario<br>Proporcional</th>
                            <th colspan="2" style="background-color: #e3f2fd;">Seguridad Social</th>
                            <th colspan="2" style="background-color: #fff3cd;">ARL</th>
                            <th rowspan="2" class="total-column">Total<br>Deducciones</th>
                            <th rowspan="2">Acciones</th>
                        </tr>
                        <tr>
                            <th style="background-color: #e3f2fd;">Salud<br>(4%)</th>
                            <th style="background-color: #e3f2fd;">Pensión<br>(4%)</th>
                            <th class="arl-column">Riesgo</th>
                            <th class="arl-column">Valor<br>ARL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($calculos_empleados as $calculo): ?>
                            <tr>
                                <td class="text-center"><?php echo htmlspecialchars($calculo['empleado']['id']); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($calculo['empleado']['nombre'] . ' ' . $calculo['empleado']['apellido']); ?></strong>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars(implode(', ', $calculo['empleado']['roles'])); ?>
                                </td>
                                <td class="text-right currency">
                                    $<?php echo number_format($calculo['salario_base'], 2); ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $calculo['dias_trabajados']; ?>
                                </td>
                                <td class="text-right currency">
                                    $<?php echo number_format($calculo['salario_proporcional'], 2); ?>
                                </td>
                                <td class="text-right currency">
                                    $<?php echo number_format($calculo['seguridad_social']['salud']['valor'], 2); ?>
                                </td>
                                <td class="text-right currency">
                                    $<?php echo number_format($calculo['seguridad_social']['pension']['valor'], 2); ?>
                                </td>
                                <td class="text-center">
                                    <span class="risk-badge risk-<?php echo $calculo['arl']['codigo_riesgo']; ?>">
                                        <?php echo $calculo['arl']['nivel_riesgo']['codigo']; ?>
                                        (<?php echo $calculo['arl']['porcentaje']; ?>%)
                                    </span>
                                </td>
                                <td class="text-right currency arl-column">
                                    $<?php echo number_format($calculo['arl']['valor'], 2); ?>
                                </td>
                                <td class="text-right currency total-column">
                                    <strong>$<?php echo number_format($calculo['totales']['total_deducciones'], 2); ?></strong>
                                </td>
                                <td class="text-center">
                                    <a href="/seguridad_social/calcularARL/<?php echo $calculo['empleado']['id']; ?>" 
                                       class="btn btn-secondary" style="font-size: 11px; padding: 4px 8px;">Ver Detalle</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Resumen total -->
            <?php if (!empty($resumen_total)): ?>
                <div class="resumen-total">
                    <h2>📈 Resumen Total Consolidado</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                        
                        <!-- Totales Generales -->
                        <div>
                            <h3>💰 Totales Generales</h3>
                            <div class="highlight">
                                <strong>Total Empleados:</strong> <?php echo $resumen_total['total_empleados']; ?>
                            </div>
                            <div class="highlight">
                                <strong>Total Salud:</strong> 
                                <span class="currency">$<?php echo number_format($resumen_total['totales']['salud'], 2); ?></span>
                            </div>
                            <div class="highlight">
                                <strong>Total Pensión:</strong> 
                                <span class="currency">$<?php echo number_format($resumen_total['totales']['pension'], 2); ?></span>
                            </div>
                            <div class="highlight" style="background-color: #fff3cd;">
                                <strong>Total ARL:</strong> 
                                <span class="currency">$<?php echo number_format($resumen_total['totales']['arl'], 2); ?></span>
                            </div>
                            <div class="highlight" style="background-color: #d4edda;">
                                <strong>TOTAL GENERAL:</strong> 
                                <span class="currency" style="font-size: 18px;">$<?php echo number_format($resumen_total['totales']['total_general'], 2); ?></span>
                            </div>
                        </div>
                        
                        <!-- Promedios -->
                        <div>
                            <h3>📊 Promedios por Empleado</h3>
                            <div class="highlight">
                                <strong>Promedio Salud:</strong> 
                                <span class="currency">$<?php echo number_format($resumen_total['promedios']['salud'], 2); ?></span>
                            </div>
                            <div class="highlight">
                                <strong>Promedio Pensión:</strong> 
                                <span class="currency">$<?php echo number_format($resumen_total['promedios']['pension'], 2); ?></span>
                            </div>
                            <div class="highlight" style="background-color: #fff3cd;">
                                <strong>Promedio ARL:</strong> 
                                <span class="currency">$<?php echo number_format($resumen_total['promedios']['arl'], 2); ?></span>
                            </div>
                            <div class="highlight" style="background-color: #d4edda;">
                                <strong>PROMEDIO GENERAL:</strong> 
                                <span class="currency" style="font-size: 18px;">$<?php echo number_format($resumen_total['promedios']['total_general'], 2); ?></span>
                            </div>
                        </div>

                        <!-- Distribución de Riesgo -->
                        <?php if (!empty($resumen_total['distribucion_riesgo'])): ?>
                            <div>
                                <h3>🎯 Distribución por Nivel de Riesgo</h3>
                                <?php foreach ($resumen_total['distribucion_riesgo'] as $codigo => $info): ?>
                                    <div class="highlight">
                                        <span class="risk-badge risk-<?php echo $codigo; ?>">
                                            Riesgo <?php echo $info['nivel_info']['codigo']; ?>
                                        </span>
                                        <strong><?php echo $info['cantidad']; ?> empleados</strong><br>
                                        <span style="font-size: 12px;">
                                            Total ARL: <span class="currency">$<?php echo number_format($info['total_arl'], 2); ?></span>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-error">
                No se pudieron calcular los aportes de seguridad social y ARL. Verifique que existan empleados registrados y que la tabla ARL esté configurada correctamente.
                <br><br>
                <a href="/setup_arl.php" class="btn btn-warning">🔧 Configurar ARL</a>
            </div>
        <?php endif; ?>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div>
                    <p><strong>Porcentajes Seguridad Social:</strong></p>
                    <ul>
                        <li>Salud Empleado: 4.0%</li>
                        <li>Pensión Empleado: 4.0%</li>
                        <li><strong>Total Seguridad Social: 8.0%</strong></li>
                    </ul>
                </div>
                <div>
                    <p><strong>Porcentajes ARL por Riesgo:</strong></p>
                    <ul>
                        <li>Riesgo I: 0.522%</li>
                        <li>Riesgo II: 1.044%</li>
                        <li>Riesgo III: 2.436%</li>
                        <li>Riesgo IV: 4.350%</li>
                        <li>Riesgo V: 6.960%</li>
                    </ul>
                </div>
            </div>
            <p><em>Cálculos basados en PROM de Seguridad Social + PROM ARL - Generado el <?php echo date('Y-m-d H:i:s'); ?></em></p>
        </div>
    </div>
</body>
</html>