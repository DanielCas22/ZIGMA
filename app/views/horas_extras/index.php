<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Horas Extras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- ...dashboard y tabla aquí... -->
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="mb-4 text-primary">Horas Extras</h2>
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center shadow border-primary border-2">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><i class="fa fa-users me-2"></i>Total de Empleados</h5>
                            <p class="display-6 fw-bold">
                                <?= isset($empleados) && is_array($empleados) ? count($empleados) : 0 ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow border-success border-2">
                        <div class="card-body">
                            <h5 class="card-title text-success"><i class="fa fa-clock me-2"></i>Total de Horas</h5>
                            <p class="display-6 fw-bold">
                                <?php 
                                $total_horas = 0;
                                if (!empty($empleados)) {
                                    foreach ($empleados as $e) {
                                        $total_horas += isset($e['total_horas']) ? floatval($e['total_horas']) : 0;
                                    }
                                }
                                echo number_format($total_horas, 1) . ' hrs';
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow border-warning border-2">
                        <div class="card-body">
                            <h5 class="card-title text-warning"><i class="fa fa-dollar-sign me-2"></i>Valor Total</h5>
                            <p class="display-6 fw-bold">
                                <?php 
                                $total_valor = 0;
                                if (!empty($empleados)) {
                                    foreach ($empleados as $e) {
                                        $total_valor += isset($e['total_valor']) ? floatval($e['total_valor']) : 0;
                                    }
                                }
                                echo '$' . number_format($total_valor, 0, ',', '.');
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow border-info border-2">
                        <div class="card-body">
                            <h5 class="card-title">Anio Actual</h5>
                            <p class="display-6 fw-bold">
                                <?= date('Y') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <a href="/ZIGMA/public/index.php?url=Dashboard/index" class="btn btn-outline-primary">
                    <i class="fa fa-home"></i> Volver al menú
                </a>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h4 class="mb-0">Gestión de Empleados para Horas Extras</h4>
                        <div>
                            <a href="/ZIGMA/public/index.php?url=HorasExtras/create" class="btn btn-success">
                                <i class="fa fa-plus"></i> Agregar Horas Extras
                            </a>
                        </div>
                    </div>
                    <form class="row g-2 align-items-center mb-3" method="get" action="/ZIGMA/public/index.php">
                        <input type="hidden" name="url" value="HorasExtras">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
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
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                        </div>
                        <div class="col-auto">
                            <a href="/ZIGMA/public/index.php?url=HorasExtras" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Rol</th>
                                    <th>Cantidad de Horas</th>
                                    <th>Valor Total</th>
                                    <th>Tipo de Horas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Debug temporal
                                echo "<!-- DEBUG: empleados variable: " . var_export($empleados, true) . " -->";
                                
                                if (isset($empleados) && is_array($empleados) && !empty($empleados)): ?>
                                    <?php foreach ($empleados as $emp): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($emp['id_empleados'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($emp['nombre'] ?? '') ?></td>
                                            <td><?= htmlspecialchars(($emp['apellidos'] ?? null) !== null ? $emp['apellidos'] : ($emp['apellido'] ?? '')) ?></td>
                                            <td>
                                                <?php 
                                                $rol_badge_class = '';
                                                $rol_display = '';
                                                $todos_roles = isset($emp['todos_los_roles']) ? $emp['todos_los_roles'] : '';
                                                $emp_rol = $emp['rol'] ?? '';
                                                
                                                switch($emp_rol) {
                                                    case 'admin': 
                                                        $rol_badge_class = 'bg-danger'; 
                                                        $rol_display = 'Admin';
                                                        break;
                                                    case 'rrhh': 
                                                        $rol_badge_class = 'bg-warning text-dark'; 
                                                        $rol_display = 'RRHH';
                                                        break;
                                                    case 'empleado': 
                                                        $rol_badge_class = 'bg-primary'; 
                                                        $rol_display = 'Empleado';
                                                        break;
                                                    default: 
                                                        $rol_badge_class = 'bg-secondary'; 
                                                        $rol_display = 'Sin rol';
                                                        break;
                                                }
                                                
                                                // Agregar tooltip con todos los roles si tiene múltiples
                                                $tooltip = '';
                                                if ($todos_roles && strpos($todos_roles, ',') !== false) {
                                                    $tooltip = 'title="Roles: ' . htmlspecialchars(str_replace(',', ', ', $todos_roles)) . '" data-bs-toggle="tooltip"';
                                                }
                                                ?>
                                                <span class="badge <?= $rol_badge_class ?>" <?= $tooltip ?>><?= htmlspecialchars($rol_display) ?></span>
                                                <?php if ($todos_roles && strpos($todos_roles, ',') !== false): ?>
                                                    <small class="text-muted ms-1">
                                                        <i class="fa fa-info-circle" title="Tiene múltiples roles"></i>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ((float)($emp['total_horas'] ?? 0) > 0): ?>
                                                    <span class="badge bg-success"><?= htmlspecialchars($emp['total_horas']) ?> horas</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">0 horas</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php 
                                                $total_valor_emp = isset($emp['total_valor']) ? floatval($emp['total_valor']) : 0;
                                                if ($total_valor_emp > 0): ?>
                                                    <strong class="text-success">$<?= number_format($total_valor_emp, 0, ',', '.') ?></strong>
                                                <?php else: ?>
                                                    <span class="text-muted">$0</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php $tipo_frec = $emp['tipo_frecuente'] ?? 'N/A'; ?>
                                                <?php if ($tipo_frec !== 'N/A'): ?>
                                                    <span class="badge bg-info"><?= htmlspecialchars($tipo_frec) ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Sin tipo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php $emp_id = $emp['id_empleados'] ?? ''; ?>
                                                <div class="btn-group" role="group">
                                                    <a href="/ZIGMA/public/index.php?url=HorasExtras/detalle/<?= urlencode($emp_id) ?>" 
                                                       class="btn btn-primary btn-sm" title="Ver detalle de horas extras">
                                                        <i class="fa fa-eye"></i> Ver Detalle
                                                    </a>
                                                    <a href="/ZIGMA/public/index.php?url=HorasExtras/create&empleado_id=<?= urlencode($emp_id) ?>" 
                                                       class="btn btn-success btn-sm" title="Agregar nuevas horas extras">
                                                        <i class="fa fa-plus"></i> Agregar
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
