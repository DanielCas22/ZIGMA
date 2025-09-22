<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="mb-4 text-primary"><i class="fa fa-users me-2"></i>Empleados</h2>
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center shadow border-primary border-2">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><i class="fa fa-users me-2"></i>Total de Empleados</h5>
                            <p class="display-6 fw-bold">
                                <?= isset($empleados) ? count($empleados) : 0 ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center shadow border-warning border-2">
                        <div class="card-body">
                            <h5 class="card-title text-warning"><i class="fa fa-dollar-sign me-2"></i>Total Salarios</h5>
                            <p class="display-6 fw-bold">
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
                    <div class="card text-center shadow border-success border-2">
                        <div class="card-body">
                            <h5 class="card-title text-success"><i class="fa fa-briefcase me-2"></i>Roles/Cargos Distintos</h5>
                            <p class="display-6 fw-bold">
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
                    <div class="card text-center shadow border-info border-2">
                        <div class="card-body">
                            <h5 class="card-title text-info"><i class="fa fa-calendar me-2"></i>Año Actual</h5>
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

            <!-- Mensajes de éxito/error -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
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

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Gestión de Empleados</h4>
                        <a href="/ZIGMA/public/index.php?url=Empleado/create" class="btn btn-info">
                            <i class="fa fa-user-plus"></i> Registrar Empleado
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Salario Base</th>
                                    <th>Rol/Cargo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($empleados)): ?>
                                    <?php foreach ($empleados as $emp): ?>
                                        <tr>
                                            <td><?= $emp['id_empleados'] ?></td>
                                            <td><?= $emp['nombre'] ?></td>
<<<<<<< HEAD
                                            <td><?= $emp['apellidos'] ?></td>
                                            <td>
                                                <strong class="text-success">
                                                    $<?= number_format(isset($emp['salario']) ? floatval($emp['salario']) : 0, 0, ',', '.') ?>
                                                </strong>
                                                <a href="#" onclick="editarSalario(<?= $emp['id_empleados'] ?>, '<?= htmlspecialchars($emp['nombre'] . ' ' . $emp['apellidos']) ?>', <?= isset($emp['salario']) ? floatval($emp['salario']) : 0 ?>)" 
                                                   class="btn btn-sm btn-outline-primary ms-2" title="Editar salario">
                                                    <i class="fas fa-edit fa-xs"></i>
                                                </a>
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
                                                <div class="btn-group" role="group">
                                                    <a href="/ZIGMA/public/index.php?url=Empleado/edit&id=<?= $emp['id_empleados'] ?>" 
                                                       class="btn btn-sm btn-warning" title="Editar empleado">
                                                        <i class="fa fa-edit"></i> Editar
                                                    </a>
                                                    <a href="/ZIGMA/public/index.php?url=Empleado/delete&id=<?= $emp['id_empleados'] ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       onclick="return confirm('⚠️ ATENCIÓN: Esta acción eliminará:\n\n• El empleado y su información\n• Su usuario y credenciales de acceso\n• Todos sus roles asignados\n• Todas sus horas extras registradas\n\n¿Está seguro de continuar? Esta acción NO se puede deshacer.');"
                                                       title="Eliminar empleado">
                                                        <i class="fa fa-trash"></i> Eliminar
                                                    </a>
                                                </div>
                                            </td>
=======
                                            <td><?= $emp['apellido'] ?></td>
                                            <td><?= isset($emp['rol']) && $emp['rol'] !== '' ? $emp['rol'] : 'Sin rol' ?></td>
>>>>>>> b0a855acd50c315b25d869c7085857d8076febc4
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

<!-- Modal para editar salario -->
<div class="modal fade" id="modalEditarSalario" tabindex="-1" aria-labelledby="modalEditarSalarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarSalarioLabel">
                    <i class="fas fa-dollar-sign me-2"></i>Editar Salario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarSalario" method="post" action="/ZIGMA/public/index.php?url=Empleado/updateSalario">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Empleado:</label>
                        <p id="nombreEmpleado" class="text-muted"></p>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoSalario" class="form-label">Nuevo Salario Base</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-dollar-sign"></i>
                            </span>
                            <input type="number" id="nuevoSalario" name="salario" class="form-control" min="0" step="1000" required>
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle text-info me-1"></i>
                            Ingrese el nuevo salario base mensual en pesos colombianos
                        </div>
                    </div>
                    <input type="hidden" id="empleadoId" name="empleado_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i>Guardar Cambios
                    </button>
                </div>
            </form>
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

// Función para editar salario
function editarSalario(empleadoId, nombreCompleto, salarioActual) {
    document.getElementById('empleadoId').value = empleadoId;
    document.getElementById('nombreEmpleado').textContent = nombreCompleto;
    document.getElementById('nuevoSalario').value = salarioActual;
    
    var modal = new bootstrap.Modal(document.getElementById('modalEditarSalario'));
    modal.show();
}
</script>
</body>
</html>
