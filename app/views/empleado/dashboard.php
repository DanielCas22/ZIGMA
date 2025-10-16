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
                <div class="col-md-4">
                    <div class="card text-center shadow border-primary border-2">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><i class="fa fa-users me-2"></i>Total de Empleados</h5>
                            <p class="display-6 fw-bold">
                                <?= isset($empleados) ? count($empleados) : 0 ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
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
                <div class="col-md-4">
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
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Gestión de Empleados</h4>
                        <a href="index.php?url=Empleado/create" class="btn btn-info">
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
                                    <th>Rol/Cargo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($empleados)): ?>
                                    <?php foreach ($empleados as $emp): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($emp['id_empleados']) ?></td>
                                            <td><?= htmlspecialchars($emp['nombre']) ?></td>
                                            <td><?= htmlspecialchars(($emp['apellidos'] ?? null) !== null ? $emp['apellidos'] : ($emp['apellido'] ?? '')) ?></td>
                                            <td>
                                                <?php
                                                $rol_nombre = strtolower($emp['rol'] ?? 'Sin rol');
                                                $badge_class = 'badge-role-default';
                                                if (strpos($rol_nombre, 'admin') !== false) {
                                                    $badge_class = 'badge-role-admin';
                                                } elseif (strpos($rol_nombre, 'rrhh') !== false || strpos($rol_nombre, 'recursos humanos') !== false) {
                                                    $badge_class = 'badge-role-rrhh';
                                                } elseif (strpos($rol_nombre, 'empleado') !== false) {
                                                    $badge_class = 'badge-role-empleado';
                                                }
                                                ?>
                                                <span class="badge <?= $badge_class ?>"><?= htmlspecialchars(isset($emp['rol']) && $emp['rol'] !== '' ? $emp['rol'] : 'Sin rol') ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="text-center">No hay empleados registrados.</td></tr>
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
</body>
</html>