<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Empleado - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
    <style>
        @media (max-width: 576px) {
            .btn, .btn-zigma-success, .btn-zigma-secondary {
                font-size: clamp(12px, 3vw, 14px) !important;
                padding: 6px 12px !important;
                min-width: 80px;
                max-width: 140px;
            }
            .form-label, .form-control, .form-select { font-size: clamp(12px, 3vw, 13px) !important; }
            h2 { font-size: clamp(1rem, 4vw, 1.2rem) !important; }
        }
        @media (min-width: 577px) {
            .btn, .btn-zigma-success, .btn-zigma-secondary {
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
<?php $pageTitle = "Registrar Empleado"; ?>
<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 fade-in-up">
            <div class="card-zigma shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <span class="d-inline-block bg-gradient-zigma text-white rounded-circle p-3 mb-2 pulse">
                            <i class="fa fa-user-plus fa-2x"></i>
                        </span>
                        <h2 class="mb-0 text-zigma-secondary fw-bold">Registrar Empleado</h2>
                        <p class="text-muted">Complete los datos para crear un nuevo empleado y usuario asociado</p>
                    </div>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert-zigma-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php if ($_GET['error'] === 'usuario'): ?>
                                El usuario ya existe. Por favor elija otro nombre de usuario.
                            <?php elseif ($_GET['error'] === 'registro'): ?>
                                Error al registrar el empleado. Inténtelo nuevamente.
                            <?php else: ?>
                                Ha ocurrido un error inesperado.
                            <?php endif; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/ZIGMA/public/index.php?url=Empleado/store">
                        <div class="mb-3">
                            <label class="form-label">Nombres</label>
                            <input type="text" name="nombres" class="form-control" placeholder="Ej: Juan Carlos" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ej: Pérez" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Usuario <span class="text-danger">*</span></label>
                            <input type="text" name="usuario" class="form-control" placeholder="Nombre de usuario único" required minlength="4" maxlength="32" autocomplete="username">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Contraseña segura" required minlength="6" maxlength="64" autocomplete="new-password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol o Cargo</label>
                            <select name="rol" class="form-select" required id="rolSelect" onchange="actualizarSueldo()">
                                <option value="">Seleccione un rol</option>
                                <option value="empleado" data-sueldo="1423000">Empleado</option>
                                <option value="rrhh" data-sueldo="2000000">RRHH</option>
                                <option value="admin" data-sueldo="4000000">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sueldo Inicial</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <input type="number" name="sueldo_actual" id="sueldoInput" class="form-control" placeholder="Ingrese un sueldo" min="1" max="100000000" step="1">
                                <button type="button" class="btn btn-outline-warning" onclick="autoAsignarSueldo()" title="Auto-asignar según rol">
                                    <i class="fas fa-magic"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info me-1"></i>
                                Puede modificar el sueldo manualmente o usar auto-asignación según el rol
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">🛡️ Nivel de Riesgo ARL</label>
                            <select name="riesgo_arl" class="form-select" required id="riesgoSelect">
                                <option value="">Seleccione el nivel de riesgo</option>
                                <option value="1">Clase I - Mínimo (0.522%)</option>
                                <option value="2" selected>Clase II - Bajo (1.044%) - Por defecto</option>
                                <option value="3">Clase III - Medio (2.436%)</option>
                                <option value="4">Clase IV - Alto (4.350%)</option>
                                <option value="5">Clase V - Máximo (6.960%)</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn-zigma-success px-4">
                                <i class="fas fa-user-plus me-2"></i>Registrar
                            </button>
                            <a href="/ZIGMA/public/index.php?url=Empleado/index" class="btn-zigma-secondary px-4">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function autoAsignarSueldo() {
    var rolSelect = document.getElementById('rolSelect');
    var sueldoInput = document.getElementById('sueldoInput');
    var selected = rolSelect.options[rolSelect.selectedIndex];
    var sueldo = selected.getAttribute('data-sueldo');
    if (sueldo) {
        sueldoInput.value = sueldo;
    }
}
function actualizarSueldo() {
    autoAsignarSueldo();
}
</script>
</body>
</html>


