<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Prestaciones Sociales' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
    <style>
        .currency {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
        .formula-text {
            font-family: 'Courier New', monospace;
            font-size: 0.85em;
            background-color: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
            color: #495057;
        }
        .prestacion-badge {
            font-size: 0.75em;
            padding: 0.25em 0.5em;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?php $pageTitle = "Prestaciones Sociales"; ?>
<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container-fluid py-4 fade-in-up">
    <div class="page-header-zigma mb-4 text-center">
        <h2><i class="fas fa-gift me-2"></i>Prestaciones Sociales</h2>
        <p class="mb-0">Gestión de Cesantías, Intereses, Prima y Vacaciones</p>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="text-primary">
                        <i class="fas fa-gift me-3"></i>Prestaciones Sociales
                        <span class="badge bg-success">NUEVA VERSIÓN CON CONCEPTOS ADICIONALES</span>
                    </h1>
                    <p class="text-muted mb-0">Cesantías, Intereses, Prima y Vacaciones + Conceptos Adicionales</p>
                </div>
                <div class="btn-group">
                    <a href="/ZIGMA/public/index.php?url=Dashboard/index" class="btn btn-primary">
                        <i class="fas fa-home"></i> Volver al Inicio
                    </a>
                </div>
            </div>
            
            <!-- Filtro de días trabajados -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="get" class="row align-items-end">
                        <input type="hidden" name="url" value="PrestacionesSociales">
                        <div class="col-md-3">
                            <label class="form-label">Días Trabajados en el Año</label>
                            <input type="number" name="dias_trabajados" class="form-control" 
                                   value="<?= $dias_trabajados ?? 360 ?>" min="1" max="360">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sync"></i> Recalcular
                            </button>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Los cálculos se basan en <?= $dias_trabajados ?? 360 ?> días trabajados.
                                360 días = año completo
                            </small>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Resumen Total -->
            <?php if (!empty($totales_empresa)): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card totales-card text-white">
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-2">
                                        <h3><i class="fas fa-users"></i></h3>
                                        <h4><?= $total_empleados ?></h4>
                                        <small>Empleados</small>
                                    </div>
                                    <div class="col-md-2">
                                        <h3><i class="fas fa-piggy-bank"></i></h3>
                                        <h4 class="currency">$<?= number_format($totales_empresa['cesantias'], 0) ?></h4>
                                        <small>Total Cesantías</small>
                                    </div>
                                    <div class="col-md-2">
                                        <h3><i class="fas fa-percentage"></i></h3>
                                        <h4 class="currency">$<?= number_format($totales_empresa['intereses'], 0) ?></h4>
                                        <small>Total Intereses</small>
                                    </div>
                                    <div class="col-md-2">
                                        <h3><i class="fas fa-star"></i></h3>
                                        <h4 class="currency">$<?= number_format($totales_empresa['prima'], 0) ?></h4>
                                        <small>Total Prima</small>
                                    </div>
                                    <div class="col-md-2">
                                        <h3><i class="fas fa-umbrella-beach"></i></h3>
                                        <h4 class="currency">$<?= number_format($totales_empresa['vacaciones'], 0) ?></h4>
                                        <small>Total Vacaciones</small>
                                    </div>
                                    <div class="col-md-2">
                                        <h3><i class="fas fa-calculator"></i></h3>
                                        <h4 class="currency">$<?= number_format($totales_empresa['total_general'], 0) ?></h4>
                                        <small>TOTAL GENERAL</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tabla de empleados -->
            <?php if (!empty($calculos_empleados)): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-table me-2"></i>Prestaciones Sociales por Empleado
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Salario</th>
                                        <th>Aux. Trans.</th>
                                        <th>Días</th>
                                        <th>Cesantías</th>
                                        <th>Intereses</th>
                                        <th>Prima</th>
                                        <th>Vacaciones</th>
                                        <th>Total</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($calculos_empleados as $calculo): ?>
                                        <?php if (in_array($calculo['empleado']['id'], [1,2,3])) continue; ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($calculo['empleado']['nombre'] . ' ' . $calculo['empleado']['apellido']) ?></strong>
                                                <br>
                                                <small class="text-muted">ID: <?= $calculo['empleado']['id'] ?></small>
                                            </td>
                                            <td class="currency">$<?= number_format($calculo['empleado']['salario_mensual'], 0) ?></td>
                                            <td class="currency">
                                                <?php if ($calculo['empleado']['auxilio_transporte'] > 0): ?>
                                                    <span class="badge bg-success prestacion-badge">
                                                        $<?= number_format($calculo['empleado']['auxilio_transporte'], 0) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary prestacion-badge">No aplica</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $calculo['parametros']['dias_trabajados'] ?></td>
                                            <td class="currency text-info">
                                                $<?= number_format($calculo['prestaciones']['cesantias']['valor_cesantias'], 0) ?>
                                            </td>
                                            <td class="currency text-warning">
                                                $<?= number_format($calculo['prestaciones']['intereses_cesantias']['valor_intereses'], 0) ?>
                                            </td>
                                            <td class="currency text-success">
                                                $<?= number_format($calculo['prestaciones']['prima_servicios']['valor_prima'], 0) ?>
                                            </td>
                                            <td class="currency text-primary">
                                                $<?= number_format($calculo['prestaciones']['vacaciones']['valor_vacaciones'], 0) ?>
                                            </td>
                                            <td class="currency">
                                                <strong class="text-danger">
                                                    $<?= number_format($calculo['resumen']['total_prestaciones'], 0) ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <a href="?url=PrestacionesSociales/detalle/<?= $calculo['empleado']['id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="Ver detalle">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-secondary">
                                    <tr>
                                        <th colspan="4">TOTALES</th>
                                        <th class="currency text-info">$<?= number_format($totales_empresa['cesantias'] ?? 0, 0) ?></th>
                                        <th class="currency text-warning">$<?= number_format($totales_empresa['intereses'] ?? 0, 0) ?></th>
                                        <th class="currency text-success">$<?= number_format($totales_empresa['prima'] ?? 0, 0) ?></th>
                                        <th class="currency text-primary">$<?= number_format($totales_empresa['vacaciones'] ?? 0, 0) ?></th>
                                        <th class="currency">
                                            <strong class="text-danger">$<?= number_format($totales_empresa['total_general'] ?? 0, 0) ?></strong>
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>No hay empleados para calcular</h4>
                    <p>Agregue empleados al sistema para poder calcular las prestaciones sociales.</p>
                    <a href="/ZIGMA/public/index.php?url=Empleado/create" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Agregar Empleado
                    </a>
                </div>
            <?php endif; ?>

            <!-- Información adicional -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0"><i class="fas fa-calculator"></i> Fórmulas Aplicadas</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li><strong>Cesantías:</strong> <span class="formula-text">(Salario + Aux. Trans.) ÷ 12</span></li>
                                <li><strong>Intereses:</strong> <span class="formula-text">Cesantías Acumuladas × 1% mensual</span></li>
                                <li><strong>Prima:</strong> <span class="formula-text">(Salario + Aux. Trans.) ÷ 12</span></li>
                                <li><strong>Vacaciones:</strong> <span class="formula-text">Salario ÷ 24 (sin auxilio)</span></li>
                            </ul>
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-calendar-alt"></i> <strong>Cálculo Mensual</strong> - Nómina con cortes mensuales
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-info"></i> Información Legal</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li><i class="fas fa-check text-success"></i> Cálculos basados en legislación colombiana 2025</li>
                                <li><i class="fas fa-check text-success"></i> Salario mínimo: $<?= number_format(1423000, 0) ?></li>
                                <li><i class="fas fa-check text-success"></i> Auxilio de transporte: $<?= number_format(200000, 0) ?></li>
                                <li><i class="fas fa-check text-success"></i> Interés cesantías: 12% anual (1% mensual)</li>
                                <li><i class="fas fa-calendar-alt text-primary"></i> <strong>Nómina mensual</strong> - Cortes cada 30 días</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>