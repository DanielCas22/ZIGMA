<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
    <style>
        @media (max-width: 576px) {
            .btn, .btn-zigma-info, .btn-zigma-danger, .btn-zigma-success, .btn-zigma-secondary {
                font-size: clamp(12px, 3vw, 14px) !important;
                padding: 6px 12px !important;
                min-width: 80px;
                max-width: 140px;
            }
            .table-zigma th, .table-zigma td, .table th, .table td {
                font-size: clamp(11px, 3vw, 13px) !important;
                padding: 6px !important;
            }
            .display-6, h2, h1, h4 {
                font-size: clamp(1rem, 4vw, 1.3rem) !important;
            }
        }
        @media (min-width: 577px) {
            .btn, .btn-zigma-info, .btn-zigma-danger {
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
                                <?php 
                                // Filtrar empleados válidos (excluyendo IDs especiales)
                                $empleadosValidos = array_filter($empleados ?? [], function($e) {
                                    return !in_array($e['id_empleados'], [1,2,3]);
                                });
                                echo count($empleadosValidos);
                                ?>
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
                                if (!empty($empleadosValidos)) {
                                    foreach ($empleadosValidos as $e) {
                                        // Usar sueldo_actual si existe, si no salario
                                        $salario = isset($e['sueldo_actual']) ? floatval($e['sueldo_actual']) : (isset($e['salario']) ? floatval($e['salario']) : 0);
                                        $total_salarios += $salario;
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
                                if (!empty($empleadosValidos)) {
                                    foreach ($empleadosValidos as $e) {
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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0 text-zigma-navy">
                                <i class="fa fa-users me-2"></i>Lista de Empleados
                            </h4>
                            <form class="row g-2 align-items-center mb-0" method="get" action="/ZIGMA/public/index.php">
                                <input type="hidden" name="url" value="Empleado/index">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-zigma-gradient text-white">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" name="filtro_nombre" class="form-control" 
                                               placeholder="Buscar por nombre del empleado..." 
                                               value="<?= isset($_GET['filtro_nombre']) ? htmlspecialchars($_GET['filtro_nombre']) : '' ?>">
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-zigma-primary">
                                        <i class="fas fa-filter me-1"></i> Filtrar
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <a href="/ZIGMA/public/index.php?url=Empleado/index" class="btn btn-zigma-secondary">
                                        <i class="fas fa-times me-1"></i> Limpiar
                                    </a>
                                </div>
                            </form>
                            <a href="/ZIGMA/public/index.php?url=Empleado/create" class="btn-zigma-primary ms-2">
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
                                <?php 
                                $empleadosFiltrados = $empleados;
                                if (!empty($_GET['filtro_nombre'])) {
                                    $filtro = mb_strtolower(trim($_GET['filtro_nombre']));
                                    $empleadosFiltrados = array_filter($empleadosFiltrados, function($emp) use ($filtro) {
                                        return strpos(mb_strtolower($emp['nombre']), $filtro) !== false
                                            || strpos(mb_strtolower($emp['apellido']), $filtro) !== false;
                                    });
                                }
                                ?>
                                <?php if (!empty($empleadosFiltrados)): ?>
                                    <?php foreach ($empleadosFiltrados as $emp): ?>
                                        <?php if (in_array($emp['id_empleados'], [1,2,3])) continue; ?>
                                        <tr>
                                            <td><?= $emp['id_empleados'] ?></td>
                                            <td><?= $emp['nombre'] ?></td>
                                            <td><?= htmlspecialchars($emp['apellido']) ?></td>
                                            <td>
                                                <strong class="text-success">
                                                    $<?= number_format(isset($emp['sueldo_actual']) ? floatval($emp['sueldo_actual']) : 0, 0, ',', '.') ?>
                                                </strong>
                                            </td>
                                            <td><?= htmlspecialchars($emp['rol']) ?></td>
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
                                                    <?php if ($emp['canDelete']): ?>
                                                    <a href="/ZIGMA/public/index.php?url=Empleado/delete&id=<?= $emp['id_empleados'] ?>" 
                                                       class="btn-zigma-danger btn-sm" 
                                                       onclick="return confirm('⚠️ ATENCIÓN: Esta acción eliminará:\n\n• El empleado y su información\n• Su usuario y credenciales de acceso\n• Todos sus roles asignados\n• Todas sus horas extras registradas\n\n¿Está seguro de continuar? Esta acción NO se puede deshacer.');"
                                                       title="Eliminar empleado">
                                                        <i class="fa fa-trash"></i> Eliminar
                                                    </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="text-center text-muted">No se encontraron empleados con ese nombre.</td></tr>
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
