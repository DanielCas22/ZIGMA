<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
    <style>
        @media (max-width: 576px) {
            .table, .table-responsive, .table th, .table td {
                font-size: clamp(11px, 3vw, 13px) !important;
                padding: 6px !important;
                white-space: normal !important;
                word-break: break-word !important;
            }
            .table-responsive {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            .btn, .btn-sm {
                font-size: clamp(12px, 3vw, 14px) !important;
                padding: 6px 12px !important;
                min-width: 80px;
                max-width: 140px;
            }
            h1, h2, h3, h4, h5, h6 {
                font-size: clamp(1rem, 4vw, 1.2rem) !important;
            }
        }
        @media (min-width: 577px) {
            .btn, .btn-zigma-success, .btn-zigma-secondary {
                font-size: 15px !important;
                padding: 8px 18px !important;
                min-width: 100px;
                max-width: 180px;
            }
        }
    </style>
</head>
<body>
<!-- Navbar -->
<?php $pageTitle = $empleado ? "Historial - " . $empleado['nombre'] . " " . $empleado['apellido'] : "Historial de Horas Extras"; ?>
<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 fade-in-up">
            <div class="card-zigma shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <span class="d-inline-block bg-gradient-zigma text-white rounded-circle p-3 mb-2 pulse">
                            <i class="fa fa-history fa-2x"></i>
                        </span>
                        <h2 class="mb-0 text-zigma-primary fw-bold"><?php echo $title; ?></h2>
                        <p class="text-muted">Historial completo con información de aprobación</p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Registros (<?php echo count($historial); ?>)
                        </h5>
                        <div>
                            <a href="/ZIGMA/public/index.php?url=HorasExtras" class="btn-zigma-secondary me-2">
                                <i class="fas fa-arrow-left me-2"></i>Volver
                            </a>
                            <?php if ($empleado): ?>
                            <a href="/ZIGMA/public/index.php?url=HorasExtras/detalle/<?php echo $empleado['id_empleados']; ?>" class="btn-zigma-primary">
                                <i class="fas fa-chart-line me-2"></i>Ver Resumen
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (empty($historial)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-muted" style="font-size: 4rem;"></i>
                            <h4 class="mt-3 text-muted">No hay registros de horas extras</h4>
                            <p class="text-muted"><?php echo $empleado ? 'Este empleado no tiene horas extras registradas.' : 'No se han registrado horas extras en el sistema.'; ?></p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <?php if (!$empleado): ?>
                                        <th><i class="fas fa-user me-2"></i>Empleado</th>
                                        <?php endif; ?>
                                        <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                                        <th><i class="fas fa-clock me-2"></i>Horas</th>
                                        <th><i class="fas fa-tag me-2"></i>Tipo</th>
                                        <th><i class="fas fa-dollar-sign me-2"></i>Valor</th>
                                        <th><i class="fas fa-traffic-light me-2"></i>Estado</th>
                                        <th><i class="fas fa-calendar-plus me-2"></i>Creada</th>
                                        <th><i class="fas fa-check-circle me-2"></i>Aprobación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historial as $hora): ?>
                                        <tr>
                                            <?php if (!$empleado): ?>
                                            <td>
                                                <strong><?php echo htmlspecialchars($hora['empleado_nombre']); ?></strong>
                                                <?php if (!empty($hora['empleado_documento'])): ?>
                                                    <br><small class="text-muted">CC: <?php echo htmlspecialchars($hora['empleado_documento']); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <?php endif; ?>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?php echo str_pad($hora['dia'], 2, '0', STR_PAD_LEFT); ?>/<?php echo str_pad($hora['mes'], 2, '0', STR_PAD_LEFT); ?>/<?php echo $hora['anio']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary fs-6">
                                                    <?php echo number_format($hora['cantidad'], 2); ?> hrs
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?php echo htmlspecialchars($hora['tipo']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">
                                                    $<?php echo number_format($hora['valor'], 0); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($hora['estado'] === 'aprobada'): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Aprobada
                                                    </span>
                                                <?php elseif ($hora['estado'] === 'rechazada'): ?>
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times me-1"></i>Rechazada
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i>Pendiente
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php echo $hora['fecha_creacion_formatted']; ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php if ($hora['estado'] === 'aprobada' || $hora['estado'] === 'rechazada'): ?>
                                                    <div class="text-center">
                                                        <strong class="d-block text-<?php echo $hora['estado'] === 'aprobada' ? 'success' : 'danger'; ?>">
                                                            <?php echo htmlspecialchars($hora['aprobado_por_usuario'] ?? 'N/A'); ?>
                                                        </strong>
                                                        <small class="text-muted">
                                                            <?php echo $hora['fecha_aprobacion_formatted'] ?? 'N/A'; ?>
                                                        </small>
                                                        <?php if (!empty($hora['comentario_aprobacion'])): ?>
                                                            <div class="mt-1">
                                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                                        data-bs-toggle="tooltip" 
                                                                        title="<?php echo htmlspecialchars($hora['comentario_aprobacion']); ?>">
                                                                    <i class="fas fa-comment"></i>
                                                                </button>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted">
                                                        <i class="fas fa-hourglass-half"></i>
                                                        Pendiente
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Estadísticas Resumen -->
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-success">Aprobadas</h5>
                                        <h3 class="text-success">
                                            <?php 
                                            $aprobadas = array_filter($historial, function($h) { return $h['estado'] === 'aprobada'; });
                                            echo count($aprobadas);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-danger">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-danger">Rechazadas</h5>
                                        <h3 class="text-danger">
                                            <?php 
                                            $rechazadas = array_filter($historial, function($h) { return $h['estado'] === 'rechazada'; });
                                            echo count($rechazadas);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-warning">Pendientes</h5>
                                        <h3 class="text-warning">
                                            <?php 
                                            $pendientes = array_filter($historial, function($h) { return $h['estado'] === 'pendiente'; });
                                            echo count($pendientes);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <h5 class="card-title text-primary">Total Horas</h5>
                                        <h3 class="text-primary">
                                            <?php 
                                            $total_horas = array_sum(array_column($aprobadas, 'cantidad'));
                                            echo number_format($total_horas, 2);
                                            ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Inicializar tooltips para los comentarios
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})
</script>
</body>
</html>
