<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Gestión de Riesgos ARL'); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #ffc107;
            padding-bottom: 10px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .stats-section {
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .stat-card {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            border: 1px solid #bee5eb;
        }
        .btn {
            background-color: #ffc107;
            color: #212529;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #e0a800;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            position: sticky;
            top: 0;
        }
        .text-center {
            text-align: center;
        }
        .nav-links {
            margin-bottom: 20px;
        }
        .nav-links a {
            margin-right: 15px;
        }
        .risk-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }
        .risk-1 { background-color: #28a745; }
        .risk-2 { background-color: #17a2b8; }
        .risk-3 { background-color: #ffc107; color: #212529; }
        .risk-4 { background-color: #fd7e14; }
        .risk-5 { background-color: #dc3545; }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 5px;
            width: 90%;
            max-width: 500px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .no-risk {
            background-color: #f8d7da;
            color: #721c24;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚠️ <?php echo htmlspecialchars($title ?? 'Gestión de Riesgos ARL'); ?></h1>
        
        <div class="nav-links">
            <a href="/seguridad_social" class="btn btn-secondary">Seguridad Social</a>
            <a href="/seguridad_social/indexConARL" class="btn btn-secondary">Seguridad Social + ARL</a>
            <a href="/seguridad_social/gestionRiesgos" class="btn">Gestión Riesgos</a>
            <a href="/seguridad_social/calculoARLPuro" class="btn btn-secondary">ARL PROM</a>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                ✅ <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Estadísticas generales -->
        <?php if (!empty($estadisticas)): ?>
            <div class="stats-section">
                <h2>📊 Estadísticas de Riesgos</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>👥 Total Empleados</h3>
                        <div style="font-size: 24px; font-weight: bold; color: #007bff;">
                            <?php echo $estadisticas['total_empleados']; ?>
                        </div>
                    </div>
                    <div class="stat-card">
                        <h3>📈 Riesgo Promedio</h3>
                        <div style="font-size: 24px; font-weight: bold; color: #28a745;">
                            <?php echo round($estadisticas['riesgo_promedio'], 1); ?>
                        </div>
                    </div>
                    <?php foreach ($estadisticas['por_nivel'] as $codigo => $info): ?>
                        <div class="stat-card">
                            <h4>
                                <span class="risk-badge risk-<?php echo $codigo; ?>">
                                    Riesgo <?php echo $info['nivel_info']['codigo']; ?>
                                </span>
                            </h4>
                            <div style="font-size: 18px; font-weight: bold;">
                                <?php echo $info['cantidad_empleados']; ?> empleados
                            </div>
                            <div style="font-size: 14px; color: #666;">
                                (<?php echo $info['porcentaje_total']; ?>%)
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Información de niveles de riesgo -->
        <div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h3>📋 Niveles de Riesgo Disponibles</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                <?php foreach ($niveles_riesgo as $codigo => $info): ?>
                    <div style="background-color: white; padding: 10px; border-radius: 4px; border: 1px solid #ffeaa7;">
                        <span class="risk-badge risk-<?php echo $codigo; ?>">
                            Riesgo <?php echo $info['codigo']; ?>
                        </span>
                        <br>
                        <strong><?php echo $info['porcentaje']; ?>%</strong>
                        <br>
                        <small><?php echo $info['descripcion']; ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Tabla de empleados y sus riesgos -->
        <h2>👥 Empleados y Niveles de Riesgo Asignados</h2>
        <?php if (!empty($empleados)): ?>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Nivel de Riesgo Actual</th>
                            <th>Porcentaje ARL</th>
                            <th>Fecha Asignación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($empleados as $empleado): ?>
                            <tr>
                                <td class="text-center"><?php echo htmlspecialchars($empleado['id_empleados']); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido']); ?></strong>
                                </td>
                                <td class="text-center">
                                    <?php if ($empleado['riesgo_actual']): ?>
                                        <span class="risk-badge risk-<?php echo $empleado['riesgo_actual']['codigo_riesgo']; ?>">
                                            Riesgo <?php echo $empleado['riesgo_actual']['nivel_info']['codigo']; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="no-risk">Sin Asignar</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($empleado['riesgo_actual']): ?>
                                        <strong><?php echo $empleado['riesgo_actual']['nivel_info']['porcentaje']; ?>%</strong>
                                    <?php else: ?>
                                        <span style="color: #dc3545;">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($empleado['riesgo_actual'] && $empleado['riesgo_actual']['fecha_asignacion']): ?>
                                        <?php echo date('d/m/Y', strtotime($empleado['riesgo_actual']['fecha_asignacion'])); ?>
                                    <?php else: ?>
                                        <span style="color: #6c757d;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm" onclick="abrirModalRiesgo(<?php echo $empleado['id_empleados']; ?>, '<?php echo htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido'], ENT_QUOTES); ?>', <?php echo $empleado['riesgo_actual'] ? $empleado['riesgo_actual']['codigo_riesgo'] : 'null'; ?>)">
                                        ✏️ Cambiar Riesgo
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-error">
                No se encontraron empleados. Verifique que existan empleados registrados en el sistema.
            </div>
        <?php endif; ?>

        <!-- Modal para cambiar riesgo -->
        <div id="modalRiesgo" class="modal">
            <div class="modal-content">
                <span class="close" onclick="cerrarModalRiesgo()">&times;</span>
                <h3>⚠️ Cambiar Nivel de Riesgo</h3>
                <form method="POST" id="formCambiarRiesgo">
                    <input type="hidden" name="id_empleado" id="modal_id_empleado">
                    
                    <div class="form-group">
                        <label>Empleado:</label>
                        <div id="modal_nombre_empleado" style="font-weight: bold; color: #007bff; padding: 8px; background-color: #f8f9fa; border-radius: 4px;"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="codigo_riesgo">Nuevo Nivel de Riesgo:</label>
                        <select name="codigo_riesgo" id="modal_codigo_riesgo" required>
                            <option value="">Seleccione un nivel de riesgo</option>
                            <?php foreach ($niveles_riesgo as $codigo => $info): ?>
                                <option value="<?php echo $codigo; ?>">
                                    Riesgo <?php echo $info['codigo']; ?> (<?php echo $info['porcentaje']; ?>%) - <?php echo $info['descripcion']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div style="text-align: center; margin-top: 20px;">
                        <button type="button" class="btn btn-secondary" onclick="cerrarModalRiesgo()">Cancelar</button>
                        <button type="submit" class="btn" style="margin-left: 10px;">💾 Guardar Cambio</button>
                    </div>
                </form>
            </div>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                <div>
                    <p><strong>Instrucciones:</strong></p>
                    <ul>
                        <li>Cada empleado debe tener un nivel de riesgo asignado</li>
                        <li>El nivel de riesgo determina el porcentaje de aporte ARL</li>
                        <li>Utilice el botón "Cambiar Riesgo" para asignar o modificar niveles</li>
                        <li>Los cambios se aplicarán inmediatamente a los cálculos</li>
                    </ul>
                </div>
                <div>
                    <p><strong>Niveles de Riesgo:</strong></p>
                    <ul>
                        <li><strong>Riesgo I (0.522%):</strong> Oficinas, administrativo</li>
                        <li><strong>Riesgo II (1.044%):</strong> Comercio, servicios</li>
                        <li><strong>Riesgo III (2.436%):</strong> Manufactura liviana</li>
                        <li><strong>Riesgo IV (4.350%):</strong> Industria pesada</li>
                        <li><strong>Riesgo V (6.960%):</strong> Minería, construcción</li>
                    </ul>
                </div>
            </div>
            <p><em>Sistema de Gestión de Riesgos ARL - Integrado con Seguridad Social</em></p>
        </div>
    </div>

    <script>
        function abrirModalRiesgo(idEmpleado, nombreEmpleado, riesgoActual) {
            document.getElementById('modal_id_empleado').value = idEmpleado;
            document.getElementById('modal_nombre_empleado').textContent = nombreEmpleado;
            
            // Seleccionar el riesgo actual si existe
            const select = document.getElementById('modal_codigo_riesgo');
            if (riesgoActual !== null) {
                select.value = riesgoActual;
            } else {
                select.value = '';
            }
            
            document.getElementById('modalRiesgo').style.display = 'block';
        }
        
        function cerrarModalRiesgo() {
            document.getElementById('modalRiesgo').style.display = 'none';
        }
        
        // Cerrar modal si se hace clic fuera de él
        window.onclick = function(event) {
            const modal = document.getElementById('modalRiesgo');
            if (event.target === modal) {
                cerrarModalRiesgo();
            }
        }
    </script>
</body>
</html>