<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<?php $pageTitle = "Gestión de Empleados"; ?>
<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 fade-in-up">
            <div class="page-header-zigma mb-4">
                <h2><i class="fa fa-users me-2"></i>Gestión de Empleados</h2>
            </div>
            <div class="row mb-4 g-3">
                <div class="col-md-3">
                    <div class="card-zigma text-center shadow h-100">
                        <div class="card-body">
                            <h6 class="card-title text-zigma-primary mb-3">
                                <i class="fa fa-users me-2"></i>Total de Empleados
                            </h6>
                            <p class="display-6 fw-bold text-zigma-navy mb-0">
                                <?= isset($empleados) ? count($empleados) : 0 ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-zigma text-center shadow h-100">
                        <div class="card-body">
                            <h6 class="card-title text-zigma-secondary mb-3">
                                <i class="fa fa-dollar-sign me-2"></i>Total Salarios
                            </h6>
                            <p class="display-6 fw-bold text-zigma-navy mb-0">
                                <?php 
                                $total_salarios = 0;
                                if (!empty($empleados)) {
                                    foreach ($empleados as $e) {
                                        $total_salarios += isset($e['salario']) ? floatval($e['salario']) : 0;
                                    }
                                }
                                echo '$' . number_format($total_salarios, 0, ',', '.');
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-zigma text-center shadow h-100">
                        <div class="card-body">
                            <h6 class="card-title text-zigma-secondary mb-3">
                                <i class="fa fa-briefcase me-2"></i>Roles/Cargos
                            </h6>
                            <p class="display-6 fw-bold text-zigma-navy mb-0">
                                <?php 
                                $roles = [];
                                if (!empty($empleados)) {
                                    foreach ($empleados as $e) {
                                        $rol = isset($e['rol']) && $e['rol'] !== '' ? $e['rol'] : 'Sin rol';
                                        $roles[$rol] = true;
                                    }
                                }
                                echo count($roles);
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-zigma text-center shadow h-100">
                        <div class="card-body">
                            <h6 class="card-title text-zigma-primary mb-3">
                                <i class="fa fa-calendar me-2"></i>Año Actual
                            </h6>
                            <p class="display-6 fw-bold text-zigma-navy mb-0">
                                <?= date('Y') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensajes de éxito/error -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-zigma-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php if ($_GET['success'] === 'update'): ?>
                        Los datos del empleado han sido actualizados correctamente.
                    <?php elseif ($_GET['success'] === 'salario'): ?>
                        El salario del empleado ha sido actualizado correctamente.
                    <?php else: ?>
                        Operación completada exitosamente.
                    <?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php if ($_GET['error'] === 'notfound'): ?>
                        El empleado solicitado no fue encontrado.
                    <?php elseif ($_GET['error'] === 'salario'): ?>
                        Error al actualizar el salario del empleado.
                    <?php elseif ($_GET['error'] === 'update'): ?>
                        Error al actualizar los datos del empleado.
                    <?php else: ?>
                        Ha ocurrido un error inesperado. Inténtelo nuevamente.
                    <?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card-zigma shadow mb-4">
                <div class="card-body p-0">
                    <div class="p-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 text-zigma-navy">Lista de Empleados</h4>
                            <a href="/ZIGMA/public/index.php?url=Empleado/create" class="btn-zigma-primary">
                                <i class="fa fa-user-plus me-2"></i> Registrar Empleado
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table-zigma">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">ID</th>
                                    <th style="width: 20%;">Nombres</th>
                                    <th style="width: 20%;">Apellidos</th>
                                    <th style="width: 15%;">Salario Base</th>
                                    <th style="width: 15%;">Rol/Cargo</th>
                                    <th style="width: 25%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($empleados)): ?>
                                    <?php foreach ($empleados as $emp): ?>
                                        <tr>
                                            <td><?= $emp['id_empleados'] ?></td>
                                            <td><?= $emp['nombre'] ?></td>
                                            <td><?= htmlspecialchars($emp['apellido']) ?></td>
                                            <td>
                                                <strong class="text-success">
                                                    $<?= number_format(isset($emp['sueldo_actual']) ? floatval($emp['sueldo_actual']) : 0, 0, ',', '.') ?>
                                                </strong>
                                            </td>
                                            <td>
                                                <?php 
                                                $rol_principal = $emp['rol'] ?? 'Sin rol';
                                                $todos_roles = $emp['roles'] ?? '';
                                                
                                                // Determinar badge del rol principal
                                                $rol_badge_class = '';
                                                $rol_display = '';
                                                switch($rol_principal) {
                                                    case 'admin': 
                                                        $rol_badge_class = 'badge-role-admin'; 
                                                        $rol_display = 'Admin';
                                                        break;
                                                    case 'rrhh': 
                                                        $rol_badge_class = 'badge-role-rrhh'; 
                                                        $rol_display = 'RRHH';
                                                        break;
                                                    case 'empleado': 
                                                        $rol_badge_class = 'badge-role-empleado'; 
                                                        $rol_display = 'Empleado';
                                                        break;
                                                    default: 
                                                        $rol_badge_class = 'badge-role-default'; 
                                                        $rol_display = 'Sin rol';
                                                        break;
                                                }
                                                
                                                // Agregar tooltip con todos los roles si tiene múltiples
                                                $tooltip = '';
                                                if ($todos_roles && strpos($todos_roles, ',') !== false) {
                                                    $tooltip = 'title="Roles: ' . htmlspecialchars(str_replace(',', ', ', $todos_roles)) . '" data-bs-toggle="tooltip"';
                                                }
                                                ?>
                                                <span class="<?= $rol_badge_class ?>" <?= $tooltip ?>><?= htmlspecialchars($rol_display) ?></span>
                                                <?php if ($todos_roles && strpos($todos_roles, ',') !== false): ?>
                                                    <small class="text-muted ms-1">
                                                        <i class="fa fa-info-circle" title="Tiene múltiples roles"></i>
                                                    </small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="/ZIGMA/public/index.php?url=Empleado/detalle&id=<?= $emp['id_empleados'] ?>" 
                                                       class="btn-zigma-info btn-sm" title="Ver detalle del empleado">
                                                        <i class="fa fa-eye"></i> Detalle
                                                    </a>
                                                    <a href="/ZIGMA/public/index.php?url=Empleado/edit&id=<?= $emp['id_empleados'] ?>" 
                                                       class="btn-zigma-warning btn-sm" title="Editar empleado">
                                                        <i class="fa fa-edit"></i> Editar
                                                    </a>
                                                    <a href="/ZIGMA/public/index.php?url=Empleado/delete&id=<?= $emp['id_empleados'] ?>" 
                                                       class="btn-zigma-danger btn-sm" 
                                                       onclick="return confirm('⚠️ ATENCIÓN: Esta acción eliminará:\n\n• El empleado y su información\n• Su usuario y credenciales de acceso\n• Todos sus roles asignados\n• Todas sus horas extras registradas\n\n¿Está seguro de continuar? Esta acción NO se puede deshacer.');"
                                                       title="Eliminar empleado">
                                                        <i class="fa fa-trash"></i> Eliminar
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center">No hay empleados registrados.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Activar tooltips de Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
</body>
</html>
