<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Cálculo Completo de Seguridad Social'); ?></title>
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
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
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
            background-color: #e9f7ff;
            margin: 10px -20px -20px -20px;
            padding: 15px 20px;
        }
        .currency {
            font-family: monospace;
            font-weight: bold;
            color: #28a745;
        }
        .nav-links {
            margin-bottom: 20px;
        }
        .nav-links a {
            margin-right: 15px;
        }
        .highlight-total {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($title ?? 'Cálculo Completo de Seguridad Social'); ?></h1>
        
        <div class="nav-links">
            <a href="/seguridad_social" class="btn btn-secondary">Vista Principal</a>
            <a href="/seguridad_social/completo" class="btn">Cálculo Completo</a>
            <a href="/seguridad_social/configuracion" class="btn btn-secondary">Configuración</a>
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

        <!-- Formulario de entrada -->
        <div class="form-section">
            <h2>Calcular Seguridad Social Completa</h2>
            <p>Este cálculo incluye tanto las deducciones del empleado como los aportes del empleador.</p>
            
            <form method="POST">
                <div class="form-group">
                    <label for="total_devengado">Total Devengado ($):</label>
                    <input type="number" 
                           name="total_devengado" 
                           id="total_devengado" 
                           step="0.01" 
                           min="0.01" 
                           required 
                           placeholder="Ingrese el total devengado">
                </div>
                <button type="submit" class="btn">Calcular Seguridad Social Completa</button>
            </form>
        </div>

        <?php if (isset($calculo_completo) && $calculo_completo): ?>
            <div class="results-section">
                <h2>Resultados del Cálculo</h2>
                
                <!-- Total Devengado -->
                <div class="highlight-total">
                    <h3>Total Devengado: <span class="currency">$<?php echo number_format($calculo_completo['total_devengado'], 2); ?></span></h3>
                </div>

                <!-- Deducciones del Empleado -->
                <div class="calculation-block">
                    <h3>DEDUCCIONES DEL EMPLEADO (Seguridad Social)</h3>
                    
                    <div class="calculation-row">
                        <span>Salud (<?php echo $calculo_completo['deducciones_empleado']['salud']['porcentaje']; ?>%)</span>
                        <span class="currency">$<?php echo number_format($calculo_completo['deducciones_empleado']['salud']['valor'], 2); ?></span>
                    </div>
                    
                    <div class="calculation-row">
                        <span>Pensión (<?php echo $calculo_completo['deducciones_empleado']['pension']['porcentaje']; ?>%)</span>
                        <span class="currency">$<?php echo number_format($calculo_completo['deducciones_empleado']['pension']['valor'], 2); ?></span>
                    </div>
                    
                    <div class="calculation-row">
                        <span><strong>Total Deducciones Empleado</strong></span>
                        <span class="currency"><strong>$<?php echo number_format($calculo_completo['deducciones_empleado']['total'], 2); ?></strong></span>
                    </div>
                </div>

                <!-- Aportes del Empleador -->
                <div class="calculation-block">
                    <h3>APORTES DEL EMPLEADOR (Seguridad Social)</h3>
                    
                    <div class="calculation-row">
                        <span>Salud (<?php echo $calculo_completo['aportes_empleador']['salud']['porcentaje']; ?>%)</span>
                        <span class="currency">$<?php echo number_format($calculo_completo['aportes_empleador']['salud']['valor'], 2); ?></span>
                    </div>
                    
                    <div class="calculation-row">
                        <span>Pensión (<?php echo $calculo_completo['aportes_empleador']['pension']['porcentaje']; ?>%)</span>
                        <span class="currency">$<?php echo number_format($calculo_completo['aportes_empleador']['pension']['valor'], 2); ?></span>
                    </div>
                    
                    <div class="calculation-row">
                        <span><strong>Total Aportes Empleador</strong></span>
                        <span class="currency"><strong>$<?php echo number_format($calculo_completo['aportes_empleador']['total'], 2); ?></strong></span>
                    </div>
                </div>

                <!-- Resumen Total -->
                <div class="highlight-total">
                    <h3>Resumen General</h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-top: 15px;">
                        <div>
                            <p><strong>Total que descuenta al empleado:</strong><br>
                            <span class="currency" style="font-size: 18px;">$<?php echo number_format($calculo_completo['deducciones_empleado']['total'], 2); ?></span></p>
                        </div>
                        <div>
                            <p><strong>Total que aporta el empleador:</strong><br>
                            <span class="currency" style="font-size: 18px;">$<?php echo number_format($calculo_completo['aportes_empleador']['total'], 2); ?></span></p>
                        </div>
                    </div>
                    <hr>
                    <p style="text-align: center; margin: 15px 0;">
                        <strong>Costo Total de Seguridad Social:</strong><br>
                        <span class="currency" style="font-size: 24px; color: #dc3545;">
                            $<?php echo number_format($calculo_completo['deducciones_empleado']['total'] + $calculo_completo['aportes_empleador']['total'], 2); ?>
                        </span>
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
            <p><strong>Porcentajes de Seguridad Social aplicados:</strong></p>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div>
                    <h4>Empleado (Deducciones):</h4>
                    <ul>
                        <li>Salud: 4.0%</li>
                        <li>Pensión: 4.0%</li>
                        <li><strong>Total: 8.0%</strong></li>
                    </ul>
                </div>
                <div>
                    <h4>Empleador (Aportes):</h4>
                    <ul>
                        <li>Salud: 8.5%</li>
                        <li>Pensión: 12.0%</li>
                        <li><strong>Total: 20.5%</strong></li>
                    </ul>
                </div>
            </div>
            <p><em>Cálculo basado en el PROM de Seguridad Social Completa - No incluye parafiscales</em></p>
        </div>
    </div>
</body>
</html>