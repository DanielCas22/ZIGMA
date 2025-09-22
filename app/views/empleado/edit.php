<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow border-warning border-2">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <span class="d-inline-block bg-warning text-white rounded-circle p-3 mb-2">
                            <i class="fa fa-user-edit fa-2x"></i>
                        </span>
                        <h2 class="mb-0 text-warning">Editar Empleado</h2>
                        <p class="text-muted">Modifica los datos del empleado <?= htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellidos']) ?></p>
                    </div>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                            <label class="form-label">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" 
                                   value="<?= htmlspecialchars($empleado['apellidos']) ?>" 
                                   placeholder="Ej: Ramírez López" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Rol o Cargo</label>
                            <select name="rol" class="form-select" required id="rolSelect" onchange="actualizarSalario()">
                                <option value="">Seleccione un rol</option>
                                <option value="empleado" data-salario="2500000" 
                                    <?= (isset($empleadoConRol['rol_principal']) && $empleadoConRol['rol_principal'] === 'empleado') ? 'selected' : '' ?>>
                                    Empleado
                                </option>
                                <option value="rrhh" data-salario="4000000" 
                                    <?= (isset($empleadoConRol['rol_principal']) && $empleadoConRol['rol_principal'] === 'rrhh') ? 'selected' : '' ?>>
                                    RRHH
                                </option>
                                <option value="admin" data-salario="6000000" 
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
                            <label class="form-label">Salario Base</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <input type="number" name="salario" id="salarioInput" class="form-control" 
                                       value="<?= $empleado['salario'] ?>" 
                                       placeholder="Ingrese un salario" min="1" step="1000">
                                <button type="button" class="btn btn-outline-warning" onclick="autoAsignarSalario()" title="Auto-asignar según rol">
                                    <i class="fas fa-magic"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info me-1"></i>
                                Puede modificar el salario manualmente o usar auto-asignación según el rol
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="fas fa-save me-2"></i>Actualizar
                            </button>
                            <a href="/ZIGMA/public/index.php?url=Empleado/index" class="btn btn-outline-secondary px-4">
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
// Auto-actualización del salario cuando cambia el rol (solo si el campo está vacío)
function actualizarSalario() {
    const rolSelect = document.getElementById('rolSelect');
    const salarioInput = document.getElementById('salarioInput');
    
    if (rolSelect.value && !salarioInput.value) {
        // Solo auto-asignar si el campo de salario está vacío
        autoAsignarSalario();
    }
}

// Función para auto-asignar salario según rol seleccionado
function autoAsignarSalario() {
    const rolSelect = document.getElementById('rolSelect');
    const salarioInput = document.getElementById('salarioInput');
    
    const selectedOption = rolSelect.options[rolSelect.selectedIndex];
    
    if (selectedOption.value) {
        const salario = selectedOption.getAttribute('data-salario');
        salarioInput.value = salario;
        salarioInput.classList.add('text-warning', 'fw-bold');
        
        // Mostrar mensaje de confirmación
        mostrarMensaje('Salario asignado automáticamente según el rol', 'success');
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

// Formatear salario mientras se escribe
document.addEventListener('DOMContentLoaded', function() {
    const salarioInput = document.getElementById('salarioInput');
    
    salarioInput.addEventListener('input', function() {
        // Remover clases de auto-asignación si el usuario modifica manualmente
        this.classList.remove('text-warning', 'fw-bold');
    });
});
</script>
</body>
</html>