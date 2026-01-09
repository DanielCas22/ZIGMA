<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Configuración de Seguridad Social'); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 900px;
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
        .config-section {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .config-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .config-item {
            background-color: white;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
        }
        .config-item h3 {
            margin-top: 0;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }
        .percentage-display {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            text-align: center;
            margin: 10px 0;
        }
        .info-box {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .warning-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .formula-section {
            background-color: #e9f7ff;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 15px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($title ?? 'Configuración de Seguridad Social'); ?></h1>
        
        <div class="nav-links">
            <a href="/seguridad_social" class="btn btn-secondary">Vista Principal</a>
            <a href="/seguridad_social/completo" class="btn btn-secondary">Cálculo Completo</a>
            <a href="/seguridad_social/configuracion" class="btn">Configuración</a>
        </div>

        <div class="info-box">
            <h2>📋 Información General</h2>
            <p>Esta página muestra los porcentajes y configuraciones utilizadas para el cálculo de seguridad social según el PROM implementado.</p>
            <p><strong>Los porcentajes son fijos según la legislación colombiana vigente y no pueden ser modificados desde la aplicación.</strong></p>
        </div>

        <!-- Porcentajes Actuales -->
        <div class="config-section">
            <h2>Porcentajes de Seguridad Social Configurados</h2>
            
            <div class="config-grid">
                <!-- Empleado -->
                <div class="config-item">
                    <h3>👤 DEDUCCIONES DEL EMPLEADO</h3>
                    <div style="text-align: center;">
                        <div>
                            <strong>Salud:</strong>
                            <div class="percentage-display"><?php echo $porcentajes['salud_empleado']; ?>%</div>
                        </div>
                        <div style="margin-top: 15px;">
                            <strong>Pensión:</strong>
                            <div class="percentage-display"><?php echo $porcentajes['pension_empleado']; ?>%</div>
                        </div>
                        <hr>
                        <div>
                            <strong>Total Empleado:</strong>
                            <div class="percentage-display" style="color: #dc3545;">
                                <?php echo ($porcentajes['salud_empleado'] + $porcentajes['pension_empleado']); ?>%
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empleador -->
                <div class="config-item">
                    <h3>🏢 APORTES DEL EMPLEADOR</h3>
                    <div style="text-align: center;">
                        <div>
                            <strong>Salud:</strong>
                            <div class="percentage-display"><?php echo $porcentajes['salud_empleador']; ?>%</div>
                        </div>
                        <div style="margin-top: 15px;">
                            <strong>Pensión:</strong>
                            <div class="percentage-display"><?php echo $porcentajes['pension_empleador']; ?>%</div>
                        </div>
                        <hr>
                        <div>
                            <strong>Total Empleador:</strong>
                            <div class="percentage-display" style="color: #28a745;">
                                <?php echo ($porcentajes['salud_empleador'] + $porcentajes['pension_empleador']); ?>%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fórmulas de Cálculo -->
        <div class="config-section">
            <h2>📐 Fórmulas de Cálculo Implementadas</h2>
            
            <div class="formula-section">
                <h3>Cálculo Básico por Empleado</h3>
                <ul>
                    <li><strong>Salario Proporcional:</strong> (Salario Base × Días Trabajados) ÷ 30</li>
                    <li><strong>Aporte Salud:</strong> Salario Proporcional × <?php echo $porcentajes['salud_empleado']; ?>%</li>
                    <li><strong>Aporte Pensión:</strong> Salario Proporcional × <?php echo $porcentajes['pension_empleado']; ?>%</li>
                    <li><strong>Total Seguridad Social:</strong> Aporte Salud + Aporte Pensión</li>
                </ul>
            </div>

            <div class="formula-section">
                <h3>Cálculo Completo (Empleado + Empleador)</h3>
                <ul>
                    <li><strong>Salud Empleado:</strong> Total Devengado × <?php echo $porcentajes['salud_empleado']; ?>%</li>
                    <li><strong>Pensión Empleado:</strong> Total Devengado × <?php echo $porcentajes['pension_empleado']; ?>%</li>
                    <li><strong>Salud Empleador:</strong> Total Devengado × <?php echo $porcentajes['salud_empleador']; ?>%</li>
                    <li><strong>Pensión Empleador:</strong> Total Devengado × <?php echo $porcentajes['pension_empleador']; ?>%</li>
                </ul>
            </div>
        </div>

        <!-- Tabla Resumen -->
        <div class="config-section">
            <h2>📊 Tabla Resumen de Porcentajes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th class="text-center">Empleado (%)</th>
                        <th class="text-center">Empleador (%)</th>
                        <th class="text-center">Total (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Salud</strong></td>
                        <td class="text-center"><?php echo $porcentajes['salud_empleado']; ?>%</td>
                        <td class="text-center"><?php echo $porcentajes['salud_empleador']; ?>%</td>
                        <td class="text-center"><strong><?php echo ($porcentajes['salud_empleado'] + $porcentajes['salud_empleador']); ?>%</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Pensión</strong></td>
                        <td class="text-center"><?php echo $porcentajes['pension_empleado']; ?>%</td>
                        <td class="text-center"><?php echo $porcentajes['pension_empleador']; ?>%</td>
                        <td class="text-center"><strong><?php echo ($porcentajes['pension_empleado'] + $porcentajes['pension_empleador']); ?>%</strong></td>
                    </tr>
                    <tr style="background-color: #f8f9fa; font-weight: bold;">
                        <td><strong>TOTAL SEGURIDAD SOCIAL</strong></td>
                        <td class="text-center"><strong><?php echo ($porcentajes['salud_empleado'] + $porcentajes['pension_empleado']); ?>%</strong></td>
                        <td class="text-center"><strong><?php echo ($porcentajes['salud_empleador'] + $porcentajes['pension_empleador']); ?>%</strong></td>
                        <td class="text-center" style="color: #dc3545;">
                            <strong><?php echo ($porcentajes['salud_empleado'] + $porcentajes['pension_empleado'] + $porcentajes['salud_empleador'] + $porcentajes['pension_empleador']); ?>%</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Información Adicional -->
        <div class="warning-box">
            <h2>⚠️ Notas Importantes</h2>
            <ul>
                <li><strong>Legislación:</strong> Los porcentajes están definidos según la normativa colombiana de seguridad social</li>
                <li><strong>Alcance:</strong> Este cálculo incluye únicamente seguridad social (salud y pensión)</li>
                <li><strong>Exclusiones:</strong> NO incluye parafiscales (SENA, ICBF, Cajas de Compensación)</li>
                <li><strong>Base de cálculo:</strong> Los cálculos se realizan sobre el salario base o total devengado según corresponda</li>
                <li><strong>Días trabajados:</strong> Para cálculos proporcionales se toma base de 30 días</li>
            </ul>
        </div>

        <!-- PROM Referencias -->
        <div class="info-box">
            <h2>📄 Referencias del PROM</h2>
            <p>La implementación está basada en los pseudocódigos (PROM) proporcionados:</p>
            <ul>
                <li><strong>PROM 1:</strong> Cálculo de Seguridad Social Completa (empleado + empleador)</li>
                <li><strong>PROM 2:</strong> Cálculo de Seguridad Social Básica (solo empleado con días proporcionales)</li>
            </ul>
            <p><strong>Implementación en:</strong></p>
            <ul>
                <li>Modelo: <code>app/models/SeguridadSocialModel.php</code></li>
                <li>Controlador: <code>app/controllers/SeguridadSocialController.php</code></li>
                <li>Vistas: <code>app/views/seguridad_social/</code></li>
            </ul>
        </div>
    </div>
</body>
</html>