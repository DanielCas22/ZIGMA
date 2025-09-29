<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .devengado-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .devengado-table {
            font-size: 0.9rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .devengado-table thead {
            background: #28a745;
            color: white;
        }
        .devengado-table th {
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            padding: 1rem 0.5rem;
            border: 1px solid #fff;
        }
        .devengado-table td {
            text-align: right;
            vertical-align: middle;
            padding: 0.75rem 0.5rem;
            border: 1px solid #dee2e6;
        }
        .devengado-table td:first-child {
            text-align: left;
            font-weight: 500;
        }
        .total-row {
            background: #f8f9fa;
            font-weight: 700;
            font-size: 1.1rem;
        }
        .total-row td {
            border-top: 2px solid #28a745;
        }
        .valor-destacado {
            background: #e8f5e9;
            font-weight: 600;
        }
        .card-estadistica {
            border-left: 4px solid #28a745;
            transition: transform 0.2s;
        }
        .card-estadistica:hover {
            transform: translateY(-2px);
        }
        .btn-detalle {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        .badge-concepto {
            font-size: 0.7rem;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Header del Devengado -->
    <div class="devengado-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-0">
                        <i class="fas fa-money-bill-wave me-3"></i>
                        Total Devengado - Nómina
                    </h1>
                    <p class="mb-0 mt-2 opacity-90">
                        Resumen completo de ingresos por empleado
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="?url=dashboard" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Estadísticas Generales -->
        <?php if (!empty($totales_empresa)): ?>
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card card-estadistica h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x text-success mb-2"></i>
                        <h5 class="card-title"><?= number_format($total_empleados) ?></h5>
                        <p class="card-text text-muted mb-0">Empleados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-estadistica h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-dollar-sign fa-2x text-success mb-2"></i>
                        <h6 class="card-title">$<?= number_format($totales_empresa['sueldo_basico'] ?? 0) ?></h6>
                        <p class="card-text text-muted mb-0">Salarios Básicos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-estadistica h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                        <h6 class="card-title">$<?= number_format($totales_empresa['horas_extras'] ?? 0) ?></h6>
                        <p class="card-text text-muted mb-0">Horas Extras</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-estadistica h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-bus fa-2x text-info mb-2"></i>
                        <h6 class="card-title">$<?= number_format($totales_empresa['auxilio_transporte'] ?? 0) ?></h6>
                        <p class="card-text text-muted mb-0">Auxilio Transp.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-estadistica h-100 text-center" 
                     style="cursor: pointer;" 
                     onclick="window.location.href='#conceptos-adicionales'"
                     data-bs-toggle="tooltip" 
                     data-bs-placement="top" 
                     title="Haga clic para gestionar conceptos adicionales de empleados">
                    <div class="card-body">
                        <i class="fas fa-plus-circle fa-2x text-secondary mb-2"></i>
                        <h6 class="card-title">$<?= number_format($totales_empresa['otros'] ?? 0) ?></h6>
                        <p class="card-text text-muted mb-0">Otros</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-estadistica h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                        <h5 class="card-title text-success">$<?= number_format($totales_empresa['total_general'] ?? 0) ?></h5>
                        <p class="card-text text-muted mb-0"><strong>TOTAL DEVENGADO</strong></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Sección de Conceptos Adicionales -->
        <a id="conceptos-adicionales"></a>
        
        <!-- Tabla Principal de Devengado -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    Detalle Devengado por Empleado
                </h4>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($calculos_empleados)): ?>
                <div class="table-responsive">
                    <table class="table table-hover devengado-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 200px;">EMPLEADO</th>
                                <th style="width: 120px;">SUELDO BÁSICO</th>
                                <th style="width: 120px;">HORAS EXTRAS</th>
                                <th style="width: 120px;">AUXILIO TRANSP.</th>
                                <th style="width: 120px;">OTROS</th>
                                <th style="width: 140px; background: #20c997;">TOTAL DEVENGADO</th>
                                <th style="width: 100px;">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($calculos_empleados as $calculo): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($calculo['empleado']['nombre'] . ' ' . $calculo['empleado']['apellido']) ?></strong>
                                    <br>
                                    <small class="text-muted"><?= htmlspecialchars($calculo['empleado']['cargo'] ?? 'N/A') ?></small>
                                </td>
                                <td>
                                    <strong>$<?= number_format($calculo['conceptos']['sueldo_basico']['valor']) ?></strong>
                                </td>
                                <td>
                                    $<?= number_format($calculo['conceptos']['horas_extras']['valor']) ?>
                                    <?php if ($calculo['conceptos']['horas_extras']['total_horas'] > 0): ?>
                                        <br><small class="text-info"><?= $calculo['conceptos']['horas_extras']['total_horas'] ?> hrs</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($calculo['conceptos']['auxilio_transporte']['aplica']): ?>
                                        <span class="badge badge-concepto bg-info">$<?= number_format($calculo['conceptos']['auxilio_transporte']['valor']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="cursor: pointer;" 
                                    class="otros-concepto" 
                                    data-empleado-id="<?= $calculo['empleado']['id'] ?>"
                                    data-empleado-nombre="<?= htmlspecialchars($calculo['empleado']['nombre'] . ' ' . $calculo['empleado']['apellido']) ?>"
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?= $calculo['conceptos']['otros']['descripcion'] ?> - Haga clic para gestionar">
                                    <span class="badge bg-secondary">
                                        $<?= number_format($calculo['conceptos']['otros']['valor']) ?>
                                    </span>
                                    <?php if ($calculo['conceptos']['otros']['valor'] > 0): ?>
                                        <small class="d-block text-muted mt-1">
                                            <?= count($calculo['conceptos']['otros']['detalle']) ?> concepto(s)
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td class="valor-destacado">
                                    <strong class="text-success">$<?= number_format($calculo['resumen']['total_devengado']) ?></strong>
                                </td>
                                <td>
                                    <a href="?url=PrestacionesSociales/detalle/<?= $calculo['empleado']['id'] ?>" 
                                       class="btn btn-outline-success btn-detalle" title="Ver detalle de prestaciones">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        
                        <!-- Fila de totales -->
                        <?php if (!empty($totales_empresa)): ?>
                        <tfoot>
                            <tr class="total-row">
                                <td><strong>TOTALES EMPRESA</strong></td>
                                <td><strong>$<?= number_format($totales_empresa['sueldo_basico']) ?></strong></td>
                                <td><strong>$<?= number_format($totales_empresa['horas_extras']) ?></strong></td>
                                <td><strong>$<?= number_format($totales_empresa['auxilio_transporte']) ?></strong></td>
                                <td><strong>$<?= number_format($totales_empresa['otros']) ?></strong></td>
                                <td style="background: #28a745; color: white;">
                                    <strong>$<?= number_format($totales_empresa['total_general']) ?></strong>
                                </td>
                                <td>
                                    <i class="fas fa-calculator text-success"></i>
                                </td>
                            </tr>
                        </tfoot>
                        <?php endif; ?>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay empleados para calcular</h5>
                    <p class="text-muted">Agregue empleados para generar el devengado</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Parámetros de Cálculo 2025
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li><strong>Salario Mínimo:</strong> $1,423,000</li>
                            <li><strong>Auxilio de Transporte:</strong> $200,000</li>
                            <li><strong>Límite Auxilio:</strong> $2,645,000 (2 SMMLV)</li>
                            <li><strong>Fecha de Cálculo:</strong> <?= date('d/m/Y H:i') ?></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-pie me-2"></i>
                            Distribución Promedio
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($promedios)): ?>
                        <ul class="list-unstyled mb-0">
                            <li><strong>Salario Básico:</strong> $<?= number_format($promedios['sueldo_basico'] ?? 0) ?></li>
                            <li><strong>Horas Extras:</strong> $<?= number_format($promedios['horas_extras'] ?? 0) ?></li>
                            <li><strong>Auxilio Transp.:</strong> $<?= number_format($promedios['auxilio_transporte'] ?? 0) ?></li>
                            <li><strong>Promedio Total:</strong> <span class="text-success">$<?= number_format($promedios['total_general'] ?? 0) ?></span></li>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal para Conceptos Adicionales -->
    <div class="modal fade" id="modalConceptos" tabindex="-1" aria-labelledby="modalConceptosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalConceptosLabel">
                        <i class="fas fa-plus-circle me-2"></i>Otros Conceptos - Total Devengado
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="empleadoInfo" class="mb-3 p-3 bg-light rounded">
                        <h6 class="mb-1">Empleado: <span id="empleadoNombre" class="text-primary"></span></h6>
                        <small class="text-muted">ID: <span id="empleadoId"></span></small>
                    </div>
                    
                    <!-- Formulario para agregar concepto -->
                    <form id="formConcepto">
                        <input type="hidden" id="inputEmpleadoId" name="empleado_id">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="concepto" class="form-label">Concepto</label>
                                <input type="text" class="form-control" id="concepto" name="concepto" 
                                       placeholder="Ej: Bonificación especial, Auxilio alimentación" required>
                            </div>
                            <div class="col-md-6">
                                <label for="valor" class="form-label">Valor ($)</label>
                                <input type="number" class="form-control" id="valor" name="valor" 
                                       step="0.01" min="0" placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="2" 
                                          placeholder="Descripción detallada del concepto adicional"></textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Agregar Concepto
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <hr>
                    
                    <!-- Lista de conceptos existentes -->
                    <div id="listaConceptos">
                        <h6><i class="fas fa-list"></i> Conceptos Actuales</h6>
                        <div id="conceptosContainer">
                            <!-- Se llenará dinámicamente con JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Modal de conceptos adicionales
        const modalConceptos = document.getElementById('modalConceptos');
        const formConcepto = document.getElementById('formConcepto');
        
        // Event listeners para las celdas de "Otros"
        document.querySelectorAll('.otros-concepto').forEach(function(celda) {
            celda.addEventListener('click', function() {
                const empleadoId = this.getAttribute('data-empleado-id');
                const empleadoNombre = this.getAttribute('data-empleado-nombre');
                
                // Actualizar información del empleado en el modal
                document.getElementById('empleadoId').textContent = empleadoId;
                document.getElementById('empleadoNombre').textContent = empleadoNombre;
                document.getElementById('inputEmpleadoId').value = empleadoId;
                
                // Cargar conceptos existentes
                cargarConceptosEmpleado(empleadoId);
                
                // Mostrar modal
                new bootstrap.Modal(modalConceptos).show();
            });
        });
        
        // Envío del formulario
        formConcepto.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(formConcepto);
            
            fetch('?url=Devengado/agregarConcepto', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Limpiar formulario
                    formConcepto.reset();
                    
                    // Recargar conceptos
                    cargarConceptosEmpleado(formData.get('empleado_id'));
                    
                    // Mostrar mensaje de éxito
                    mostrarMensaje('Concepto agregado exitosamente', 'success');
                    
                    // Recargar página después de un momento
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarMensaje(data.message || 'Error al agregar el concepto', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarMensaje('Error de conexión', 'danger');
            });
        });
        
        function cargarConceptosEmpleado(empleadoId) {
            fetch(`?url=Devengado/obtenerConceptos/${empleadoId}`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('conceptosContainer');
                
                if (data.success && data.conceptos.length > 0) {
                    let html = '<div class="table-responsive"><table class="table table-sm">';
                    html += '<thead><tr><th>Concepto</th><th>Valor</th><th>Descripción</th><th>Acciones</th></tr></thead>';
                    html += '<tbody>';
                    
                    data.conceptos.forEach(concepto => {
                        html += `<tr>
                            <td><strong>${concepto.concepto}</strong></td>
                            <td class="currency">$${new Intl.NumberFormat('es-CO').format(concepto.valor)}</td>
                            <td><small>${concepto.descripcion || 'Sin descripción'}</small></td>
                            <td>
                                <button class="btn btn-sm btn-danger" onclick="eliminarConcepto(${concepto.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                    });
                    
                    html += '</tbody></table></div>';
                    html += `<div class="mt-2 p-2 bg-light rounded"><strong>Total Conceptos: $${new Intl.NumberFormat('es-CO').format(data.total)}</strong></div>`;
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="text-muted text-center py-3"><i class="fas fa-info-circle"></i> No hay conceptos adicionales registrados para este empleado</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('conceptosContainer').innerHTML = '<div class="text-danger text-center py-3"><i class="fas fa-exclamation-triangle"></i> Error al cargar conceptos</div>';
            });
        }
        
        window.eliminarConcepto = function(id) {
            if (confirm('¿Está seguro de eliminar este concepto? Esta acción no se puede deshacer.')) {
                fetch(`?url=Devengado/eliminarConcepto/${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cargarConceptosEmpleado(document.getElementById('inputEmpleadoId').value);
                        mostrarMensaje('Concepto eliminado exitosamente', 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        mostrarMensaje(data.message || 'Error al eliminar el concepto', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarMensaje('Error de conexión', 'danger');
                });
            }
        }
        
        function mostrarMensaje(mensaje, tipo) {
            // Crear alert temporal
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(alertDiv);
            
            // Auto-remover después de 3 segundos
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 3000);
        }
    });
    </script>
    
</body>
</html>