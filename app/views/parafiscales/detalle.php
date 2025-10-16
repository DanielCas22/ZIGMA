<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Parafiscales - <?= htmlspecialchars($empleado['nombre'] ?? 'Empleado') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
    <style>
        .card-base {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            border: none;
        }
        .card-sena {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
        }
        .card-icbf {
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
            color: white;
            border: none;
        }
        .card-caja {
            background: linear-gradient(135deg, #fd7e14 0%, #e83e8c 100%);
            color: white;
            border: none;
        }
        .card-total {
            background: linear-gradient(135deg, #dc3545 0%, #6f42c1 100%);
            color: white;
            border: none;
        }
        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-custom {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            color: white;
        }
        .info-tooltip {
            cursor: help;
            color: #6c757d;
        }
        .formula-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            font-family: monospace;
            margin: 10px 0;
        }
        .legal-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="fas fa-building text-success me-2"></i>
                            Parafiscales - <?= htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido']) ?>
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="fas fa-id-card me-1"></i> <?= htmlspecialchars($empleado['documento'] ?? 'N/A') ?> | 
                            <?php
                            $rol_nombre = strtolower($empleado['cargo'] ?? 'Sin rol');
                            $badge_class = 'badge-role-default';
                            if (strpos($rol_nombre, 'admin') !== false) {
                                $badge_class = 'badge-role-admin';
                            } elseif (strpos($rol_nombre, 'rrhh') !== false || strpos($rol_nombre, 'recursos humanos') !== false) {
                                $badge_class = 'badge-role-rrhh';
                            } elseif (strpos($rol_nombre, 'empleado') !== false) {
                                $badge_class = 'badge-role-empleado';
                            }
                            ?>
                            <i class="fas fa-briefcase me-1"></i> <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($empleado['cargo'] ?? 'No especificado') ?></span>
                        </p>
                    </div>
                    <div>
                        <a href="/ZIGMA/Parafiscales" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas Informativas -->
        <div class="row mb-4">
            <div class="col-md-2_4">
                <div class="card card-base h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-calculator fa-2x mb-2"></i>
                        <h6>Base de Cálculo</h6>
                        <h4>$<?= number_format($datos['base_calculo'], 0, ',', '.') ?></h4>
                        <small>Total Devengado</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2_4">
                <div class="card card-sena h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                        <h6>SENA</h6>
                        <h4>$<?= number_format($datos['sena'], 0, ',', '.') ?></h4>
                        <small>2.0% - Capacitación</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2_4">
                <div class="card card-icbf h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-child fa-2x mb-2"></i>
                        <h6>ICBF</h6>
                        <h4>$<?= number_format($datos['icbf'], 0, ',', '.') ?></h4>
                        <small>3.0% - Bienestar</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2_4">
                <div class="card card-caja h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <h6>Caja Compensación</h6>
                        <h4>$<?= number_format($datos['caja_compensacion'], 0, ',', '.') ?></h4>
                        <small>4.0% - Subsidios</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2_4">
                <div class="card card-total h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                        <h6>Total Parafiscales</h6>
                        <h4>$<?= number_format($datos['total_parafiscales'], 0, ',', '.') ?></h4>
                        <small>9.0% Total</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Legal -->
        <?php if (!empty($informacion_legal)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="legal-info">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5><i class="fas fa-balance-scale me-2"></i>Marco Legal</h5>
                            <ul class="mb-0">
                                <li><strong>Obligatorio para:</strong> <?= $informacion_legal['obligatorio_empleador'] ? 'El empleador únicamente' : 'N/A' ?></li>
                                <li><strong>Descuenta del empleado:</strong> <?= $informacion_legal['descuenta_empleado'] ? 'Sí' : 'No' ?></li>
                                <li><strong>Normativa:</strong> <?= htmlspecialchars($informacion_legal['normativa']) ?></li>
                                <li><strong>Observaciones:</strong> <?= htmlspecialchars($informacion_legal['observaciones']) ?></li>
                            </ul>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="fs-1 opacity-50">
                                <i class="fas fa-gavel"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Detalle de Cálculos -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Detalle de Cálculos Parafiscales
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Entidad</th>
                                        <th>Base de Cálculo</th>
                                        <th>Porcentaje</th>
                                        <th class="text-end">Valor</th>
                                        <th class="text-center">Fórmula</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Base de Cálculo -->
                                    <tr class="table-light">
                                        <td colspan="6">
                                            <strong>Base de Cálculo: Total Devengado</strong>
                                            <div class="formula-box mt-2">
                                                Base = Salario Básico + Auxilio de Transporte + Horas Extras + Otros Conceptos<br>
                                                Base = $<?= number_format($datos['base_calculo'], 0, ',', '.') ?>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- SENA -->
                                    <tr>
                                        <td>
                                            <i class="fas fa-graduation-cap text-success me-2"></i>
                                            <strong>SENA</strong>
                                            <br><small class="text-muted">Servicio Nacional de Aprendizaje</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">SENA</span>
                                        </td>
                                        <td>$<?= number_format($datos['base_calculo'], 0, ',', '.') ?></td>
                                        <td><?= number_format($datos['porcentajes']['sena'], 1) ?>%</td>
                                        <td class="text-end">
                                            <span class="badge bg-success fs-6">
                                                $<?= number_format($datos['sena'], 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <i class="fas fa-info-circle info-tooltip" 
                                               data-bs-toggle="tooltip" 
                                               title="<?= htmlspecialchars($datos['detalles']['sena']['formula'] ?? '') ?>"></i>
                                        </td>
                                    </tr>

                                    <!-- ICBF -->
                                    <tr>
                                        <td>
                                            <i class="fas fa-child text-primary me-2"></i>
                                            <strong>ICBF</strong>
                                            <br><small class="text-muted">Instituto Colombiano de Bienestar Familiar</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">ICBF</span>
                                        </td>
                                        <td>$<?= number_format($datos['base_calculo'], 0, ',', '.') ?></td>
                                        <td><?= number_format($datos['porcentajes']['icbf'], 1) ?>%</td>
                                        <td class="text-end">
                                            <span class="badge bg-primary fs-6">
                                                $<?= number_format($datos['icbf'], 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <i class="fas fa-info-circle info-tooltip" 
                                               data-bs-toggle="tooltip" 
                                               title="<?= htmlspecialchars($datos['detalles']['icbf']['formula'] ?? '') ?>"></i>
                                        </td>
                                    </tr>

                                    <!-- Caja de Compensación -->
                                    <tr>
                                        <td>
                                            <i class="fas fa-users text-warning me-2"></i>
                                            <strong>Caja de Compensación</strong>
                                            <br><small class="text-muted">Subsidio Familiar</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">CAJA COMP.</span>
                                        </td>
                                        <td>$<?= number_format($datos['base_calculo'], 0, ',', '.') ?></td>
                                        <td><?= number_format($datos['porcentajes']['caja_compensacion'], 1) ?>%</td>
                                        <td class="text-end">
                                            <span class="badge bg-warning text-dark fs-6">
                                                $<?= number_format($datos['caja_compensacion'], 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <i class="fas fa-info-circle info-tooltip" 
                                               data-bs-toggle="tooltip" 
                                               title="<?= htmlspecialchars($datos['detalles']['caja_compensacion']['formula'] ?? '') ?>"></i>
                                        </td>
                                    </tr>

                                    <!-- Total -->
                                    <tr class="table-danger">
                                        <td><strong>TOTAL PARAFISCALES</strong></td>
                                        <td><span class="badge bg-danger">TOTAL</span></td>
                                        <td>$<?= number_format($datos['base_calculo'], 0, ',', '.') ?></td>
                                        <td><strong><?= number_format($datos['porcentajes']['total'], 1) ?>%</strong></td>
                                        <td class="text-end">
                                            <strong class="h5">$<?= number_format($datos['total_parafiscales'], 0, ',', '.') ?></strong>
                                        </td>
                                        <td class="text-center">
                                            <i class="fas fa-calculator text-danger"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <small class="text-muted">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <strong>Importante:</strong> Los parafiscales son pagados únicamente por el empleador
                                </small>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    <strong>Periodicidad:</strong> Pago mensual obligatorio
                                </small>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">
                                    <i class="fas fa-percentage me-1"></i>
                                    <strong>Total:</strong> 9% sobre el total devengado
                                </small>
                            </div>
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

    <style>
        .col-md-2_4 {
            flex: 0 0 auto;
            width: 20%;
        }
        @media (max-width: 768px) {
            .col-md-2_4 {
                width: 100%;
                margin-bottom: 1rem;
            }
        }
    </style>
</body>
</html>