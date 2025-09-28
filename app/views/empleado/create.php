<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow border-primary border-2">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <span class="d-inline-block bg-primary text-white rounded-circle p-3 mb-2">
                            <i class="fa fa-user-plus fa-2x"></i>
                        </span>
                        <h2 class="mb-0 text-primary">Registrar Empleado</h2>
                        <p class="text-muted">Agrega un nuevo empleado al sistema</p>
                    </div>
                    <form method="post" action="/ZIGMA/public/index.php?url=Empleado/store">
                        <div class="mb-3">
                            <label class="form-label">Nombres</label>
                            <input type="text" name="nombres" class="form-control" placeholder="Ej: Juan Carlos" required>
                        </div>
                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol o Cargo</label>
                            <select name="rol" class="form-select" required id="rolSelect" onchange="actualizarSueldo()">
                                <option value="">Seleccione un rol</option>
                                <option value="empleado" data-sueldo="1423000">Empleado</option>
                                <option value="rrhh" data-sueldo="2000000">RRHH</option>
                                <option value="admin" data-sueldo="4000000">Admin</option>
                            </select>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Todos los usuarios tendrán rol de "empleado" automáticamente. Si selecciona RRHH o Admin, tendrá ambos roles.
                                </small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sueldo Actual</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <input type="number" name="sueldo_actual" id="sueldoInput" class="form-control" placeholder="Seleccione un rol o ingrese un sueldo" min="1" max="100000000" step="1">
                                <button type="button" class="btn btn-outline-info" onclick="autoAsignarSueldo()" title="Auto-asignar según rol">
                                    <i class="fas fa-magic"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info me-1"></i>
                                Ingrese un sueldo entre $1 y $100.000.000 pesos colombianos
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success px-4">Registrar</button>
                            <a href="/ZIGMA/public/index.php?url=Empleado/index" class="btn btn-outline-secondary px-4">Cancelar</a>
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
        sueldoInput.classList.add('text-success', 'fw-bold');
        
        // Mostrar mensaje de confirmación
        mostrarMensaje(`Sueldo actualizado: $${Number(sueldo).toLocaleString('es-CO')}`, 'success');
    } else {
        // Si no hay rol seleccionado, limpiar el campo
        sueldoInput.value = '';
        sueldoInput.classList.remove('text-success', 'fw-bold');
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
        sueldoInput.classList.add('text-success', 'fw-bold');
        
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
        this.classList.remove('text-success', 'fw-bold');
    });
});
</script>
</body>
</html>
