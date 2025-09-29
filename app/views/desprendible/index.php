<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?> - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row bg-primary text-white py-3 mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">
                            <i class="fas fa-file-invoice me-2"></i>
                            <?= $data['title'] ?>
                        </h2>
                        <small class="opacity-75">Generar y consultar desprendibles de pago</small>
                    </div>
                    <a href="<?= URL_ROOT ?>=dashboard" class="btn btn-light">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Selección de Empleado -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Seleccionar Empleado
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="formSeleccionEmpleado" method="GET" action="<?= URL_ROOT ?>=Desprendible/mostrar">
                            <div class="row">
                                <div class="col-md-6 mb-3">
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
                                
                                <div class="col-md-3 mb-3">
                                    <label for="mes" class="form-label">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        Mes
                                    </label>
                                    <select class="form-select" id="mes" name="mes">
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
                                
                                <div class="col-md-3 mb-3">
                                    <label for="anio" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>
                                        Año
                                    </label>
                                    <select class="form-select" id="anio" name="anio">
                                        <option value="">Año actual</option>
                                        <option value="2023">2023</option>
                                        <option value="2024">2024</option>
                                        <option value="2025" selected>2025</option>
                                        <option value="2026">2026</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-file-invoice me-2"></i>
                                    Generar Desprendible
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Empleados -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Empleados Registrados
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
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
                                                    <span class="badge bg-primary"><?= $empleado['rol_nombre'] ?? $empleado['cargo'] ?? 'No definido' ?></span>
                                                </td>
                                                <td>
                                                    <a href="<?= URL_ROOT ?>=Desprendible/mostrar/<?= $empleado['id_empleados'] ?? $empleado['id'] ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Ver desprendible actual">
                                                        <i class="fas fa-file-invoice"></i>
                                                    </a>
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
</body>
</html>