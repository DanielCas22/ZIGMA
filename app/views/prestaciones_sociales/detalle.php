<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Detalle de Prestaciones Sociales' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .currency { font-family: 'Courier New', monospace; font-weight: bold; }
        .calculation-card { transition: transform 0.2s; }
        .calculation-card:hover { transform: translateY(-3px); }
        .formula-box { 
            background: #f8f9fa; 
            border-left: 4px solid #007bff; 
            padding: 10px; 
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }
        .empleado-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-primary">
                    <i class="fas fa-receipt me-2"></i>Detalle de Prestaciones Sociales
                </h1>
                <div class="btn-group">
                    <a href="/ZIGMA/public/index.php?url=PrestacionesSociales" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <button onclick="window.print()" class="btn btn-success">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($calculo) && $calculo): ?>
                <!-- Información del Empleado -->
                <div class="card mb-4 empleado-info">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h3><i class="fas fa-user me-2"></i><?= htmlspecialchars($calculo['empleado']['nombre'] . ' ' . $calculo['empleado']['apellido']) ?></h3>
                                <p class="mb-0">ID: <?= $calculo['empleado']['id'] ?></p>
                            </div>
                            <div class="col-md-6">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <h4 class="currency">$<?= number_format($calculo['empleado']['salario_mensual'], 0) ?></h4>
                                        <small>Salario Mensual</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="currency">$<?= number_format($calculo['empleado']['auxilio_transporte'], 0) ?></h4>
                                        <small>Auxilio Transporte</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parámetros de Cálculo -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-cog"></i> Parámetros de Cálculo</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <h6>Días Trabajados</h6>
                                        <span class="badge bg-primary fs-6">Período: <?= $calculo['parametros']['periodo'] ?? 'Mensual' ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <h6>Salario Mínimo</h6>
                                        <span class="currency">$<?= number_format($calculo['parametros']['salario_minimo'], 0) ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <h6>Total Devengado</h6>
                                        <span class="currency">$<?= number_format($calculo['empleado']['total_devengado'], 0) ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <h6>Tipo Cálculo</h6>
                                        <span class="badge bg-warning fs-6"><?= $calculo['parametros']['periodo'] ?? 'Mensual' ?></span>
                                    </div>
                                    <div class="col-md-3">
                                        <h6>Fecha Cálculo</h6>
                                        <span><?= date('d/m/Y') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cálculos Detallados -->
                <div class="row">
                    <!-- Cesantías -->
                    <div class="col-md-6 mb-4">
                        <div class="card calculation-card h-100">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-piggy-bank me-2"></i>Cesantías</h5>
                            </div>
                            <div class="card-body">
                                <div class="formula-box">
                                    <?= htmlspecialchars($calculo['prestaciones']['cesantias']['formula'] ?? 'Total Devengado × 8.33%') ?>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Total devengado base:</small>
                                    <div class="currency">$<?= number_format($calculo['empleado']['total_devengado'], 2) ?></div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Porcentaje aplicado:</small>
                                    <div>8.33%</div>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <h4 class="currency text-info">$<?= number_format($calculo['prestaciones']['cesantias']['valor_cesantias'] ?? 0, 2) ?></h4>
                                    <small class="text-muted">Valor Cesantías</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Intereses sobre Cesantías -->
                    <div class="col-md-6 mb-4">
                        <div class="card calculation-card h-100">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-percentage me-2"></i>Intereses sobre Cesantías</h5>
                            </div>
                            <div class="card-body">
                                <div class="formula-box">
                                    <?= htmlspecialchars($calculo['prestaciones']['intereses_cesantias']['formula'] ?? 'Total Devengado × 1%') ?>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Total devengado base:</small>
                                    <div class="currency">$<?= number_format($calculo['empleado']['total_devengado'], 2) ?></div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Porcentaje aplicado:</small>
                                    <div>1.00%</div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Porcentaje aplicado:</small>
                                    <div>1.00%</div>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <h4 class="currency text-warning">$<?= number_format($calculo['prestaciones']['intereses_cesantias']['valor_intereses'] ?? 0, 2) ?></h4>
                                    <small class="text-muted">Valor Intereses</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prima de Servicios -->
                    <div class="col-md-6 mb-4">
                        <div class="card calculation-card h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-star me-2"></i>Prima de Servicios</h5>
                            </div>
                            <div class="card-body">
                                <div class="formula-box">
                                    <?= htmlspecialchars($calculo['prestaciones']['prima_servicios']['formula'] ?? 'Total Devengado × 8.33%') ?>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Total devengado base:</small>
                                    <div class="currency">$<?= number_format($calculo['empleado']['total_devengado'], 2) ?></div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Porcentaje aplicado:</small>
                                    <div>8.33%</div>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <h4 class="currency text-success">$<?= number_format($calculo['prestaciones']['prima_servicios']['valor_prima'] ?? 0, 2) ?></h4>
                                    <small class="text-muted">Valor Prima</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vacaciones -->
                    <div class="col-md-6 mb-4">
                        <div class="card calculation-card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-umbrella-beach me-2"></i>Vacaciones</h5>
                            </div>
                            <div class="card-body">
                                <div class="formula-box">
                                    <?= htmlspecialchars($calculo['prestaciones']['vacaciones']['formula'] ?? '(Total Devengado - Auxilio Transporte) × 4.17%') ?>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Base de cálculo:</small>
                                    <div class="currency">$<?= number_format($calculo['prestaciones']['vacaciones']['base_vacaciones'] ?? 0, 2) ?></div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-muted">Auxilio transporte descontado:</small>
                                    <div class="currency">$<?= number_format($calculo['prestaciones']['vacaciones']['auxilio_transporte'] ?? 0, 2) ?></div>
                                </div>
                                <div class="mb-2">
                                    <small class="text-info"><i class="fas fa-info-circle"></i> <?= $calculo['prestaciones']['vacaciones']['nota'] ?? 'Las vacaciones NO incluyen auxilio de transporte' ?></small>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <h4 class="currency text-primary">$<?= number_format($calculo['prestaciones']['vacaciones']['valor_vacaciones'] ?? 0, 2) ?></h4>
                                    <small class="text-muted">Valor Vacaciones</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumen Total -->
                <div class="card mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Resumen Total de Prestaciones</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <td>Cesantías:</td>
                                        <td class="text-end currency">$<?= number_format($calculo['resumen']['desglose']['cesantias'] ?? 0, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Intereses sobre Cesantías:</td>
                                        <td class="text-end currency">$<?= number_format($calculo['resumen']['desglose']['intereses'] ?? 0, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Prima de Servicios:</td>
                                        <td class="text-end currency">$<?= number_format($calculo['resumen']['desglose']['prima'] ?? 0, 2) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Vacaciones:</td>
                                        <td class="text-end currency">$<?= number_format($calculo['resumen']['desglose']['vacaciones'] ?? 0, 2) ?></td>
                                    </tr>
                                    <tr class="border-top">
                                        <td><strong>TOTAL PRESTACIONES SOCIALES:</strong></td>
                                        <td class="text-end"><h4 class="currency text-danger">$<?= number_format($calculo['resumen']['total_prestaciones'] ?? 0, 2) ?></h4></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="bg-light p-3 rounded">
                                    <h6>Equivale al</h6>
                                    <h3 class="text-danger">
                                        <?= number_format(($calculo['resumen']['total_prestaciones'] / $calculo['empleado']['salario_mensual']) * 100, 1) ?>%
                                    </h3>
                                    <small class="text-muted">del salario mensual</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del cálculo -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información del Cálculo</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Tipo de cálculo:</strong> Mensual (basado en total devengado)</p>
                                <p><strong>Total devengado:</strong> $<?= number_format($calculo['empleado']['total_devengado']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Fecha:</strong> <?= date('d/m/Y H:i') ?></p>
                                <p><strong>Período:</strong> <?= $calculo['parametros']['periodo'] ?? 'Mensual' ?></p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <div class="alert alert-warning text-center">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <h4>No se pudieron obtener los datos</h4>
                    <p>No se encontró información del empleado o no se pudo calcular las prestaciones sociales.</p>
                    <a href="/ZIGMA/public/index.php?url=PrestacionesSociales" class="btn btn-primary">
                        Volver a Prestaciones Sociales
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>