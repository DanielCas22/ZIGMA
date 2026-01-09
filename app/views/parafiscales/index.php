<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parafiscales - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<?php $pageTitle = "Parafiscales"; ?>
<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4 fade-in-up">
        <div class="col-12">
            <div class="page-header-zigma">
                <h2 class="mb-1">
                    <i class="fas fa-building text-zigma-success me-2"></i>
                    Parafiscales - Sistema Nómina
                </h2>
                <p class="text-muted mb-0">Cálculo de aportes parafiscales (SENA, ICBF, Caja de Compensación)</p>
            </div>
        </div>
    </div>

    <!-- Información Legal -->
    <div class="alert-zigma-info mb-4">
        <div class="row align-items-center">
            <div class="col-md-10">
                <h5><i class="fas fa-info-circle me-2"></i>Información Legal</h5>
                <p class="mb-0">
                    Los parafiscales son aportes <strong>exclusivos del empleador</strong> (no se descuentan del trabajador).
                    Base de cálculo: Total devengado mensual. <strong>Total: 9%</strong> (SENA 2% + ICBF 3% + Caja 4%)
                </p>
            </div>
        </div>
    </div>

    <!-- Tarjetas de Resumen General -->
    <div class="row mb-4 fade-in-up">
            <div class="col-md-3">
                <div class="card card-sena h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                        <h5>SENA (2%)</h5>
                        <h4>$<?= number_format($totales_empresa['sena'] ?? 0, 0, ',', '.') ?></h4>
                        <small>Capacitación Laboral</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-icbf h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-child fa-2x mb-2"></i>
                        <h5>ICBF (3%)</h5>
                        <h4>$<?= number_format($totales_empresa['icbf'] ?? 0, 0, ',', '.') ?></h4>
                        <small>Bienestar Familiar</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-caja h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h5>Caja Compensación (4%)</h5>
                        <h4>$<?= number_format($totales_empresa['caja_compensacion'] ?? 0, 0, ',', '.') ?></h4>
                        <small>Subsidio Familiar</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-total h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-calculator fa-2x mb-2"></i>
                        <h5>Total Parafiscales</h5>
                        <h4>$<?= number_format($totales_empresa['total'] ?? 0, 0, ',', '.') ?></h4>
                        <small>(<?= $total_empleados ?> empleados)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <?php if (!empty($estadisticas)): ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card-zigma shadow">
                    <div class="card-body">
                        <h5 class="mb-3 text-zigma-navy">
                            <i class="fas fa-chart-bar me-2"></i>
                            Estadísticas de la Empresa
                        </h5>
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <h6>Empleados Procesados</h6>
                                <h4 class="text-zigma-primary"><?= $estadisticas['empleados_procesados'] ?></h4>
                            </div>
                            <div class="col-md-3 text-center">
                                <h6>Costo Total Empresa</h6>
                                <h4 class="text-zigma-success">$<?= number_format($estadisticas['costo_total_empresa'], 0, ',', '.') ?></h4>
                            </div>
                            <div class="col-md-3 text-center">
                                <h6>% sobre Nómina</h6>
                                <h4 class="text-zigma-secondary"><?= number_format($estadisticas['porcentaje_sobre_nomina'], 2) ?>%</h4>
                            </div>
                            <div class="col-md-3 text-center">
                                <h6>Promedio por Empleado</h6>
                                <h4 class="text-zigma-info">$<?= number_format($promedios['total_por_empleado'] ?? 0, 0, ',', '.') ?></h4>
                            </div>
                        </div>
                        
                        <!-- Distribución porcentual -->
                        <hr>
                        <h6>Distribución de Parafiscales</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <label>SENA</label>
                                <div class="progress progress-custom">
                                    <div class="progress-bar bg-success" style="width: <?= $estadisticas['distribucion_porcentual']['sena'] ?>%"></div>
                                </div>
                                <small><?= number_format($estadisticas['distribucion_porcentual']['sena'], 1) ?>%</small>
                            </div>
                            <div class="col-md-4">
                                <label>ICBF</label>
                                <div class="progress progress-custom">
                                    <div class="progress-bar bg-primary" style="width: <?= $estadisticas['distribucion_porcentual']['icbf'] ?>%"></div>
                                </div>
                                <small><?= number_format($estadisticas['distribucion_porcentual']['icbf'], 1) ?>%</small>
                            </div>
                            <div class="col-md-4">
                                <label>Caja Compensación</label>
                                <div class="progress progress-custom">
                                    <div class="progress-bar bg-warning" style="width: <?= $estadisticas['distribucion_porcentual']['caja_compensacion'] ?>%"></div>
                                </div>
                                <small><?= number_format($estadisticas['distribucion_porcentual']['caja_compensacion'], 1) ?>%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tabla de Empleados -->
        <div class="row">
            <div class="col-12">
                <div class="card-zigma shadow">
                    <div class="card-body">
                        <h5 class="mb-3 text-zigma-navy">
                            <i class="fas fa-table me-2"></i>
                            Parafiscales por Empleado
                        </h5>
                        <div class="table-responsive">
                            <table class="table-zigma">
                                <thead>
                                    <tr>
                                        <th>Empleado</th>
                                        <th class="text-end">Base Devengado</th>
                                        <th class="text-end">SENA (2%)</th>
                                        <th class="text-end">ICBF (3%)</th>
                                        <th class="text-end">Caja (4%)</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($calculos_empleados)): ?>
                                        <?php foreach ($calculos_empleados as $calculo): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($calculo['empleado']['nombre'] . ' ' . $calculo['empleado']['apellido']) ?></strong>
                                            </td>
                                            <td class="text-end">
                                                $<?= number_format($calculo['base_calculo']['total_devengado'], 0, ',', '.') ?>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge-zigma-success">
                                                    $<?= number_format($calculo['parafiscales']['sena']['valor'], 0, ',', '.') ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge-zigma-primary">
                                                    $<?= number_format($calculo['parafiscales']['icbf']['valor'], 0, ',', '.') ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-warning">
                                                    $<?= number_format($calculo['parafiscales']['caja_compensacion']['valor'], 0, ',', '.') ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <strong>$<?= number_format($calculo['resumen']['total_parafiscales'], 0, ',', '.') ?></strong>
                                            </td>
                                            <td class="text-center">
                                                <a href="/ZIGMA/Parafiscales/detalle/<?= $calculo['empleado']['id'] ?>" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   data-bs-toggle="tooltip" 
                                                   title="Ver detalle">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                <i class="fas fa-info-circle me-2"></i>
                                                No hay empleados para procesar
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inicializar tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>