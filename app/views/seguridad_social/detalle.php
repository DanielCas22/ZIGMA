<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Detalle de Seguridad Social'); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
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
        .employee-info {
            background-color: #e9f7ff;
            border: 1px solid #b8daff;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .calculation-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .calculation-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .calculation-row:last-child {
            border-bottom: none;
            font-weight: bold;
            background-color: #d1ecf1;
            margin: 15px -20px -20px -20px;
            padding: 15px 20px;
            border-radius: 0 0 5px 5px;
        }
        .currency {
            font-family: monospace;
            font-weight: bold;
            color: #28a745;
            font-size: 16px;
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
            margin-right: 10px;
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
        .nav-links {
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .info-item {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #007bff;
        }
        .roles-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .role-badge {
            background-color: #007bff;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($title ?? 'Detalle de Seguridad Social'); ?></h1>
        
        <div class="nav-links">
            <a href="/seguridad_social" class="btn btn-secondary">← Volver a Lista</a>
            <a href="/seguridad_social/completo" class="btn btn-secondary">Cálculo Completo</a>
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

        <?php if (isset($calculo) && $calculo): ?>
            <!-- Información del empleado -->
            <div class="employee-info">
                <h2>Información del Empleado</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>ID:</strong> <?php echo htmlspecialchars($calculo['empleado']['id']); ?>
                    </div>
                    <div class="info-item">
                        <strong>Nombre:</strong> <?php echo htmlspecialchars($calculo['empleado']['nombre'] . ' ' . $calculo['empleado']['apellido']); ?>
                    </div>
                    <div class="info-item">
                        <strong>Salario Base:</strong> 
                        <span class="currency">$<?php echo number_format($calculo['salario_base'], 2); ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Días Trabajados:</strong> <?php echo $calculo['dias_trabajados']; ?>
                    </div>
                </div>
                <div style="margin-top: 15px;">
                    <strong>Roles:</strong>
                    <div class="roles-list">
                        <?php foreach ($calculo['empleado']['roles'] as $rol): ?>
                            <span class="role-badge"><?php echo htmlspecialchars($rol); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Cálculos de seguridad social -->
            <div class="calculation-section">
                <h2>Cálculo de Seguridad Social</h2>
                
                <div class="calculation-row">
                    <span>Salario Base</span>
                    <span class="currency">$<?php echo number_format($calculo['salario_base'], 2); ?></span>
                </div>
                
                <div class="calculation-row">
                    <span>Días Trabajados</span>
                    <span><?php echo $calculo['dias_trabajados']; ?> días</span>
                </div>
                
                <div class="calculation-row">
                    <span>Salario Proporcional (<?php echo $calculo['dias_trabajados']; ?>/30)</span>
                    <span class="currency">$<?php echo number_format($calculo['salario_proporcional'], 2); ?></span>
                </div>
                
                <hr style="margin: 20px 0;">
                
                <div class="calculation-row">
                    <span>Aporte Salud (<?php echo $calculo['aportes_empleado']['salud']['porcentaje']; ?>%)</span>
                    <span class="currency">$<?php echo number_format($calculo['aportes_empleado']['salud']['valor'], 2); ?></span>
                </div>
                
                <div class="calculation-row">
                    <span>Aporte Pensión (<?php echo $calculo['aportes_empleado']['pension']['porcentaje']; ?>%)</span>
                    <span class="currency">$<?php echo number_format($calculo['aportes_empleado']['pension']['valor'], 2); ?></span>
                </div>
                
                <div class="calculation-row">
                    <span><strong>Total Seguridad Social</strong></span>
                    <span class="currency"><strong>$<?php echo number_format($calculo['aportes_empleado']['total'], 2); ?></strong></span>
                </div>
            </div>

            <!-- Resumen detallado -->
            <div class="calculation-section" style="background-color: #fff3cd; border-color: #ffeaa7;">
                <h3>Resumen Detallado</h3>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <div>
                        <h4>Fórmulas Aplicadas:</h4>
                        <ul>
                            <li><strong>Salario Proporcional:</strong><br>
                                (Salario Base × Días Trabajados) ÷ 30</li>
                            <li><strong>Salud:</strong><br>
                                Salario Proporcional × 4%</li>
                            <li><strong>Pensión:</strong><br>
                                Salario Proporcional × 4%</li>
                        </ul>
                    </div>
                    <div>
                        <h4>Valores Calculados:</h4>
                        <ul>
                            <li><strong>Salario Proporcional:</strong><br>
                                ($<?php echo number_format($calculo['salario_base'], 2); ?> × <?php echo $calculo['dias_trabajados']; ?>) ÷ 30 = 
                                <span class="currency">$<?php echo number_format($calculo['salario_proporcional'], 2); ?></span>
                            </li>
                            <li><strong>Total Deducciones:</strong><br>
                                <span class="currency">$<?php echo number_format($calculo['aportes_empleado']['total'], 2); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Salario neto estimado -->
            <div class="calculation-section" style="background-color: #d4edda; border-color: #c3e6cb;">
                <h3 style="color: #155724;">Salario Neto Estimado</h3>
                <div class="calculation-row" style="background-color: #c3e6cb; color: #155724;">
                    <span><strong>Salario Proporcional - Deducciones Seguridad Social</strong></span>
                    <span class="currency" style="color: #155724; font-size: 18px;">
                        <strong>$<?php echo number_format($calculo['salario_proporcional'] - $calculo['aportes_empleado']['total'], 2); ?></strong>
                    </span>
                </div>
                <p style="font-size: 12px; color: #155724; margin-top: 10px;">
                    <em>* Este cálculo no incluye otros descuentos como retención en la fuente, préstamos, etc.</em>
                </p>
            </div>

        <?php else: ?>
            <div class="alert alert-error">
                No se pudieron obtener los datos del empleado o no se realizó el cálculo correctamente.
            </div>
        <?php endif; ?>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
            <p><strong>Información del cálculo:</strong></p>
            <ul>
                <li>Los porcentajes de seguridad social son fijos según la legislación vigente</li>
                <li>El salario se calcula proporcionalmente según los días trabajados</li>
                <li>Este cálculo corresponde únicamente a la seguridad social (sin parafiscales)</li>
                <li>Basado en el PROM de Seguridad Social proporcionado</li>
            </ul>
        </div>
    </div>
</body>
</html>