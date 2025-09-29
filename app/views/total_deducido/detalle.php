<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Total Deducido - <?= htmlspecialchars($empleado['nombre'] ?? 'Empleado') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-stat {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .card-deduction {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
        }
        .card-concept {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border: none;
        }
        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-custom {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .info-tooltip {
            cursor: help;
            color: #6c757d;
        }
        .concept-badge {
            background: linear-gradient(45deg, #11998e, #38ef7d);
            color: white;
            border: none;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="fas fa-calculator text-primary me-2"></i>
                            Total Deducido - <?= htmlspecialchars($empleado['nombre']) ?>
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="fas fa-id-card me-1"></i> <?= htmlspecialchars($empleado['documento'] ?? $empleado['cedula'] ?? 'N/A') ?> | 
                            <i class="fas fa-briefcase me-1"></i> <?= htmlspecialchars($empleado['cargo'] ?? $empleado['rol_nombre'] ?? 'No especificado') ?>
                        </p>
                    </div>
                    <div>
                        <a href="/ZIGMA/TotalDeducido" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Volver
                        </a>
                        <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#conceptosModal">
                            <i class="fas fa-plus me-1"></i> Gestionar Conceptos
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas Informativas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card card-stat h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                        <h5>Total Devengado</h5>
                        <h4>$<?= number_format($datos['total_devengado'], 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-deduction h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-minus-circle fa-2x mb-2"></i>
                        <h5>Total Deducido</h5>
                        <h4>$<?= number_format($datos['total_deducido'], 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-concept h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-list-alt fa-2x mb-2"></i>
                        <h5>Conceptos Adicionales</h5>
                        <h4>$<?= number_format($datos['conceptos_adicionales']['total'], 0, ',', '.') ?></h4>
                        <small>(<?= $datos['conceptos_adicionales']['cantidad'] ?> conceptos)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stat h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                        <h5>Neto a Pagar</h5>
                        <h4>$<?= number_format($datos['total_devengado'] - $datos['total_deducido'], 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalle de Deducciones -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Detalle de Deducciones
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Concepto</th>
                                        <th>Base de Cálculo</th>
                                        <th>Porcentaje</th>
                                        <th class="text-end">Valor</th>
                                        <th class="text-center">
                                            <i class="fas fa-info-circle" data-bs-toggle="tooltip" title="Información adicional"></i>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Salud -->
                                    <tr>
                                        <td>
                                            <i class="fas fa-heartbeat text-danger me-2"></i>
                                            <strong>Salud</strong>
                                        </td>
                                        <td>$<?= number_format($datos['base_calculo'], 0, ',', '.') ?></td>
                                        <td>4.00%</td>
                                        <td class="text-end">
                                            <span class="badge bg-danger">
                                                $<?= number_format($datos['salud'], 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <i class="fas fa-info-circle info-tooltip" 
                                               data-bs-toggle="tooltip" 
                                               title="Aporte obligatorio del empleado para salud según normativa colombiana"></i>
                                        </td>
                                    </tr>

                                    <!-- Pensión -->
                                    <tr>
                                        <td>
                                            <i class="fas fa-user-clock text-warning me-2"></i>
                                            <strong>Pensión</strong>
                                        </td>
                                        <td>$<?= number_format($datos['base_calculo'], 0, ',', '.') ?></td>
                                        <td>4.00%</td>
                                        <td class="text-end">
                                            <span class="badge bg-warning">
                                                $<?= number_format($datos['pension'], 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <i class="fas fa-info-circle info-tooltip" 
                                               data-bs-toggle="tooltip" 
                                               title="Aporte obligatorio del empleado para pensión según normativa colombiana"></i>
                                        </td>
                                    </tr>

                                    <!-- Fondo de Solidaridad (solo si aplica) -->
                                    <?php if ($datos['fondo_solidaridad'] > 0): ?>
                                    <tr>
                                        <td>
                                            <i class="fas fa-hands-helping text-info me-2"></i>
                                            <strong>Fondo de Solidaridad</strong>
                                        </td>
                                        <td>$<?= number_format($datos['base_calculo'], 0, ',', '.') ?></td>
                                        <td><?= number_format($datos['porcentaje_fondo'], 2) ?>%</td>
                                        <td class="text-end">
                                            <span class="badge bg-info">
                                                $<?= number_format($datos['fondo_solidaridad'], 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <i class="fas fa-info-circle info-tooltip" 
                                               data-bs-toggle="tooltip" 
                                               title="Aplica para empleados con ingresos superiores a 4 SMLV"></i>
                                        </td>
                                    </tr>
                                    <?php endif; ?>

                                    <!-- Retención en la Fuente (si aplica) -->
                                    <?php if ($datos['retencion_fuente'] > 0): ?>
                                    <tr>
                                        <td>
                                            <i class="fas fa-receipt text-secondary me-2"></i>
                                            <strong>Retención en la Fuente</strong>
                                        </td>
                                        <td>$<?= number_format($datos['total_devengado'], 0, ',', '.') ?></td>
                                        <td>Variable</td>
                                        <td class="text-end">
                                            <span class="badge bg-secondary">
                                                $<?= number_format($datos['retencion_fuente'], 0, ',', '.') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <i class="fas fa-info-circle info-tooltip" 
                                               data-bs-toggle="tooltip" 
                                               title="Retención aplicada según tabla de retención en la fuente"></i>
                                        </td>
                                    </tr>
                                    <?php endif; ?>

                                    <!-- Conceptos Adicionales -->
                                    <?php if (!empty($datos['conceptos_adicionales']['conceptos'])): ?>
                                        <?php foreach ($datos['conceptos_adicionales']['conceptos'] as $concepto): ?>
                                        <tr>
                                            <td>
                                                <i class="fas fa-plus-circle text-success me-2"></i>
                                                <strong><?= htmlspecialchars($concepto['concepto']) ?></strong>
                                                <?php if (!empty($concepto['descripcion'])): ?>
                                                    <br><small class="text-muted"><?= htmlspecialchars($concepto['descripcion']) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>-</td>
                                            <td>-</td>
                                            <td class="text-end">
                                                <span class="badge concept-badge">
                                                    $<?= number_format($concepto['valor'], 0, ',', '.') ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <small class="text-muted">
                                                    <?= date('d/m/Y', strtotime($concepto['fecha_creacion'])) ?>
                                                </small>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>

                                    <!-- Total -->
                                    <tr class="table-primary">
                                        <td><strong>TOTAL DEDUCIDO</strong></td>
                                        <td colspan="2"></td>
                                        <td class="text-end">
                                            <strong class="h5">$<?= number_format($datos['total_deducido'], 0, ',', '.') ?></strong>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Gestionar Conceptos -->
    <div class="modal fade" id="conceptosModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-cogs me-2"></i>
                        Gestionar Conceptos Deducibles
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para agregar concepto -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-plus me-1"></i> Agregar Concepto Deducible
                            </h6>
                        </div>
                        <div class="card-body">
                            <form id="formAgregarConcepto">
                                <input type="hidden" name="empleado_id" value="<?= $empleado['id'] ?>">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Concepto*</label>
                                        <input type="text" class="form-control" name="concepto" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Valor*</label>
                                        <input type="number" class="form-control" name="valor" step="0.01" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Descripción</label>
                                        <input type="text" class="form-control" name="descripcion">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-plus me-1"></i> Agregar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Lista de conceptos existentes -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-list me-1"></i> Conceptos Actuales
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="listaConceptos">
                                <!-- Se carga vía AJAX -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inicializar tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            cargarConceptos();
        });

        // Manejar envío del formulario
        document.getElementById('formAgregarConcepto').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/ZIGMA/TotalDeducido/agregarConcepto', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Concepto agregado exitosamente');
                    this.reset();
                    cargarConceptos();
                    // Recargar página para actualizar totales
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            });
        });

        // Cargar lista de conceptos
        function cargarConceptos() {
            const empleadoId = <?= $empleado['id'] ?>;
            
            fetch(`/ZIGMA/TotalDeducido/obtenerConceptos?empleado_id=${empleadoId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const lista = document.getElementById('listaConceptos');
                    if (data.conceptos.length === 0) {
                        lista.innerHTML = '<p class="text-muted">No hay conceptos adicionales registrados.</p>';
                        return;
                    }
                    
                    let html = '<div class="table-responsive"><table class="table table-sm">';
                    html += '<thead><tr><th>Concepto</th><th>Descripción</th><th class="text-end">Valor</th><th class="text-center">Acciones</th></tr></thead><tbody>';
                    
                    data.conceptos.forEach(concepto => {
                        html += `<tr>
                            <td><strong>${concepto.concepto}</strong></td>
                            <td>${concepto.descripcion || '-'}</td>
                            <td class="text-end">$${Number(concepto.valor).toLocaleString()}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-danger" onclick="eliminarConcepto(${concepto.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                    });
                    
                    html += '</tbody></table></div>';
                    lista.innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('listaConceptos').innerHTML = '<p class="text-danger">Error al cargar conceptos</p>';
            });
        }

        // Eliminar concepto
        function eliminarConcepto(id) {
            if (!confirm('¿Está seguro de eliminar este concepto?')) return;
            
            fetch('/ZIGMA/TotalDeducido/eliminarConcepto', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + id
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Concepto eliminado exitosamente');
                    cargarConceptos();
                    // Recargar página para actualizar totales
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el concepto');
            });
        }
    </script>
</body>
</html>