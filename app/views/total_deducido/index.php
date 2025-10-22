<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
    <style>
        @media (max-width: 576px) {
            .card-estadistica, .card, .table, .deducido-table, .devengado-table {
                font-size: clamp(11px, 3vw, 13px) !important;
            }
            .card-header, h1, h2, h4, .display-6 {
                font-size: clamp(1rem, 4vw, 1.2rem) !important;
            }
            .btn, .btn-sm, .btn-primary, .btn-danger, .btn-info, .btn-success {
                font-size: clamp(12px, 3vw, 14px) !important;
                padding: 6px 12px !important;
                min-width: 80px;
                max-width: 140px;
            }
            .table th, .table td {
                padding: 6px !important;
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<?php $pageTitle = "Total Deducido"; ?>
<?php include __DIR__ . '/../components/navbar.php'; ?>

<!-- Header -->
<div class="container">
    <div class="page-header-zigma mb-4 py-4 fade-in-up">
        <div class="row align-items-center">
            <div class="col-md-12 text-center">
                <h1 class="display-6 fw-bold mb-2">
                    <i class="fas fa-minus-circle me-3"></i>
                    Total Deducido - Nómina
                </h1>
                <p class="mb-0 mt-2 text-muted">
                    Deducciones por salud, pensión, fondo de solidaridad y retención en la fuente
                </p>
            </div>
        </div>
    </div>
</div>

    <div class="container-fluid px-4">
        
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
                        <i class="fas fa-heartbeat fa-2x text-info mb-2"></i>
                        <h6 class="card-title">$<?= number_format($totales_empresa['salud'] ?? 0) ?></h6>
                        <p class="card-text text-muted mb-0">Salud (4%)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-estadistica h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-user-shield fa-2x text-primary mb-2"></i>
                        <h6 class="card-title">$<?= number_format($totales_empresa['pension'] ?? 0) ?></h6>
                        <p class="card-text text-muted mb-0">Pensión (4%)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-estadistica h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-handshake fa-2x text-warning mb-2"></i>
                        <h6 class="card-title">$<?= number_format($totales_empresa['fondo_solidaridad'] ?? 0) ?></h6>
                        <p class="card-text text-muted mb-0">F. Solidaridad</p>
                        <small class="text-muted"><?= $estadisticas['empleados_con_fondo_solidaridad'] ?? 0 ?> empleados</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-estadistica h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-receipt fa-2x text-success mb-2"></i>
                        <h6 class="card-title">$<?= number_format($totales_empresa['retencion_fuente'] ?? 0) ?></h6>
                        <p class="card-text text-muted mb-0">Ret. Fuente</p>
                        <small class="text-muted"><?= $estadisticas['empleados_con_retencion'] ?? 0 ?> empleados</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-estadistica h-100 text-center" 
                     style="cursor: pointer;" 
                     onclick="window.location.href='#conceptos-deducibles'"
                     data-bs-toggle="tooltip" 
                     data-bs-placement="top" 
                     title="Haga clic para gestionar otros descuentos de empleados">
                    <div class="card-body">
                        <i class="fas fa-minus-circle fa-2x text-secondary mb-2"></i>
                        <h6 class="card-title">$<?= number_format($totales_empresa['otros'] ?? 0) ?></h6>
                        <p class="card-text text-muted mb-0">Otros</p>
                        <small class="text-muted"><?= $estadisticas['empleados_con_otros_descuentos'] ?? 0 ?> empleados</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-estadistica h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-2x text-danger mb-2"></i>
                        <h5 class="card-title text-danger">$<?= number_format($totales_empresa['total_general'] ?? 0) ?></h5>
                        <p class="card-text text-muted mb-0"><strong>TOTAL DEDUCIDO</strong></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Sección de Conceptos Deducibles -->
        <a id="conceptos-deducibles"></a>
        
        <!-- Tabla Principal de Total Deducido -->
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    Detalle Total Deducido por Empleado
                </h4>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($calculos_empleados)): ?>
                <div class="table-responsive">
                    <table class="table table-hover deducido-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 200px;">EMPLEADO</th>
                                <th style="width: 120px;">SALUD (4%)</th>
                                <th style="width: 120px;">PENSIÓN (4%)</th>
                                <th style="width: 120px;">F. SOLIDARIDAD</th>
                                <th style="width: 120px;">RET. FUENTE</th>
                                <th style="width: 120px;">OTROS</th>
                                <th style="width: 140px; background: #c82333;">TOTAL DEDUCIDO</th>
                                <th style="width: 100px;">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($calculos_empleados as $calculo): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($calculo['empleado']['nombre'] . ' ' . $calculo['empleado']['apellido']) ?></strong>
                                    <br>
                                    <small class="text-muted"><?= htmlspecialchars($calculo['empleado']['documento']) ?></small>
                                </td>
                                <td>$<?= number_format($calculo['deducciones']['salud_empleado']['valor']) ?></td>
                                <td>$<?= number_format($calculo['deducciones']['pension_empleado']['valor']) ?></td>
                                <td>
                                    <?php if ($calculo['deducciones']['fondo_solidaridad']['aplica']): ?>
                                        <span class="badge bg-warning text-dark" 
                                              data-bs-toggle="tooltip" 
                                              title="<?= $calculo['deducciones']['fondo_solidaridad']['rango'] ?> - <?= $calculo['deducciones']['fondo_solidaridad']['porcentaje'] ?>%">
                                            $<?= number_format($calculo['deducciones']['fondo_solidaridad']['valor']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">$0</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($calculo['deducciones']['retencion_fuente']['valor'] > 0): ?>
                                        <span class="badge bg-success" 
                                              data-bs-toggle="tooltip" 
                                              title="Base: $<?= number_format($calculo['deducciones']['retencion_fuente']['base_retencion']) ?>">
                                            $<?= number_format($calculo['deducciones']['retencion_fuente']['valor']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">$0</span>
                                    <?php endif; ?>
                                </td>
                                <td style="cursor: pointer;" 
                                    class="otros-concepto" 
                                    data-empleado-id="<?= $calculo['empleado']['id'] ?>"
                                    data-empleado-nombre="<?= htmlspecialchars($calculo['empleado']['nombre'] . ' ' . $calculo['empleado']['apellido']) ?>"
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?= $calculo['deducciones']['otros_deducibles']['descripcion'] ?> - Haga clic para gestionar">
                                    <span class="badge bg-secondary">
                                        $<?= number_format($calculo['deducciones']['otros_deducibles']['valor']) ?>
                                    </span>
                                    <?php if ($calculo['deducciones']['otros_deducibles']['valor'] > 0): ?>
                                        <small class="d-block text-muted mt-1">
                                            <?= count($calculo['deducciones']['otros_deducibles']['detalle']) ?> concepto(s)
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td class="valor-destacado">
                                    <strong>$<?= number_format($calculo['resumen']['total_deducciones']) ?></strong>
                                </td>
                                <td>
                                    <a href="?url=TotalDeducido/detalle/<?= $calculo['empleado']['id'] ?>" 
                                       class="btn btn-outline-danger btn-detalle" title="Ver detalle de deducciones">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            
                            <!-- Fila de totales -->
                            <tr class="total-row">
                                <td><strong>TOTALES EMPRESA</strong></td>
                                <td><strong>$<?= number_format($totales_empresa['salud']) ?></strong></td>
                                <td><strong>$<?= number_format($totales_empresa['pension']) ?></strong></td>
                                <td><strong>$<?= number_format($totales_empresa['fondo_solidaridad']) ?></strong></td>
                                <td><strong>$<?= number_format($totales_empresa['retencion_fuente']) ?></strong></td>
                                <td><strong>$<?= number_format($totales_empresa['otros']) ?></strong></td>
                                <td><strong>$<?= number_format($totales_empresa['total_general']) ?></strong></td>
                                <td>
                                    <span class="badge bg-dark"><?= $total_empleados ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay datos de empleados para mostrar</h5>
                    <p class="text-muted">Asegúrese de tener empleados registrados en el sistema.</p>
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
                            <li><strong>Salud Empleado:</strong> 4%</li>
                            <li><strong>Pensión Empleado:</strong> 4%</li>
                            <li><strong>Base de Cálculo:</strong> (Total Devengado - Auxilio) + Horas Extras + Otros</li>
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
                            Fondo de Solidaridad
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li><strong>1-4 SMLV:</strong> 0.0%</li>
                            <li><strong>4-16 SMLV:</strong> 1.0%</li>
                            <li><strong>16-17 SMLV:</strong> 1.2%</li>
                            <li><strong>17-18 SMLV:</strong> 1.4%</li>
                            <li><strong>18-19 SMLV:</strong> 1.6%</li>
                            <li><strong>19-20 SMLV:</strong> 1.8%</li>
                            <li><strong>Más de 20 SMLV:</strong> 2.0%</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal para Conceptos Deducibles (Otros) -->
    <div class="modal fade" id="modalConceptos" tabindex="-1" aria-labelledby="modalConceptosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalConceptosLabel">
                        <i class="fas fa-minus-circle me-2"></i>Otros Descuentos - Total Deducido
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
                                <label for="concepto" class="form-label">Concepto Deducible</label>
                                <input type="text" class="form-control" id="concepto" name="concepto" 
                                       placeholder="Ej: Préstamo personal, Descuento por tardanza" required>
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
                                          placeholder="Descripción detallada del concepto deducible"></textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-minus"></i> Agregar Descuento
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <hr>
                    
                    <!-- Lista de conceptos existentes -->
                    <div id="listaConceptos">
                        <h6><i class="fas fa-list"></i> Descuentos Actuales</h6>
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
        
        // Modal de conceptos deducibles
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
            
            fetch('?url=TotalDeducido/agregarConcepto', {
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
                    mostrarMensaje('Descuento agregado exitosamente', 'success');
                    
                    // Recargar página después de un momento
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    mostrarMensaje(data.message || 'Error al agregar el descuento', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarMensaje('Error de conexión', 'danger');
            });
        });
        
        function cargarConceptosEmpleado(empleadoId) {
            fetch(`?url=TotalDeducido/obtenerConceptos/${empleadoId}`)
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
                    html += `<div class="mt-2 p-2 bg-light rounded"><strong>Total Descuentos: $${new Intl.NumberFormat('es-CO').format(data.total)}</strong></div>`;
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<div class="text-muted text-center py-3"><i class="fas fa-info-circle"></i> No hay descuentos adicionales registrados para este empleado</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('conceptosContainer').innerHTML = '<div class="text-danger text-center py-3"><i class="fas fa-exclamation-triangle"></i> Error al cargar descuentos</div>';
            });
        }
        
        window.eliminarConcepto = function(id) {
            if (confirm('¿Está seguro de eliminar este descuento? Esta acción no se puede deshacer.')) {
                fetch(`?url=TotalDeducido/eliminarConcepto/${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        cargarConceptosEmpleado(document.getElementById('inputEmpleadoId').value);
                        mostrarMensaje('Descuento eliminado exitosamente', 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        mostrarMensaje(data.message || 'Error al eliminar el descuento', 'danger');
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