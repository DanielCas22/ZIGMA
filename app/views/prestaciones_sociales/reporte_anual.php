<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Reporte Anual Prestaciones Sociales') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .currency { font-family: 'Courier New', monospace; font-weight: bold; }
        .reporte-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .card-reporte {
            border-left: 4px solid #007bff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .tabla-reporte {
            font-size: 0.9rem;
        }
        .total-row {
            background: #f8f9fa;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .prestacion-badge {
            font-size: 0.8rem;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Header del Reporte -->
    <div class="reporte-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-0">
                        <i class="fas fa-chart-bar me-3"></i>
                        Reporte Anual de Prestaciones Sociales <?= $anio ?? date('Y') ?>
                    </h1>
                    <p class="mb-0 mt-2 opacity-90">
                        Consolidado empresarial de prestaciones sociales
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-light text-dark fs-6">
                        <i class="fas fa-calendar me-2"></i><?= $fecha_reporte ?? date('d/m/Y H:i:s') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($calculo_anual) && $calculo_anual): ?>
        
        <!-- Resumen Ejecutivo -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card card-reporte text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x text-primary mb-3"></i>
                        <h3><?= number_format($calculo_anual['total_empleados'] ?? 0) ?></h3>
                        <p class="text-muted mb-0">Empleados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-reporte text-center">
                    <div class="card-body">
                        <i class="fas fa-piggy-bank fa-2x text-info mb-3"></i>
                        <h5 class="currency text-info">$<?= number_format($calculo_anual['totales_empresa']['cesantias'] ?? 0) ?></h5>
                        <p class="text-muted mb-0">Total Cesantías</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-reporte text-center">
                    <div class="card-body">
                        <i class="fas fa-percent fa-2x text-warning mb-3"></i>
                        <h5 class="currency text-warning">$<?= number_format($calculo_anual['totales_empresa']['intereses'] ?? 0) ?></h5>
                        <p class="text-muted mb-0">Total Intereses</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-reporte text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-2x text-success mb-3"></i>
                        <h4 class="currency text-success">$<?= number_format($calculo_anual['totales_empresa']['total_general'] ?? 0) ?></h4>
                        <p class="text-muted mb-0"><strong>Total General</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla Detallada por Empleado -->
        <div class="card card-reporte mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    Detalle por Empleado - Año <?= $anio ?? date('Y') ?>
                </h4>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($calculo_anual['empleados'])): ?>
                <div class="table-responsive">
                    <table class="table table-hover tabla-reporte mb-0">
                        <thead class="table-primary">
                            <tr>
                                <th>Empleado</th>
                                <th class="text-end">Cesantías</th>
                                <th class="text-end">Intereses</th>
                                <th class="text-end">Prima</th>
                                <th class="text-end">Vacaciones</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($calculo_anual['empleados'] as $empleado): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($empleado['empleado']['nombre'] . ' ' . $empleado['empleado']['apellido']) ?></strong>
                                    <br>
                                    <small class="text-muted">Total Devengado: $<?= number_format($empleado['empleado']['total_devengado'] ?? 0) ?></small>
                                </td>
                                <td class="text-end currency">
                                    $<?= number_format($empleado['prestaciones']['cesantias']['valor_cesantias'] ?? 0) ?>
                                </td>
                                <td class="text-end currency">
                                    $<?= number_format($empleado['prestaciones']['intereses_cesantias']['valor_intereses'] ?? 0) ?>
                                </td>
                                <td class="text-end currency">
                                    $<?= number_format($empleado['prestaciones']['prima_servicios']['valor_prima'] ?? 0) ?>
                                </td>
                                <td class="text-end currency">
                                    $<?= number_format($empleado['prestaciones']['vacaciones']['valor_vacaciones'] ?? 0) ?>
                                </td>
                                <td class="text-end currency">
                                    <strong class="text-success">$<?= number_format($empleado['resumen']['total_prestaciones'] ?? 0) ?></strong>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success prestacion-badge">Calculado</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        
                        <!-- Totales -->
                        <tfoot>
                            <tr class="total-row">
                                <td><strong>TOTALES ANUALES <?= $anio ?? date('Y') ?></strong></td>
                                <td class="text-end"><strong>$<?= number_format($calculo_anual['totales_empresa']['cesantias'] ?? 0) ?></strong></td>
                                <td class="text-end"><strong>$<?= number_format($calculo_anual['totales_empresa']['intereses'] ?? 0) ?></strong></td>
                                <td class="text-end"><strong>$<?= number_format($calculo_anual['totales_empresa']['prima'] ?? 0) ?></strong></td>
                                <td class="text-end"><strong>$<?= number_format($calculo_anual['totales_empresa']['vacaciones'] ?? 0) ?></strong></td>
                                <td class="text-end" style="background: #28a745; color: white;">
                                    <strong>$<?= number_format($calculo_anual['totales_empresa']['total_general'] ?? 0) ?></strong>
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-calculator text-success"></i>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay datos para el reporte anual</h5>
                    <p class="text-muted">Verifique que existan empleados con prestaciones calculadas</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Análisis y Promedios -->
        <div class="row">
            <div class="col-md-6">
                <div class="card card-reporte">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-pie me-2"></i>
                            Análisis de Promedios
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($calculo_anual['promedios'])): ?>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <strong>Promedio Cesantías:</strong> 
                                <span class="currency text-info">$<?= number_format($calculo_anual['promedios']['cesantias'] ?? 0) ?></span>
                            </li>
                            <li class="mb-2">
                                <strong>Promedio Intereses:</strong> 
                                <span class="currency text-warning">$<?= number_format($calculo_anual['promedios']['intereses'] ?? 0) ?></span>
                            </li>
                            <li class="mb-2">
                                <strong>Promedio Prima:</strong> 
                                <span class="currency text-success">$<?= number_format($calculo_anual['promedios']['prima'] ?? 0) ?></span>
                            </li>
                            <li class="mb-2">
                                <strong>Promedio Vacaciones:</strong> 
                                <span class="currency text-primary">$<?= number_format($calculo_anual['promedios']['vacaciones'] ?? 0) ?></span>
                            </li>
                            <li class="mb-0">
                                <strong>Promedio Total:</strong> 
                                <span class="currency text-danger">$<?= number_format($calculo_anual['promedios']['total_general'] ?? 0) ?></span>
                            </li>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card card-reporte">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Información del Reporte
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li><strong>Año del Reporte:</strong> <?= $anio ?? date('Y') ?></li>
                            <li><strong>Fecha de Generación:</strong> <?= $fecha_reporte ?? date('d/m/Y H:i:s') ?></li>
                            <li><strong>Total Empleados:</strong> <?= number_format($calculo_anual['total_empleados'] ?? 0) ?></li>
                            <li><strong>Tipo de Cálculo:</strong> Mensual (basado en total devengado)</li>
                            <li><strong>Fórmulas Aplicadas:</strong></li>
                            <ul class="mt-2">
                                <li><small>Cesantías: Total Devengado × 8.33%</small></li>
                                <li><small>Intereses: Total Devengado × 1%</small></li>
                                <li><small>Prima: Total Devengado × 8.33%</small></li>
                                <li><small>Vacaciones: (Total Devengado - Auxilio) × 4.17%</small></li>
                            </ul>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
            <h5 class="text-muted">No se pudo generar el reporte anual</h5>
            <p class="text-muted">Verifique que existan empleados con datos válidos</p>
            <a href="?url=PrestacionesSociales" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>Volver a Prestaciones Sociales
            </a>
        </div>
        <?php endif; ?>

        <!-- Botones de Acción -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="?url=PrestacionesSociales" class="btn btn-secondary me-3">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Prestaciones
                </a>
                <button onclick="window.print()" class="btn btn-primary me-3">
                    <i class="fas fa-print me-2"></i>Imprimir Reporte
                </button>
                <button onclick="exportarExcel()" class="btn btn-success">
                    <i class="fas fa-file-excel me-2"></i>Exportar Excel
                </button>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function exportarExcel() {
            // Funcionalidad para exportar a Excel (por implementar)
            alert('Funcionalidad de exportar a Excel en desarrollo');
        }
    </script>
    
</body>
</html>