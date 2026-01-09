<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Cálculo ARL PROM'); ?></title>
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
            border-bottom: 2px solid #ffc107;
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
            background-color: #fff3cd;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #ffeaa7;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn {
            background-color: #ffc107;
            color: #212529;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #e0a800;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
        .results-section {
            margin-top: 30px;
        }
        .calculation-block {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .calculation-block h3 {
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }
        .calculation-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .calculation-row:last-child {
            border-bottom: none;
            font-weight: bold;
            background-color: #fff3cd;
            margin: 10px -20px -20px -20px;
            padding: 15px 20px;
        }
        .currency {
            font-family: monospace;
            font-weight: bold;
            color: #e67e22;
        }
        .nav-links {
            margin-bottom: 20px;
        }
        .nav-links a {
            margin-right: 15px;
        }
        .highlight-total {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
        }
        .risk-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .risk-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .risk-table th, .risk-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .risk-table th {
            background-color: #f8f9fa;
        }
        .risk-badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            margin-right: 5px;
        }
        .risk-1 { background-color: #28a745; }
        .risk-2 { background-color: #17a2b8; }
        .risk-3 { background-color: #ffc107; color: #212529; }
        .risk-4 { background-color: #fd7e14; }
        .risk-5 { background-color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏗️ <?php echo htmlspecialchars($title ?? 'Cálculo ARL (PROM Original)'); ?></h1>
        
        <div class="nav-links">
            <a href="/seguridad_social" class="btn btn-secondary">Seguridad Social</a>
            <a href="/seguridad_social/indexConARL" class="btn btn-secondary">Seguridad Social + ARL</a>
            <a href="/seguridad_social/gestionRiesgos" class="btn btn-secondary">Gestión Riesgos</a>
            <a href="/seguridad_social/calculoARLPuro" class="btn">ARL PROM</a>
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

        <!-- Información de niveles de riesgo -->
        <div class="risk-info">
            <h3>📋 Niveles de Riesgo ARL Disponibles</h3>
            <table class="risk-table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nivel</th>
                        <th>Porcentaje</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($niveles_riesgo as $codigo => $info): ?>
                        <tr>
                            <td><?php echo $codigo; ?></td>
                            <td>
                                <span class="risk-badge risk-<?php echo $codigo; ?>">
                                    Riesgo <?php echo $info['codigo']; ?>
                                </span>
                            </td>
                            <td><strong><?php echo $info['porcentaje']; ?>%</strong></td>
                            <td><?php echo $info['descripcion']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Formulario de entrada -->
        <div class="form-section">
            <h2>🧮 Calcular ARL según PROM Original</h2>
            <p><strong>Este cálculo replica exactamente el pseudocódigo (PROM) proporcionado para ARL.</strong></p>
            
            <form method="POST">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px;">
                    <div class="form-group">
                        <label for="salario_base">💰 Salario Base ($):</label>
                        <input type="number" 
                               name="salario_base" 
                               id="salario_base" 
                               step="0.01" 
                               min="0.01" 
                               required 
                               value="<?php echo isset($_POST['salario_base']) ? htmlspecialchars($_POST['salario_base']) : ''; ?>"
                               placeholder="Ej: 1500000">
                    </div>
                    
                    <div class="form-group">
                        <label for="dias_trabajados">📅 Días Trabajados:</label>
                        <input type="number" 
                               name="dias_trabajados" 
                               id="dias_trabajados" 
                               min="1" 
                               max="31" 
                               required 
                               value="<?php echo isset($_POST['dias_trabajados']) ? htmlspecialchars($_POST['dias_trabajados']) : '30'; ?>"
                               placeholder="Ej: 30">
                    </div>
                    
                    <div class="form-group">
                        <label for="codigo_riesgo">⚠️ Código de Riesgo (1-5):</label>
                        <select name="codigo_riesgo" id="codigo_riesgo" required>
                            <option value="">Seleccione un nivel de riesgo</option>
                            <?php foreach ($niveles_riesgo as $codigo => $info): ?>
                                <option value="<?php echo $codigo; ?>" 
                                        <?php echo (isset($_POST['codigo_riesgo']) && $_POST['codigo_riesgo'] == $codigo) ? 'selected' : ''; ?>>
                                    <?php echo $codigo; ?>. Riesgo <?php echo $info['codigo']; ?> (<?php echo $info['porcentaje']; ?>%) - <?php echo $info['descripcion']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <button type="submit" class="btn">🚀 Calcular ARL</button>
                </div>
            </form>
        </div>

        <?php if (isset($calculo_arl) && $calculo_arl): ?>
            <div class="results-section">
                <h2>📊 Resultados del Cálculo ARL</h2>
                
                <!-- Datos de entrada -->
                <div class="highlight-total">
                    <h3>🔍 Datos de Entrada</h3>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                        <div>
                            <strong>Salario Base:</strong><br>
                            <span class="currency" style="font-size: 18px;">$<?php echo number_format($calculo_arl['salario_base'], 2); ?></span>
                        </div>
                        <div>
                            <strong>Días Trabajados:</strong><br>
                            <span style="font-size: 18px; font-weight: bold;"><?php echo $calculo_arl['dias_trabajados']; ?> días</span>
                        </div>
                        <div>
                            <strong>Nivel de Riesgo:</strong><br>
                            <span class="risk-badge risk-<?php echo $calculo_arl['codigo_riesgo']; ?>">
                                Riesgo <?php echo $calculo_arl['nivel_riesgo']['codigo']; ?>
                            </span>
                            <span style="font-size: 14px; display: block; margin-top: 5px;">
                                <?php echo $calculo_arl['nivel_riesgo']['descripcion']; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Cálculos paso a paso -->
                <div class="calculation-block">
                    <h3>🧮 Proceso de Cálculo (Según PROM)</h3>
                    
                    <div class="calculation-row">
                        <span><strong>Paso 1:</strong> Cálculo del Salario Proporcional</span>
                        <span></span>
                    </div>
                    <div class="calculation-row">
                        <span style="margin-left: 20px;">
                            Fórmula: (Salario Base × Días Trabajados) ÷ 30
                        </span>
                        <span></span>
                    </div>
                    <div class="calculation-row">
                        <span style="margin-left: 20px;">
                            ($<?php echo number_format($calculo_arl['salario_base'], 2); ?> × <?php echo $calculo_arl['dias_trabajados']; ?>) ÷ 30
                        </span>
                        <span class="currency">$<?php echo number_format($calculo_arl['salario_proporcional'], 2); ?></span>
                    </div>
                    
                    <div class="calculation-row">
                        <span><strong>Paso 2:</strong> Aplicar Porcentaje de Riesgo</span>
                        <span></span>
                    </div>
                    <div class="calculation-row">
                        <span style="margin-left: 20px;">
                            Fórmula: Salario Proporcional × (<?php echo $calculo_arl['porcentaje_arl']; ?>% ÷ 100)
                        </span>
                        <span></span>
                    </div>
                    <div class="calculation-row">
                        <span style="margin-left: 20px;">
                            $<?php echo number_format($calculo_arl['salario_proporcional'], 2); ?> × <?php echo ($calculo_arl['porcentaje_arl'] / 100); ?>
                        </span>
                        <span class="currency">$<?php echo number_format($calculo_arl['aporte_arl'], 2); ?></span>
                    </div>
                    
                    <div class="calculation-row">
                        <span><strong>🎯 RESULTADO FINAL - APORTE ARL</strong></span>
                        <span class="currency" style="font-size: 20px;">
                            <strong>$<?php echo number_format($calculo_arl['aporte_arl'], 2); ?></strong>
                        </span>
                    </div>
                </div>

                <!-- Resumen final -->
                <div class="highlight-total">
                    <h3>📋 Resumen del Cálculo ARL</h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                        <div>
                            <h4>Valores Base:</h4>
                            <ul>
                                <li><strong>Salario Base:</strong> $<?php echo number_format($calculo_arl['salario_base'], 2); ?></li>
                                <li><strong>Días Trabajados:</strong> <?php echo $calculo_arl['dias_trabajados']; ?> días</li>
                                <li><strong>Salario Proporcional:</strong> $<?php echo number_format($calculo_arl['salario_proporcional'], 2); ?></li>
                            </ul>
                        </div>
                        <div>
                            <h4>Riesgo y Resultado:</h4>
                            <ul>
                                <li><strong>Nivel de Riesgo:</strong> <?php echo $calculo_arl['nivel_riesgo']['codigo']; ?> (<?php echo $calculo_arl['porcentaje_arl']; ?>%)</li>
                                <li><strong>Descripción:</strong> <?php echo $calculo_arl['nivel_riesgo']['descripcion']; ?></li>
                                <li style="font-size: 16px; color: #e67e22;"><strong>Aporte ARL:</strong> $<?php echo number_format($calculo_arl['aporte_arl'], 2); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div>
                    <p><strong>Información del PROM ARL:</strong></p>
                    <ul>
                        <li>Basado en el pseudocódigo proporcionado</li>
                        <li>Cálculo del salario proporcional según días trabajados</li>
                        <li>Aplicación de porcentajes según nivel de riesgo</li>
                        <li>Validaciones incluidas según PROM original</li>
                    </ul>
                </div>
                <div>
                    <p><strong>Constantes de Riesgo (PROM):</strong></p>
                    <ul>
                        <li>RIESGO_I = 0.522%</li>
                        <li>RIESGO_II = 1.044%</li>
                        <li>RIESGO_III = 2.436%</li>
                        <li>RIESGO_IV = 4.350%</li>
                        <li>RIESGO_V = 6.960%</li>
                    </ul>
                </div>
            </div>
            <p><em>Implementación exacta del PROM - Cálculo de Aporte a la ARL</em></p>
        </div>
    </div>
</body>
</html>