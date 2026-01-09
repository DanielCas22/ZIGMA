<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Empleado - <?= htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
    <style>
        .employee-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .info-card {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .stat-card {
            text-align: center;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header del empleado -->
            <div class="employee-header text-center">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <i class="fas fa-user-circle fa-5x opacity-75"></i>
                    </div>
                    <div class="col-md-8">
                        <h1 class="mb-2">
                            <i class="fas fa-user me-2"></i>
                            <?= htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido']) ?>
                        </h1>
                        <p class="lead mb-1">ID: <?= $empleado['id_empleados'] ?></p>
                        <?php
                        $rol_principal = $empleado['rol_principal'] ?? 'Sin rol';
                        $rol_display = '';
                        $badge_class = 'badge-role-default';
                        
                        switch($rol_principal) {
                            case 'admin': 
                                $rol_display = 'Administrador'; 
                                $badge_class = 'badge-role-admin';
                                break;
                            case 'rrhh': 
                                $rol_display = 'Recursos Humanos'; 
                                $badge_class = 'badge-role-rrhh';
                                break;
                            case 'empleado': 
                                $rol_display = 'Empleado'; 
                                $badge_class = 'badge-role-empleado';
                                break;
                            default: 
                                $rol_display = 'Sin rol'; 
                                $badge_class = 'badge-role-default';
                                break;
                        }
                        ?>
                        <span class="<?= $badge_class ?> fs-6">
                            <?= htmlspecialchars($rol_display) ?>
                        </span>
                    </div>
                    <div class="col-md-2">
                        <a href="/ZIGMA/public/index.php?url=Empleado/index" class="btn btn-light">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Información Personal -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información Personal</h5>
                        </div>
                        <div class="card-body">
                            <div class="info-card">
                                <h6><i class="fas fa-user me-2"></i>Nombres</h6>
                                <p class="mb-0"><?= htmlspecialchars($empleado['nombre']) ?></p>
                            </div>
                            <div class="info-card">
                                <h6><i class="fas fa-user me-2"></i>Apellidos</h6>
                                <p class="mb-0"><?= htmlspecialchars($empleado['apellido']) ?></p>
                            </div>
                            <div class="info-card">
                                <h6><i class="fas fa-dollar-sign me-2"></i>Salario Base</h6>
                                <p class="mb-0 text-success fw-bold">
                                    $<?= number_format(isset($empleado['sueldo_actual']) ? floatval($empleado['sueldo_actual']) : 0, 0, ',', '.') ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Roles y Permisos -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fas fa-user-tag me-2"></i>Roles y Permisos</h5>
                        </div>
                        <div class="card-body">
                            <div class="info-card">
                                <h6><i class="fas fa-crown me-2"></i>Rol Principal</h6>
                                <p class="mb-0"><?= htmlspecialchars($rol_display) ?></p>
                            </div>
                            <?php if (isset($empleado['todos_los_roles']) && $empleado['todos_los_roles']): ?>
                            <div class="info-card">
                                <h6><i class="fas fa-list me-2"></i>Todos los Roles</h6>
                                <p class="mb-0"><?= htmlspecialchars(str_replace(',', ', ', $empleado['todos_los_roles'])) ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (isset($empleado['username'])): ?>
                            <div class="info-card">
                                <h6><i class="fas fa-user-lock me-2"></i>Usuario del Sistema</h6>
                                <p class="mb-0"><?= htmlspecialchars($empleado['username']) ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de Horas Extras -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card stat-card border-info">
                        <div class="card-body">
                            <h3 class="text-info"><?= number_format($total_horas_extras, 1) ?></h3>
                            <p class="mb-0">
                                <i class="fas fa-clock me-1"></i>
                                Horas Extras Totales
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card stat-card border-success">
                        <div class="card-body">
                            <h3 class="text-success">$<?= number_format($total_valor_extras, 0, ',', '.') ?></h3>
                            <p class="mb-0">
                                <i class="fas fa-dollar-sign me-1"></i>
                                Valor Horas Extras
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horas Extras Registradas -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Horas Extras Registradas</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($horasExtras)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Tipo</th>
                                        <th>Cantidad</th>
                                        <th>Valor</th>
                                        <th>Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($horasExtras as $he): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($he['fecha'] ?? date('Y-m-d'))) ?></td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?= htmlspecialchars($he['tipo'] ?? 'N/A') ?>
                                            </span>
                                        </td>
                                        <td><?= number_format($he['cantidad'] ?? 0, 1) ?>h</td>
                                        <td class="text-success fw-bold">
                                            $<?= number_format($he['valor'] ?? 0, 0, ',', '.') ?>
                                        </td>
                                        <td><?= htmlspecialchars($he['descripcion'] ?? 'Sin descripción') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Sin horas extras registradas</h5>
                            <p class="text-muted">Este empleado no tiene horas extras registradas en el sistema.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Acciones -->
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="/ZIGMA/public/index.php?url=Empleado/edit&id=<?= $empleado['id_empleados'] ?>" 
                   class="btn btn-warning">
                    <i class="fas fa-edit me-1"></i>Editar Empleado
                </a>
                <a href="/ZIGMA/public/index.php?url=HorasExtras/create" 
                   class="btn btn-success">
                    <i class="fas fa-clock me-1"></i>Registrar Horas Extras
                </a>
                <a href="/ZIGMA/public/index.php?url=Empleado/index" 
                   class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Volver al Listado
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php if (in_array($empleado['id_empleados'], [1,2,3])): ?>
    <script>window.location.href='/ZIGMA/public/index.php?url=Empleado/index';</script>
<?php endif; ?>
</body>
</html>