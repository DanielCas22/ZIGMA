<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horas Extras Pendientes - ZIGMA</title>
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
<?php $pageTitle = "Horas Extras Pendientes"; ?>
<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 fade-in-up">
            <div class="card-zigma shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <span class="d-inline-block bg-gradient-zigma text-white rounded-circle p-3 mb-2 pulse">
                            <i class="fa fa-clock fa-2x"></i>
                        </span>
                        <h2 class="mb-0 text-zigma-primary fw-bold">Horas Extras Pendientes</h2>
                        <p class="text-muted">Aprobar o rechazar solicitudes de horas extras</p>
                    </div>

                    <!-- Mensajes de éxito/error -->
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php if ($_GET['success'] === 'aprobado'): ?>
                                Horas extras aprobadas exitosamente.
                            <?php elseif ($_GET['success'] === 'rechazado'): ?>
                                Horas extras rechazadas exitosamente.
                            <?php endif; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error al procesar la solicitud. Inténtelo nuevamente.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="fas fa-list-check me-2"></i>
                            Solicitudes Pendientes (<?php echo count($pendientes); ?>)
                        </h5>
                        <a href="/ZIGMA/public/index.php?url=HorasExtras" class="btn-zigma-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>

                    <?php if (empty($pendientes)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                            <h4 class="mt-3 text-muted">¡No hay solicitudes pendientes!</h4>
                            <p class="text-muted">Todas las horas extras han sido procesadas.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-user me-2"></i>Empleado</th>
                                        <th><i class="fas fa-calendar me-2"></i>Fecha</th>
                                        <th><i class="fas fa-clock me-2"></i>Horas</th>
                                        <th><i class="fas fa-tag me-2"></i>Tipo</th>
                                        <th><i class="fas fa-dollar-sign me-2"></i>Valor</th>
                                        <th><i class="fas fa-calendar-plus me-2"></i>Solicitada</th>
                                        <th><i class="fas fa-user-tag me-2"></i>Rol</th>
                                        <th><i class="fas fa-cogs me-2"></i>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pendientes as $hora): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($hora['nombre'] . ' ' . $hora['apellido']); ?></strong>
                                                <?php if (!empty($hora['documento'])): ?>
                                                    <br><small class="text-muted">CC: <?php echo htmlspecialchars($hora['documento']); ?></small>
                                                <?php endif; ?>
                                            </td>
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
                                                <small class="text-muted">
                                                    <?php echo $hora['fecha_creacion_formatted']; ?>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    <?php echo !empty($hora['rol']) ? htmlspecialchars($hora['rol']) : 'Sin rol'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php $idHora = isset($hora['id']) ? (int)$hora['id'] : null; ?>
                                                <div class="btn-group" role="group">
                                                    <?php if ($idHora): ?>
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        onclick="aprobar(
                                                            <?php echo $idHora; ?>,
                                                            <?php echo isset($hora['nombre'], $hora['apellido']) ? htmlspecialchars(json_encode($hora['nombre'] . ' ' . $hora['apellido']), ENT_QUOTES, 'UTF-8') : json_encode(''); ?>
                                                        )">
                                                        <i class="fas fa-check"></i> Aprobar
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="rechazar(
                                                            <?php echo $idHora; ?>,
                                                            <?php echo isset($hora['nombre'], $hora['apellido']) ? htmlspecialchars(json_encode($hora['nombre'] . ' ' . $hora['apellido']), ENT_QUOTES, 'UTF-8') : json_encode(''); ?>
                                                        )">
                                                        <i class="fas fa-times"></i> Rechazar
                                                    </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Aprobar -->
<div class="modal fade" id="modalAprobar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check me-2"></i>Aprobar Horas Extras</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/ZIGMA/public/index.php?url=HorasExtras/aprobar">
                <div class="modal-body">
                    <input type="hidden" id="aprobar_id" name="id">
                    <p>¿Está seguro de aprobar las horas extras de <strong id="aprobar_empleado"></strong>?</p>
                    <div class="mb-3">
                        <label for="comentario_aprobar" class="form-label">Comentario (opcional)</label>
                        <textarea class="form-control" id="comentario_aprobar" name="comentario" rows="3" 
                                  placeholder="Agregar comentario sobre la aprobación..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Aprobar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Rechazar -->
<div class="modal fade" id="modalRechazar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times me-2"></i>Rechazar Horas Extras</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/ZIGMA/public/index.php?url=HorasExtras/rechazar">
                <div class="modal-body">
                    <input type="hidden" id="rechazar_id" name="id">
                    <p>¿Está seguro de rechazar las horas extras de <strong id="rechazar_empleado"></strong>?</p>
                    <div class="mb-3">
                        <label for="comentario_rechazar" class="form-label">Motivo del rechazo <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="comentario_rechazar" name="comentario" rows="3" 
                                  placeholder="Explicar el motivo del rechazo..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>Rechazar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function aprobar(id, empleado) {
    document.getElementById('aprobar_id').value = id;
    document.getElementById('aprobar_empleado').textContent = empleado;
    document.getElementById('comentario_aprobar').value = '';
    var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalAprobar'));
    modal.show();
}

function rechazar(id, empleado) {
    document.getElementById('rechazar_id').value = id;
    document.getElementById('rechazar_empleado').textContent = empleado;
    document.getElementById('comentario_rechazar').value = '';
    var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalRechazar'));
    modal.show();
}

// Validar que el id se envía correctamente
const aprobarForm = document.querySelector('form[action*="HorasExtras/aprobar"]');
if (aprobarForm) {
    aprobarForm.addEventListener('submit', function(e) {
        if (!document.getElementById('aprobar_id').value) {
            e.preventDefault();
            alert('Error: No se encontró el ID de la hora extra a aprobar.');
        }
    });
}
const rechazarForm = document.querySelector('form[action*="HorasExtras/rechazar"]');
if (rechazarForm) {
    rechazarForm.addEventListener('submit', function(e) {
        if (!document.getElementById('rechazar_id').value) {
            e.preventDefault();
            alert('Error: No se encontró el ID de la hora extra a rechazar.');
        }
    });
}
</script>
</body>
</html>
