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
                        Buscar Empleado
                    </h5>
                    <form id="formBusquedaNombre" onsubmit="return false;">
                        <div class="mb-3">
                            <label for="busqueda_nombre" class="form-label">
                                <i class="fas fa-search me-1"></i>
                                Buscar por nombre
                            </label>
                            <input type="text" class="form-control" id="busqueda_nombre" placeholder="Ingrese el nombre del empleado..." oninput="filtrarEmpleados(this.value)">
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
                                                <?php if (in_array($currentRole, ['admin', 'rrhh'])): ?>
                                                    <form method="POST" action="<?= URL_ROOT ?>=Desprendible/eliminar" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar el desprendible?');">
                                                        <input type="hidden" name="empleadoId" value="<?= $empleado['id_empleados'] ?? $empleado['id'] ?>">
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

<script>
function filtrarEmpleados(filtro) {
    const tabla = document.querySelector('.table-zigma tbody');
    const filas = tabla.querySelectorAll('tr');
    const filtroLower = filtro.toLowerCase();
    
    filas.forEach(fila => {
        const nombre = fila.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
        const documento = fila.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
        
        if (nombre.includes(filtroLower) || documento.includes(filtroLower)) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}
</script>
</body>
</html>