<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Detalle Seguridad Social + ARL'); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
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
        .employee-info {
            background-color: #e8f5e8;
            border: 1px solid #c3e6cb;
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
        .arl-section {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
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
        .total-row {
            background-color: #d4edda;
            margin: 15px -20px -20px -20px;
            padding: 15px 20px;
            border-radius: 0 0 5px 5px;
            font-weight: bold;
            font-size: 18px;
        }
        .currency {
            font-family: monospace;
            font-weight: bold;
            color: #28a745;
            font-size: 16px;
        }
        .currency-arl {
            color: #e67e22;
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
            margin-right: 10px;
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
        .nav-links {
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .info-item {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #28a745;
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
        .risk-badge {
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: bold;
            color: white;
        }
        .risk-1 { background-color: #28a745; }
        .risk-2 { background-color: #17a2b8; }
        .risk-3 { background-color: #ffc107; color: #212529; }
        .risk-4 { background-color: #fd7e14; }
        .risk-5 { background-color: #dc3545; }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛡️ <?php echo htmlspecialchars($title ?? 'Detalle Seguridad Social + ARL'); ?></h1>
        
        <div class="nav-links">
            <a href="/seguridad_social/indexConARL" class="btn btn-secondary">← Volver a Lista</a>
            <a href="/seguridad_social/gestionRiesgos" class="btn btn-secondary">Gestión Riesgos</a>
            <a href="/seguridad_social/calculoARLPuro" class="btn btn-secondary">ARL PROM</a>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                ✅ <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($calculo) && $calculo): ?>
            <!-- Información del empleado -->
            <div class="employee-info">
                <h2>👤 Información del Empleado</h2>
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
                    <div class="info-item">
                        <strong>Salario Proporcional:</strong> 
                        <span class="currency">$<?php echo number_format($calculo['salario_proporcional'], 2); ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Nivel de Riesgo ARL:</strong>
                        <br>
                        <span class="risk-badge risk-<?php echo $calculo['arl']['codigo_riesgo']; ?>">
                            Riesgo <?php echo $calculo['arl']['nivel_riesgo']['codigo']; ?> (<?php echo $calculo['arl']['porcentaje']; ?>%)
                        </span>
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
                <h2>🏥 Cálculo de Seguridad Social</h2>
                
                <div class="calculation-row">
                    <span>Aporte Salud (<?php echo $calculo['seguridad_social']['salud']['porcentaje']; ?>%)</span>
                    <span class="currency">$<?php echo number_format($calculo['seguridad_social']['salud']['valor'], 2); ?></span>
                </div>
                
                <div class="calculation-row">
                    <span>Aporte Pensión (<?php echo $calculo['seguridad_social']['pension']['porcentaje']; ?>%)</span>
                    <span class="currency">$<?php echo number_format($calculo['seguridad_social']['pension']['valor'], 2); ?></span>
                </div>
                
                <div class="calculation-row">
                    <span><strong>Subtotal Seguridad Social</strong></span>
                    <span class="currency"><strong>$<?php echo number_format($calculo['totales']['seguridad_social'], 2); ?></strong></span>
                </div>
            </div>

            <!-- Cálculos de ARL -->
            <div class="arl-section">
                <h2>⚠️ Cálculo de ARL (<?php echo $calculo['arl']['nivel_riesgo']['descripcion']; ?>)</h2>
                
                <div class="calculation-row">
                    <span>Nivel de Riesgo</span>
                    <span class="risk-badge risk-<?php echo $calculo['arl']['codigo_riesgo']; ?>">
                        Riesgo <?php echo $calculo['arl']['nivel_riesgo']['codigo']; ?>
                    </span>
                </div>
                
                <div class="calculation-row">
                    <span>Porcentaje ARL</span>
                    <span><strong><?php echo $calculo['arl']['porcentaje']; ?>%</strong></span>
                </div>
                
                <div class="calculation-row">
                    <span>Base de Cálculo (Salario Proporcional)</span>
                    <span class="currency">$<?php echo number_format($calculo['salario_proporcional'], 2); ?></span>
                </div>
                
                <div class="calculation-row">
                    <span><strong>Aporte ARL</strong></span>
                    <span class="currency currency-arl"><strong>$<?php echo number_format($calculo['arl']['valor'], 2); ?></strong></span>
                </div>
            </div>

            <!-- Total consolidado -->
            <div class="calculation-section">
                <h2>💰 Total de Deducciones</h2>
                
                <div class="calculation-row">
                    <span>Seguridad Social (Salud + Pensión)</span>
                    <span class="currency">$<?php echo number_format($calculo['totales']['seguridad_social'], 2); ?></span>
                </div>
                
                <div class="calculation-row">
                    <span>ARL (Riesgo <?php echo $calculo['arl']['nivel_riesgo']['codigo']; ?>)</span>
                    <span class="currency currency-arl">$<?php echo number_format($calculo['arl']['valor'], 2); ?></span>
                </div>
                
                <div class="total-row">
                    <span><strong>TOTAL DEDUCCIONES EMPLEADO</strong></span>
                    <span class="currency" style="color: #dc3545; font-size: 20px;">
                        <strong>$<?php echo number_format($calculo['totales']['total_deducciones'], 2); ?></strong>
                    </span>
                </div>
            </div>

            <!-- Resumen detallado -->
            <div class="calculation-section" style="background-color: #e8f5e8; border-color: #c3e6cb;">
                <h3>📋 Resumen Completo</h3>
                <div class="summary-grid">
                    <div>
                        <h4>💰 Fórmulas Aplicadas:</h4>
                        <ul>
                            <li><strong>Salario Proporcional:</strong><br>
                                (Salario Base × Días Trabajados) ÷ 30</li>
                            <li><strong>Salud:</strong><br>
                                Salario Proporcional × 4%</li>
                            <li><strong>Pensión:</strong><br>
                                Salario Proporcional × 4%</li>
                            <li><strong>ARL:</strong><br>
                                Salario Proporcional × <?php echo $calculo['arl']['porcentaje']; ?>%</li>
                        </ul>
                    </div>
                    <div>
                        <h4>🧮 Valores Calculados:</h4>
                        <ul>
                            <li><strong>Base de cálculo:</strong><br>
                                <span class="currency">$<?php echo number_format($calculo['salario_proporcional'], 2); ?></span>
                            </li>
                            <li><strong>Porcentaje total aplicado:</strong><br>
                                8% (Seg. Social) + <?php echo $calculo['arl']['porcentaje']; ?>% (ARL) = 
                                <strong><?php echo (8 + $calculo['arl']['porcentaje']); ?>%</strong>
                            </li>
                            <li><strong>Total deducciones:</strong><br>
                                <span class="currency" style="color: #dc3545;">$<?php echo number_format($calculo['totales']['total_deducciones'], 2); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Salario neto estimado -->
            <div class="calculation-section" style="background-color: #d4edda; border-color: #c3e6cb;">
                <h3 style="color: #155724;">💵 Salario Neto Estimado</h3>
                <div class="calculation-row" style="background-color: #c3e6cb; color: #155724; font-size: 18px;">
                    <span><strong>Salario Proporcional - Deducciones Totales</strong></span>
                    <span class="currency" style="color: #155724; font-size: 22px;">
                        <strong>$<?php echo number_format($calculo['salario_proporcional'] - $calculo['totales']['total_deducciones'], 2); ?></strong>
                    </span>
                </div>
                <p style="font-size: 12px; color: #155724; margin-top: 10px;">
                    <em>* Este cálculo no incluye otros descuentos como retención en la fuente, préstamos, etc.</em>
                </p>
            </div>

        <?php else: ?>
            <div class="alert alert-error">
                No se pudieron obtener los datos del empleado o no se realizó el cálculo correctamente.
                <br><br>
                <strong>Posibles causas:</strong>
                <ul>
                    <li>El empleado no existe</li>
                    <li>No tiene un nivel de riesgo ARL asignado</li>
                    <li>No se pudo determinar el salario base</li>
                </ul>
                <a href="/seguridad_social/gestionRiesgos" class="btn" style="margin-top: 10px;">
                    🔧 Ir a Gestión de Riesgos
                </a>
            </div>
        <?php endif; ?>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div>
                    <p><strong>Información del cálculo:</strong></p>
                    <ul>
                        <li>Seguridad Social: Salud 4% + Pensión 4% = 8%</li>
                        <li>ARL: Variable según nivel de riesgo (0.522% - 6.960%)</li>
                        <li>El salario se calcula proporcionalmente según días trabajados</li>
                        <li>Los porcentajes se aplican sobre el salario proporcional</li>
                    </ul>
                </div>
                <div>
                    <p><strong>Niveles de Riesgo ARL:</strong></p>
                    <ul>
                        <li>Riesgo I: 0.522% (Administrativo)</li>
                        <li>Riesgo II: 1.044% (Comercio)</li>
                        <li>Riesgo III: 2.436% (Manufactura)</li>
                        <li>Riesgo IV: 4.350% (Industria pesada)</li>
                        <li>Riesgo V: 6.960% (Construcción/Minería)</li>
                    </ul>
                </div>
            </div>
            <p><em>Basado en PROM de Seguridad Social + PROM ARL integrados</em></p>
        </div>
    </div>
</body>
</html>