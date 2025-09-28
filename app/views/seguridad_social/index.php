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
        <h1><?php echo htmlspecialchars($title ?? 'Cálculo de Seguridad Social'); ?></h1>
        
        <div class="nav-links">
            <a href="/seguridad_social" class="btn">Vista Principal</a>
            <a href="/seguridad_social/completo" class="btn btn-secondary">Cálculo Completo</a>
            <a href="/seguridad_social/indexConARL" class="btn btn-secondary">Con ARL</a>
            <a href="/seguridad_social/configuracion" class="btn btn-secondary">Configuración</a>
            <a href="/seguridad_social/reporteJson" class="btn btn-secondary">Reporte JSON</a>
        </div>
        
        <div style="background-color: #e8f5e8; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #28a745;">
            <h3>💼 Sistema Basado en Salarios Individuales</h3>
            <p><strong>Los cálculos se realizan usando el salario individual asignado a cada empleado.</strong></p>
            <p>• Cada empleado tiene su propio salario en el campo <code>sueldo_actual</code></p>
            <p>• Los roles son solo informativos y no afectan los cálculos</p>
            <p>• Para modificar salarios, edita directamente la información del empleado</p>
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
            <h3>Configurar Período de Cálculo</h3>
            <form method="POST" action="/seguridad_social/actualizarDias" class="form-inline">
                <label for="dias_trabajados">Días trabajados:</label>
                <input type="number" name="dias_trabajados" id="dias_trabajados" 
                       value="<?php echo $dias_trabajados ?? 30; ?>" 
                       min="1" max="31" required>
                <button type="submit" class="btn">Recalcular</button>
            </form>
        </div>

        <?php if (!empty($calculos_empleados)): ?>
            <!-- Tabla de cálculos por empleado -->
            <h2>Cálculos de Seguridad Social por Empleado</h2>
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
                        <th>Total Seguridad Social</th>
                        <th>Acciones</th>
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
                                <?php echo htmlspecialchars(implode(', ', $calculo['empleado']['roles'])); ?>
                            </td>
                            <td class="text-right currency">
                                $<?php echo number_format($calculo['salario_base'], 2); ?>
                            </td>
                            <td class="text-right">
                                <?php echo $calculo['dias_trabajados']; ?>
                            </td>
                            <td class="text-right currency">
                                $<?php echo number_format($calculo['salario_proporcional'], 2); ?>
                            </td>
                            <td class="text-right currency">
                                $<?php echo number_format($calculo['aportes_empleado']['salud']['valor'], 2); ?>
                            </td>
                            <td class="text-right currency">
                                $<?php echo number_format($calculo['aportes_empleado']['pension']['valor'], 2); ?>
                            </td>
                            <td class="text-right currency">
                                <strong>$<?php echo number_format($calculo['aportes_empleado']['total'], 2); ?></strong>
                            </td>
                            <td>
                                <a href="/seguridad_social/calcular/<?php echo $calculo['empleado']['id']; ?>" class="btn btn-secondary" style="font-size: 12px; padding: 5px 10px;">Ver Detalle</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Resumen total -->
            <?php if (!empty($resumen_total)): ?>
                <div class="resumen-total">
                    <h2>Resumen Total</h2>
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
                            <div class="highlight">
                                <strong>Total Seguridad Social:</strong> 
                                <span class="currency">$<?php echo number_format($resumen_total['totales']['total_seguridad_social'], 2); ?></span>
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
                            <div class="highlight">
                                <strong>Promedio Seguridad Social:</strong> 
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
                <li>Total Empleado: 8.0%</li>
            </ul>
            <p><em>Cálculos basados en el PROM de Seguridad Social - Generado el <?php echo date('Y-m-d H:i:s'); ?></em></p>
        </div>
    </div>
</body>
</html>