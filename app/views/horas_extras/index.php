<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Horas Extras - ZIGMA</title>
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
    </style>
</head>
<body>

<!-- Navbar -->
<?php $pageTitle = "Gestión de Horas Extras"; ?>
<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 fade-in-up">
            <div class="page-header-zigma mb-4">
                <h2><i class="fa fa-clock me-2"></i>Gestión de Horas Extras</h2>
            </div>
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card-zigma text-center shadow">
                        <div class="card-body">
                            <h5 class="card-title text-zigma-primary"><i class="fa fa-users me-2"></i>Total de Empleados</h5>
                            <p class="display-6 fw-bold text-zigma-navy">
                                <?= isset($empleados) && is_array($empleados) ? count($empleados) : 0 ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-zigma text-center shadow">
                        <div class="card-body">
                            <h5 class="card-title text-zigma-secondary"><i class="fa fa-clock me-2"></i>Total de Horas</h5>
                            <p class="display-6 fw-bold text-zigma-navy">
                                <?php 
                                $total_horas = 0;
                                if (!empty($empleados)) {
                                    foreach ($empleados as $e) {
                                        $total_horas += isset($e['total_horas']) ? floatval($e['total_horas']) : 0;
                                    }
                                    echo number_format($total_horas, 1) . ' hrs';
                                } else {
                                    echo '0 hrs';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-zigma text-center shadow">
                        <div class="card-body">
                            <h5 class="card-title text-zigma-primary"><i class="fa fa-dollar-sign me-2"></i>Valor Total</h5>
                            <p class="display-6 fw-bold text-zigma-navy">
                                <?php 
                                $total_valor = 0;
                                if (!empty($empleados)) {
                                    foreach ($empleados as $e) {
                                        $total_valor += isset($e['total_valor']) ? floatval($e['total_valor']) : 0;
                                    }
                                    echo '$' . number_format($total_valor, 0, ',', '.');
                                } else {
                                    echo '$0';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-zigma text-center shadow">
                        <div class="card-body">
                            <h5 class="card-title text-zigma-secondary">Año Actual</h5>
                            <p class="display-6 fw-bold text-zigma-navy">
                                <?= date('Y') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-zigma shadow mb-4">
                <div class="card-body p-0">
                    <div class="p-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0 text-zigma-navy">
                                <i class="fa fa-clock me-2"></i>Gestión de Horas Extras
                            </h4>
                            <div class="d-flex gap-2">
                                <?php if ($canAccessAllEmployees): ?>
                                <a href="/ZIGMA/public/index.php?url=HorasExtras/historial" class="btn-zigma-secondary">
                                    <i class="fa fa-history me-2"></i> Historial Completo
                                </a>
                                <?php endif; ?>
                                <?php if ($canApprove): ?>
                                <a href="/ZIGMA/public/index.php?url=HorasExtras/pendientes" class="btn btn-warning">
                                    <i class="fa fa-bell me-2"></i> Pendientes
                                    <?php if ($pendingCount > 0): ?>
                                        <span class="badge bg-danger"><?php echo $pendingCount; ?></span>
                                    <?php endif; ?>
                                </a>
                                <?php endif; ?>
                                <a href="/ZIGMA/public/index.php?url=HorasExtras/create" class="btn-zigma-primary">
                                    <i class="fa fa-plus me-2"></i> Agregar Horas Extras
                                </a>
                            </div>
                        </div>
                    <form class="row g-2 align-items-center mb-3" method="get" action="/ZIGMA/public/index.php">
                        <input type="hidden" name="url" value="HorasExtras">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text bg-zigma-gradient text-white">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="buscar_empleado" class="form-control" 
                                       placeholder="Buscar por nombre del empleado..." 
                                       value="<?= isset($_GET['buscar_empleado']) ? htmlspecialchars($_GET['buscar_empleado']) : '' ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select name="filtro_rol" class="form-select">
                                <option value="">Filtrar por Rol</option>
                                <option value="admin" <?= (isset($filtro_rol) && $filtro_rol == 'admin') ? 'selected' : '' ?>>Admin</option>
                                <option value="empleado" <?= (isset($filtro_rol) && $filtro_rol == 'empleado') ? 'selected' : '' ?>>Empleado</option>
                                <option value="rrhh" <?= (isset($filtro_rol) && $filtro_rol == 'rrhh') ? 'selected' : '' ?>>RRHH</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="filtro_horas" class="form-select">
                                <option value="">Filtrar por Horas</option>
                                <option value="0" <?= (isset($_GET['filtro_horas']) && $_GET['filtro_horas'] == '0') ? 'selected' : '' ?>>Sin horas</option>
                                <option value="1-10" <?= (isset($_GET['filtro_horas']) && $_GET['filtro_horas'] == '1-10') ? 'selected' : '' ?>>1-10 horas</option>
                                <option value="11-20" <?= (isset($_GET['filtro_horas']) && $_GET['filtro_horas'] == '11-20') ? 'selected' : '' ?>>11-20 horas</option>
                                <option value="21-40" <?= (isset($_GET['filtro_horas']) && $_GET['filtro_horas'] == '21-40') ? 'selected' : '' ?>>21-40 horas</option>
                                <option value="40+" <?= (isset($_GET['filtro_horas']) && $_GET['filtro_horas'] == '40+') ? 'selected' : '' ?>>Más de 40</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="filtro_tipo" class="form-select">
                                <option value="">Filtrar por Tipo</option>
                                <?php if (!empty($tipos_disponibles)): ?>
                                    <?php foreach ($tipos_disponibles as $tipo): ?>
                                        <option value="<?= htmlspecialchars($tipo) ?>" <?= (isset($_GET['filtro_tipo']) && $_GET['filtro_tipo'] == $tipo) ? 'selected' : '' ?>><?= htmlspecialchars(ucfirst($tipo)) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-zigma-primary">
                                <i class="fas fa-filter me-1"></i> Filtrar
                            </button>
                        </div>
                        <div class="col-auto">
                            <a href="/ZIGMA/public/index.php?url=HorasExtras" class="btn btn-zigma-secondary">
                                <i class="fas fa-times me-1"></i> Limpiar
                            </a>
                        </div>
                    </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table-zigma">
                            <thead>
                                <tr>
                                    <th style="width: 4%;">ID</th>
                                    <th style="width: 13%;">Nombres</th>
                                    <th style="width: 13%;">Apellidos</th>
                                    <th style="width: 10%;">Rol</th>
                                    <th style="width: 10%;">Horas</th>
                                    <th style="width: 12%;">Valor Total</th>
                                    <th style="width: 20%;">Tipo de Horas</th>
                                    <th style="width: 18%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Debug temporal
                                echo "<!-- DEBUG: empleados variable: " . var_export($empleados, true) . " -->";
                                
                                if (isset($empleados) && is_array($empleados) && !empty($empleados)): ?>
                                    <?php foreach ($empleados as $emp): ?>
                                        <?php if (in_array($emp['id_empleados'], [1,2,3])) continue; ?>
                                        <tr>
                                            <td><span class="badge-zigma-info"><?= htmlspecialchars($emp['id_empleados'] ?? '') ?></span></td>
                                            <td><?= htmlspecialchars($emp['nombre'] ?? '') ?></td>
                                            <td><?= htmlspecialchars(($emp['apellidos'] ?? null) !== null ? $emp['apellidos'] : ($emp['apellido'] ?? '')) ?></td>
                                            <td>
                                                <?php 
                                                $rol_badge_class = '';
                                                $rol_display = '';
                                                $todos_roles = isset($emp['todos_los_roles']) ? $emp['todos_los_roles'] : '';
                                                $emp_rol = $emp['rol_nombre'] ?? $emp['rol'] ?? '';
                                                switch($emp_rol) {
                                                    case 'admin': $rol_badge_class = 'badge-role-admin'; $rol_display = 'Admin'; break;
                                                    case 'rrhh': $rol_badge_class = 'badge-role-rrhh'; $rol_display = 'RRHH'; break;
                                                    case 'empleado': $rol_badge_class = 'badge-role-empleado'; $rol_display = 'Empleado'; break;
                                                    default: $rol_badge_class = 'badge-role-default'; $rol_display = 'Sin rol'; break;
                                                }
                                                $tooltip = '';
                                                if ($todos_roles && strpos($todos_roles, ',') !== false) {
                                                    $tooltip = 'title="Roles: ' . htmlspecialchars(str_replace(',', ', ', $todos_roles)) . '" data-bs-toggle="tooltip"';
                                                }
                                                ?>
                                                <span class="badge <?= $rol_badge_class ?>" <?= $tooltip ?>><?= $rol_display ?></span>
                                            </td>
                                            <td>
                                                <?php if ((float)($emp['total_horas'] ?? 0) > 0): ?>
                                                    <span class="badge-zigma-secondary"><?= htmlspecialchars($emp['total_horas']) ?> horas</span>
                                                <?php else: ?>
                                                    <span class="badge-zigma-info">0 horas</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php 
                                                $total_valor_emp = isset($emp['total_valor']) ? floatval($emp['total_valor']) : 0;
                                                if ($total_valor_emp > 0): ?>
                                                    <strong class="text-zigma-secondary">$<?= number_format($total_valor_emp, 0, ',', '.') ?></strong>
                                                <?php else: ?>
                                                    <span class="text-muted">$0</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php $tipo_frec = $emp['tipo_frecuente'] ?? 'N/A'; ?>
                                                <?php if ($tipo_frec !== 'N/A'): ?>
                                                    <span class="badge-zigma-primary"><?= htmlspecialchars($tipo_frec) ?></span>
                                                <?php else: ?>
                                                    <span class="badge-zigma-info">Sin tipo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php $emp_id = $emp['id_empleados'] ?? ''; ?>
                                                <div class="btn-group" role="group">
                                                    <a href="/ZIGMA/public/index.php?url=HorasExtras/detalle/<?= urlencode($emp_id) ?>" 
                                                       class="btn btn-zigma-primary btn-sm" title="Ver detalle de horas extras">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="/ZIGMA/public/index.php?url=HorasExtras/historial/<?= urlencode($emp_id) ?>" 
                                                       class="btn btn-info btn-sm" title="Ver historial con aprobaciones">
                                                        <i class="fa fa-history"></i>
                                                    </a>
                                                    <a href="/ZIGMA/public/index.php?url=HorasExtras/create&empleado_id=<?= urlencode($emp_id) ?>" 
                                                       class="btn btn-zigma-secondary btn-sm" title="Agregar nuevas horas extras">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="8" class="text-center">
                                        <?php 
                                        if (!isset($empleados)) {
                                            echo "Variable empleados no está definida";
                                        } elseif (!is_array($empleados)) {
                                            echo "Variable empleados no es un array: " . gettype($empleados);
                                        } elseif (empty($empleados)) {
                                            echo "Array empleados está vacío";
                                        } else {
                                            echo "No hay empleados registrados.";
                                        }
                                        ?>
                                    </td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Activar tooltips de Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
