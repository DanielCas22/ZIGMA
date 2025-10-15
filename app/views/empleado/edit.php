<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
<?php $pageTitle = "Editar Empleado"; ?>
<?php include __DIR__ . '/../components/navbar.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 fade-in-up">
            <div class="card-zigma shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <span class="d-inline-block bg-gradient-zigma text-white rounded-circle p-3 mb-2 pulse">
                            <i class="fa fa-user-edit fa-2x"></i>
                        </span>
                        <h2 class="mb-0 text-zigma-secondary fw-bold">Editar Empleado</h2>
                        <p class="text-muted">Modifica los datos del empleado <?= htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido']) ?></p>
                    </div>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert-zigma-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php if ($_GET['error'] === 'update'): ?>
                                Error al actualizar los datos del empleado. Inténtelo nuevamente.
                            <?php else: ?>
                                Ha ocurrido un error inesperado.
                            <?php endif; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/ZIGMA/public/index.php?url=Empleado/update">
                        <input type="hidden" name="id" value="<?= $empleado['id_empleados'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Nombres</label>
                            <input type="text" name="nombres" class="form-control" 
                                   value="<?= htmlspecialchars($empleado['nombre']) ?>" 
                                   placeholder="Ej: Juan Carlos" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" value="<?= htmlspecialchars($empleado['apellido']) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Rol o Cargo</label>
                            <select name="rol" class="form-select" required id="rolSelect" onchange="actualizarSueldo()">
                                <option value="">Seleccione un rol</option>
                                <option value="empleado" data-sueldo="1423000" 
                                    <?= (isset($empleadoConRol['rol_principal']) && $empleadoConRol['rol_principal'] === 'empleado') ? 'selected' : '' ?>>
                                    Empleado
                                </option>
                                <option value="rrhh" data-sueldo="2000000" 
                                    <?= (isset($empleadoConRol['rol_principal']) && $empleadoConRol['rol_principal'] === 'rrhh') ? 'selected' : '' ?>>
                                    RRHH
                                </option>
                                <option value="admin" data-sueldo="4000000" 
                                    <?= (isset($empleadoConRol['rol_principal']) && $empleadoConRol['rol_principal'] === 'admin') ? 'selected' : '' ?>>
                                    Admin
                                </option>
                            </select>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Roles actuales: <?= isset($empleadoConRol['todos_los_roles']) ? htmlspecialchars($empleadoConRol['todos_los_roles']) : 'Sin roles asignados' ?>
                                </small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Sueldo Actual</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <input type="number" name="sueldo_actual" id="sueldoInput" class="form-control" 
                                       value="<?= $empleado['sueldo_actual'] ?>" 
                                       placeholder="Ingrese un sueldo" min="1" max="100000000" step="1">
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
                                <option value="1" <?= (isset($empleado['riesgo_arl']) && $empleado['riesgo_arl'] == 1) ? 'selected' : '' ?>>
                                    Clase I - Mínimo (0.522%)
                                </option>
                                <option value="2" <?= (isset($empleado['riesgo_arl']) && $empleado['riesgo_arl'] == 2) ? 'selected' : (!isset($empleado['riesgo_arl']) ? 'selected' : '') ?>>
                                    Clase II - Bajo (1.044%) <?= !isset($empleado['riesgo_arl']) ? '- Por defecto' : '' ?>
                                </option>
                                <option value="3" <?= (isset($empleado['riesgo_arl']) && $empleado['riesgo_arl'] == 3) ? 'selected' : '' ?>>
                                    Clase III - Medio (2.436%)
                                </option>
                                <option value="4" <?= (isset($empleado['riesgo_arl']) && $empleado['riesgo_arl'] == 4) ? 'selected' : '' ?>>
                                    Clase IV - Alto (4.350%)
                                </option>
                                <option value="5" <?= (isset($empleado['riesgo_arl']) && $empleado['riesgo_arl'] == 5) ? 'selected' : '' ?>>
                                    Clase V - Máximo (6.960%)
                                </option>
                            </select>
                            <div class="form-text">
                                <i class="fas fa-shield-alt text-warning me-1"></i>
                                Nivel actual: <?= isset($empleado['riesgo_arl']) ? 'Clase ' . ['I', 'II', 'III', 'IV', 'V'][$empleado['riesgo_arl'] - 1] : 'Sin asignar (se usará Clase II por defecto)' ?>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn-zigma-warning px-4">
                                <i class="fas fa-save me-2"></i>Actualizar
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-actualización del sueldo cuando cambia el rol
function actualizarSueldo() {
    const rolSelect = document.getElementById('rolSelect');
    const sueldoInput = document.getElementById('sueldoInput');
    
    if (rolSelect.value) {
        // Cambiar el sueldo inmediatamente al seleccionar un rol
        const selectedOption = rolSelect.options[rolSelect.selectedIndex];
        const sueldo = selectedOption.getAttribute('data-sueldo');
        sueldoInput.value = sueldo;
        sueldoInput.classList.add('text-warning', 'fw-bold');
        
        // Mostrar mensaje de confirmación
        mostrarMensaje(`Sueldo actualizado: $${Number(sueldo).toLocaleString('es-CO')}`, 'success');
    } else {
        // Si no hay rol seleccionado, limpiar el campo
        sueldoInput.value = '';
        sueldoInput.classList.remove('text-warning', 'fw-bold');
    }
}

// Función para auto-asignar sueldo según rol seleccionado
function autoAsignarSueldo() {
    const rolSelect = document.getElementById('rolSelect');
    const sueldoInput = document.getElementById('sueldoInput');
    
    const selectedOption = rolSelect.options[rolSelect.selectedIndex];
    
    if (selectedOption.value) {
        const sueldo = selectedOption.getAttribute('data-sueldo');
        sueldoInput.value = sueldo;
        sueldoInput.classList.add('text-warning', 'fw-bold');
        
        // Mostrar mensaje de confirmación
        mostrarMensaje('Sueldo asignado automáticamente según el rol', 'success');
    } else {
        mostrarMensaje('Seleccione un rol primero', 'warning');
    }
}

// Función para mostrar mensajes temporales
function mostrarMensaje(mensaje, tipo) {
    // Crear elemento de mensaje
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Agregar al body
    document.body.appendChild(alertDiv);
    
    // Auto-remover después de 3 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}

// Formatear sueldo mientras se escribe
document.addEventListener('DOMContentLoaded', function() {
    const sueldoInput = document.getElementById('sueldoInput');
    
    sueldoInput.addEventListener('input', function() {
        // Remover clases de auto-asignación si el usuario modifica manualmente
        this.classList.remove('text-warning', 'fw-bold');
    });
});
</script>
</body>
</html>