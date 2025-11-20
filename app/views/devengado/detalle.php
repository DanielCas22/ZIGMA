<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
    <style>
        .detalle-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .concepto-card {
            transition: transform 0.2s;
            border-left: 4px solid #28a745;
        }
        .concepto-card:hover {
            transform: translateY(-2px);
        }
        .valor-principal {
            font-size: 1.5rem;
            font-weight: 700;
            color: #28a745;
        }
        .badge-porcentaje {
            font-size: 0.8rem;
        }
        .tabla-desglose th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .total-devengado {
            background: #e8f5e9;
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 1.5rem;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Header del Detalle -->
    <div class="detalle-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-0">
                        <i class="fas fa-user-circle me-3"></i>
                        Detalle de Devengado
                    </h1>
                    <?php if (isset($calculo['empleado'])): ?>
                    <p class="mb-0 mt-2 opacity-90">
                        <strong><?= htmlspecialchars($calculo['empleado']['nombre'] . ' ' . $calculo['empleado']['apellido']) ?></strong>
                        <?php
                        $rol_nombre = strtolower($calculo['empleado']['cargo'] ?? 'Sin cargo');
                        $badge_class = 'badge-role-default';
                        if (strpos($rol_nombre, 'admin') !== false) {
                            $badge_class = 'badge-role-admin';
                        } elseif (strpos($rol_nombre, 'rrhh') !== false || strpos($rol_nombre, 'recursos humanos') !== false) {
                            $badge_class = 'badge-role-rrhh';
                        } elseif (strpos($rol_nombre, 'empleado') !== false) {
                            $badge_class = 'badge-role-empleado';
                        }
                        ?>
                        - <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($calculo['empleado']['cargo'] ?? 'Sin cargo especificado') ?></span>
                    </p>
                    <?php endif; ?>
                </div>
                <div class="col-md-4 text-end">
                    <a href="/public/index.php?url=devengado" class="btn btn-light btn-lg me-2">
                        <i class="fas fa-arrow-left me-2"></i>Regresar
                    </a>
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

        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($calculo) && $calculo): ?>
        
        <!-- Resumen Total -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="total-devengado text-center">
                    <h3 class="text-success mb-1">TOTAL DEVENGADO</h3>
                    <div class="valor-principal">$<?= number_format($calculo['resumen']['total_devengado']) ?></div>
                    <small class="text-muted">Período: <?= date('F Y') ?></small>
                </div>
            </div>
        </div>

        <!-- Conceptos del Devengado -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card concepto-card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-dollar-sign me-2"></i>
                            Sueldo Básico
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-success mb-1">$<?= number_format($calculo['conceptos']['sueldo_basico']['valor']) ?></h4>
                                <p class="text-muted mb-0"><?= htmlspecialchars($calculo['conceptos']['sueldo_basico']['descripcion']) ?></p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success badge-porcentaje">
                                    <?= number_format($calculo['resumen']['porcentajes']['sueldo_basico'], 1) ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card concepto-card h-100">
                    <div class="card-header" style="background: #fd7e14; color: white;">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Horas Extras
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h4 class="text-warning mb-1">$<?= number_format($calculo['conceptos']['horas_extras']['valor']) ?></h4>
                                <p class="text-muted mb-0"><?= htmlspecialchars($calculo['conceptos']['horas_extras']['descripcion']) ?></p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-warning badge-porcentaje">
                                    <?= number_format($calculo['resumen']['porcentajes']['horas_extras'], 1) ?>%
                                </span>
                            </div>
                        </div>
                        <?php if ($calculo['conceptos']['horas_extras']['total_horas'] > 0): ?>
                        <small class="text-info">
                            <i class="fas fa-clock me-1"></i>
                            Total horas: <?= $calculo['conceptos']['horas_extras']['total_horas'] ?>
                        </small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card concepto-card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-bus me-2"></i>
                            Auxilio Transporte
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-info mb-1">$<?= number_format($calculo['conceptos']['auxilio_transporte']['valor']) ?></h4>
                                <p class="text-muted mb-0 small"><?= htmlspecialchars($calculo['conceptos']['auxilio_transporte']['descripcion']) ?></p>
                            </div>
                            <div class="text-end">
                                <?php if ($calculo['conceptos']['auxilio_transporte']['aplica']): ?>
                                    <span class="badge bg-info badge-porcentaje">
                                        <?= number_format($calculo['resumen']['porcentajes']['auxilio_transporte'], 1) ?>%
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary badge-porcentaje">N/A</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="alert alert-info mt-2 p-2 small">
                            <b>Nota:</b> El auxilio de transporte solo se otorga si el salario mensual es menor o igual a 2 SMLV vigentes (actualmente $2,846,000). Si el salario supera este valor, no se asigna auxilio, sin importar el cargo o rol.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card concepto-card h-100">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-percentage me-2"></i>
                            Comisiones
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-secondary mb-1">$<?= number_format($calculo['conceptos']['comisiones']['valor']) ?></h4>
                                <p class="text-muted mb-0"><?= htmlspecialchars($calculo['conceptos']['comisiones']['descripcion']) ?></p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-secondary badge-porcentaje">
                                    <?= number_format($calculo['resumen']['porcentajes']['comisiones'], 1) ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card concepto-card h-100">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            Otros Conceptos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-dark mb-1">$<?= number_format($calculo['conceptos']['otros']['valor']) ?></h4>
                                <p class="text-muted mb-0"><?= htmlspecialchars($calculo['conceptos']['otros']['descripcion']) ?></p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-dark badge-porcentaje">
                                    <?= number_format($calculo['resumen']['porcentajes']['otros'], 1) ?>%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desglose Detallado -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-list-alt me-2"></i>
                    Desglose Detallado
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table tabla-desglose">
                        <thead>
                            <tr>
                                <th>Concepto</th>
                                <th class="text-end">Valor</th>
                                <th class="text-end">Porcentaje</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Sueldo Básico</strong></td>
                                <td class="text-end">$<?= number_format($calculo['resumen']['desglose']['sueldo_basico']) ?></td>
                                <td class="text-end">
                                    <span class="badge bg-success"><?= number_format($calculo['resumen']['porcentajes']['sueldo_basico'], 1) ?>%</span>
                                </td>
                                <td>Salario mensual base</td>
                            </tr>
                            <tr>
                                <td><strong>Horas Extras</strong></td>
                                <td class="text-end">$<?= number_format($calculo['resumen']['desglose']['horas_extras']) ?></td>
                                <td class="text-end">
                                    <span class="badge bg-warning"><?= number_format($calculo['resumen']['porcentajes']['horas_extras'], 1) ?>%</span>
                                </td>
                                <td>Horas adicionales trabajadas</td>
                            </tr>
                            <tr>
                                <td><strong>Auxilio de Transporte</strong></td>
                                <td class="text-end">$<?= number_format($calculo['resumen']['desglose']['auxilio_transporte']) ?></td>
                                <td class="text-end">
                                    <span class="badge bg-info"><?= number_format($calculo['resumen']['porcentajes']['auxilio_transporte'], 1) ?>%</span>
                                </td>
                                <td>Auxilio legal de transporte</td>
                            </tr>
                            <tr>
                                <td><strong>Comisiones</strong></td>
                                <td class="text-end">$<?= number_format($calculo['resumen']['desglose']['comisiones']) ?></td>
                                <td class="text-end">
                                    <span class="badge bg-secondary"><?= number_format($calculo['resumen']['porcentajes']['comisiones'], 1) ?>%</span>
                                </td>
                                <td>Comisiones por ventas/metas</td>
                            </tr>
                            <tr>
                                <td><strong>Otros Conceptos</strong></td>
                                <td class="text-end">$<?= number_format($calculo['resumen']['desglose']['otros']) ?></td>
                                <td class="text-end">
                                    <span class="badge bg-dark"><?= number_format($calculo['resumen']['porcentajes']['otros'], 1) ?>%</span>
                                </td>
                                <td>Otros ingresos adicionales</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <th><strong>TOTAL DEVENGADO</strong></th>
                                <th class="text-end"><strong>$<?= number_format($calculo['resumen']['total_devengado']) ?></strong></th>
                                <th class="text-end"><span class="badge bg-success">100%</span></th>
                                <th><strong>Total de ingresos del empleado</strong></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Información del Empleado -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            Información del Empleado
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li><strong>Nombre:</strong> <?= htmlspecialchars($calculo['empleado']['nombre'] . ' ' . $calculo['empleado']['apellido']) ?></li>
                            <li><strong>Documento:</strong> <?= htmlspecialchars($calculo['empleado']['documento'] ?: 'N/A') ?></li>
                            <li>
                                <strong>Cargo:</strong> 
                                <?php
                                $rol_nombre = strtolower($calculo['empleado']['cargo'] ?? 'Sin cargo');
                                $badge_class = 'badge-role-default';
                                if (strpos($rol_nombre, 'admin') !== false) {
                                    $badge_class = 'badge-role-admin';
                                } elseif (strpos($rol_nombre, 'rrhh') !== false || strpos($rol_nombre, 'recursos humanos') !== false) {
                                    $badge_class = 'badge-role-rrhh';
                                } elseif (strpos($rol_nombre, 'empleado') !== false) {
                                    $badge_class = 'badge-role-empleado';
                                }
                                ?>
                                <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($calculo['empleado']['cargo']) ?></span>
                            </li>
                            <li><strong>ID Empleado:</strong> <?= $calculo['empleado']['id'] ?></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-cog me-2"></i>
                            Parámetros de Cálculo
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li><strong>Salario Mínimo:</strong> $<?= number_format($calculo['parametros']['salario_minimo']) ?></li>
                            <li><strong>Aux. Transporte:</strong> $<?= number_format($calculo['parametros']['auxilio_transporte_valor']) ?></li>
                            <li><strong>Límite Auxilio:</strong> $<?= number_format($calculo['parametros']['auxilio_transporte_limite']) ?></li>
                            <li><strong>Fecha Cálculo:</strong> <?= date('d/m/Y H:i', strtotime($calculo['parametros']['fecha_calculo'])) ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No se pudo cargar la información del devengado</h5>
            <p class="text-muted">Verifique que el empleado existe y tiene datos válidos</p>
            <a href="/public/index.php?url=devengado" class="btn btn-success">
                <i class="fas fa-arrow-left me-2"></i>Volver al listado
            </a>
        </div>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>