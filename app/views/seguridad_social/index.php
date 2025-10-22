<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Seguridad Social'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
    <style>
        @media (max-width: 576px) {
            .table-responsive {
                width: 100vw !important;
                max-width: 100vw !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            .table, .table th, .table td {
                min-width: 120px !important;
                font-size: clamp(11px, 3vw, 13px) !important;
                padding: 6px !important;
                white-space: nowrap !important;
            }
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

<!-- Navbar -->
<?php $pageTitle = "Seguridad Social"; ?>
<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-4 fade-in-up">
    <div class="page-header-zigma mb-4">
        <h2><i class="fas fa-shield-alt me-2"></i><?php echo htmlspecialchars($title ?? 'Cálculo de Seguridad Social + ARL'); ?></h2>
    </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-zigma-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-zigma-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Formulario para actualizar días trabajados -->
        <div class="card-zigma mb-4">
            <div class="card-header-zigma">
                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Configuración de Días Trabajados</h5>
            </div>
            <div class="card-body">
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
            <div class="table-responsive">
                <table class="table">
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
                                        
                                        // Mostrar badges diferenciados por color
                                        foreach ($rolesFormateados as $rol) {
                                            $rol_lower = strtolower($rol);
                                            $badge_class = 'badge-role-default';
                                            if (strpos($rol_lower, 'admin') !== false) {
                                                $badge_class = 'badge-role-admin';
                                            } elseif (strpos($rol_lower, 'rrhh') !== false || strpos($rol_lower, 'recursos humanos') !== false) {
                                                $badge_class = 'badge-role-rrhh';
                                            } elseif (strpos($rol_lower, 'empleado') !== false) {
                                                $badge_class = 'badge-role-empleado';
                                            }
                                            echo '<span class="badge ' . $badge_class . '">' . htmlspecialchars($rol) . '</span> ';
                                        }
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
                                    <?php 
                                        $porcentaje = 0;
                                        if (isset($calculo['arl']['nivel_riesgo']['porcentaje'])) {
                                            $porcentaje = $calculo['arl']['nivel_riesgo']['porcentaje'];
                                        } elseif (isset($calculo['arl']['porcentaje_arl'])) {
                                            $porcentaje = $calculo['arl']['porcentaje_arl'];
                                        }
                                        echo number_format($porcentaje, 3);
                                    ?>%
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
            </div>

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