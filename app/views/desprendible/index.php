<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?> - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<?php $pageTitle = "Desprendible de Pago"; ?>
<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container-fluid py-4">
    <div class="row justify-content-center fade-in-up">
        <div class="col-12 col-md-8">
            <div class="card-zigma shadow-lg">
                <div class="card-body p-4">
                    <h5 class="mb-4 text-zigma-navy">
                        <i class="fas fa-users me-2"></i>
                        Seleccionar Empleado
                    </h5>
                    <form id="formSeleccionEmpleado" method="GET" action="/ZIGMA/public/index.php?url=Desprendible/mostrar">
                        <div class="row g-2">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="empleado_id" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Empleado
                                </label>
                                <select class="form-select" id="empleado_id" name="empleado_id" required>
                                    <option value="">Seleccione un empleado</option>
                                    <?php foreach($data['empleados'] as $empleado): ?>
                                        <option value="<?= $empleado['id_empleados'] ?? $empleado['id'] ?>">
                                            <?= $empleado['id_doc'] ?? ($empleado['id_empleados'] ?? 'N/A') ?> - <?= ($empleado['nombre'] ?? '') . ' ' . ($empleado['apellido'] ?? '') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-3 mb-3">
                                <label for="mes" class="form-label">Mes</label>
                                <select class="form-select" id="mes" name="mes" required>
                                    <option value="">Mes actual</option>
                                    <option value="01">Enero</option>
                                    <option value="02">Febrero</option>
                                    <option value="03">Marzo</option>
                                    <option value="04">Abril</option>
                                    <option value="05">Mayo</option>
                                    <option value="06">Junio</option>
                                    <option value="07">Julio</option>
                                    <option value="08">Agosto</option>
                                    <option value="09" selected>Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-3 mb-3">
                                <label for="anio" class="form-label">Año</label>
                                <select class="form-select" id="anio" name="anio" required>
                                    <option value="">Año actual</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025" selected>2025</option>
                                    <option value="2026">2026</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-2">
                            <button type="submit" class="btn-zigma-success px-4 w-100 w-md-auto">
                                <i class="fas fa-search me-2"></i> Consultar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card-zigma shadow">
                <div class="card-body">
                    <h6 class="mb-3 text-zigma-navy">
                        <i class="fas fa-list me-2"></i>
                        Empleados Registrados
                    </h6>
                    <div class="table-responsive">
                        <table class="table-zigma table table-striped table-hover align-middle">
                            <thead class="table-zigma-thead">
                                <tr>
                                    <th>Documento</th>
                                    <th>Nombre Completo</th>
                                    <th>Cargo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($data['empleados'])): ?>
                                    <?php foreach($data['empleados'] as $empleado): ?>
                                        <tr>
                                            <td><?= $empleado['id_doc'] ?? $empleado['id_empleados'] ?? 'N/A' ?></td>
                                            <td><?= ($empleado['nombre'] ?? '') . ' ' . ($empleado['apellido'] ?? '') ?></td>
                                            <td>
                                                <?php 
                                                $rol_nombre = strtolower($empleado['rol_nombre'] ?? $empleado['cargo'] ?? 'Sin rol');
                                                $badge_class = 'badge-role-default';
                                                $display_name = 'No definido';
                                                
                                                if (strpos($rol_nombre, 'admin') !== false) {
                                                    $badge_class = 'badge-role-admin';
                                                    $display_name = 'Admin';
                                                } elseif (strpos($rol_nombre, 'rrhh') !== false) {
                                                    $badge_class = 'badge-role-rrhh';
                                                    $display_name = 'RRHH';
                                                } elseif (strpos($rol_nombre, 'empleado') !== false) {
                                                    $badge_class = 'badge-role-empleado';
                                                    $display_name = 'Empleado';
                                                }
                                                ?>
                                                <span class="<?= $badge_class ?>"><?= $display_name ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= URL_ROOT ?>=Desprendible/mostrar/<?= $empleado['id_empleados'] ?? $empleado['id'] ?>" 
                                                   class="btn-zigma-info btn-sm" 
                                                   title="Ver desprendible actual">
                                                    <i class="fas fa-file-invoice me-1"></i> Ver
                                                </a>
                                                <?php if (in_array(RolePermissions::getCurrentUserRole(), ['admin', 'rrhh'])): ?>
                                                    <form method="POST" action="<?= URL_ROOT ?>=Desprendible/eliminar/<?= $empleado['id_empleados'] ?? $empleado['id'] ?>" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar el desprendible?');">
                                                        <button type="submit" class="btn-zigma-danger btn-sm" title="Eliminar desprendible">
                                                            <i class="fas fa-trash-alt me-1"></i> Eliminar
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="fas fa-users fa-2x mb-2"></i><br>
                                            No hay empleados registrados
                                        </td>
                                    </tr>
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
    // Auto-seleccionar mes y año actual
    document.addEventListener('DOMContentLoaded', function() {
        const fechaActual = new Date();
        const mesActual = String(fechaActual.getMonth() + 1).padStart(2, '0');
        const anioActual = fechaActual.getFullYear();
        
        document.getElementById('mes').value = mesActual;
        document.getElementById('anio').value = anioActual;
    });
</script>

<style>
    @media (max-width: 576px) {
        .btn, .btn-zigma-success, .btn-zigma-secondary, .btn-zigma-info, .btn-zigma-danger {
            font-size: clamp(12px, 3vw, 14px) !important;
            padding: 6px 12px !important;
            min-width: 80px;
            max-width: 140px;
        }
        .table-zigma th, .table-zigma td { font-size: clamp(11px, 3vw, 13px) !important; padding: 6px !important; }
        .page-header-zigma h2 { font-size: clamp(1rem, 4vw, 1.2rem) !important; }
    }
    @media (min-width: 577px) {
        .btn, .btn-zigma-success, .btn-zigma-secondary, .btn-zigma-info, .btn-zigma-danger {
            font-size: 15px !important;
            padding: 8px 18px !important;
            min-width: 100px;
            max-width: 180px;
        }
    }
</style>
</body>
</html>