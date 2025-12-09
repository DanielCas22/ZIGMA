<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Parámetros Administrativos - ZIGMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2 class="mb-4">Parámetros Administrativos</h2>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <ul class="nav nav-tabs" id="paramTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#generales">Parámetros Generales</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#legales">Legales</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#solidaridad">Fondo Solidaridad</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#retencion">Retención Fuente</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#historial">Historial</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#horasextras">Horas Extras</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#aportes">Aportes y Parafiscales</button></li>
    </ul>
    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="generales">
            <form method="post" action="/ZIGMA/public/index.php?url=Admin/guardarParametrosGenerales" onsubmit="return confirm('¿Guardar cambios en parámetros generales?');">
                <h5>Parámetros Generales del Sistema</h5>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>UVT (Unidad de Valor Tributario)</label>
                        <input type="number" step="0.01" name="uvt" class="form-control" required min="0" value="<?= htmlspecialchars($parametrosGenerales['uvt'] ?? 45286.00) ?>" placeholder="Ej: 45286.00">
                        <small class="form-text text-muted">Valor tributario actual para cálculo de retención</small>
                    </div>
                    <div class="col-md-4">
                        <label>SMLV (Salario Mínimo Legal Vigente)</label>
                        <input type="number" step="1" name="smlv" class="form-control" required min="0" value="<?= htmlspecialchars($parametrosGenerales['smlv'] ?? 1300000) ?>" placeholder="Ej: 1300000">
                        <small class="form-text text-muted">Salario mínimo legal vigente en COP</small>
                    </div>
                    <div class="col-md-4">
                        <label>Año de Vigencia</label>
                        <input type="number" name="ano_vigencia" class="form-control" required min="2000" value="<?= htmlspecialchars($parametrosGenerales['ano_vigencia'] ?? date('Y')) ?>">
                    </div>
                </div>
                
                <hr>
                <h6>Período de Pago</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Tipo de Período</label>
                        <select name="periodo_pago" class="form-select" required>
                            <option value="semanal" <?= ($parametrosGenerales['periodo_pago'] ?? 'mensual') === 'semanal' ? 'selected' : '' ?>>Semanal</option>
                            <option value="quincenal" <?= ($parametrosGenerales['periodo_pago'] ?? 'mensual') === 'quincenal' ? 'selected' : '' ?>>Quincenal</option>
                            <option value="mensual" <?= ($parametrosGenerales['periodo_pago'] ?? 'mensual') === 'mensual' ? 'selected' : '' ?>>Mensual</option>
                        </select>
                        <small class="form-text text-muted">Frecuencia de pago de nómina</small>
                    </div>
                </div>

                <hr>
                <h6>Formato de Nómina y Reportes</h6>
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label>Símbolo Divisa</label>
                        <input type="text" name="formato_divisa" class="form-control" required maxlength="5" value="<?= htmlspecialchars($parametrosGenerales['formato_divisa'] ?? '$') ?>" placeholder="$">
                        <small class="form-text text-muted">Símbolo de moneda (Ej: $, COP)</small>
                    </div>
                    <div class="col-md-2">
                        <label>Decimales</label>
                        <select name="formato_decimales" class="form-select" required>
                            <option value="0" <?= ($parametrosGenerales['formato_decimales'] ?? 2) == 0 ? 'selected' : '' ?>>0 decimales</option>
                            <option value="1" <?= ($parametrosGenerales['formato_decimales'] ?? 2) == 1 ? 'selected' : '' ?>>1 decimal</option>
                            <option value="2" <?= ($parametrosGenerales['formato_decimales'] ?? 2) == 2 ? 'selected' : '' ?>>2 decimales</option>
                            <option value="3" <?= ($parametrosGenerales['formato_decimales'] ?? 2) == 3 ? 'selected' : '' ?>>3 decimales</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Separador Miles</label>
                        <select name="formato_miles" class="form-select" required>
                            <option value="." <?= ($parametrosGenerales['formato_miles'] ?? '.') === '.' ? 'selected' : '' ?>>Punto (.)</option>
                            <option value="," <?= ($parametrosGenerales['formato_miles'] ?? '.') === ',' ? 'selected' : '' ?>>Coma (,)</option>
                            <option value=" " <?= ($parametrosGenerales['formato_miles'] ?? '.') === ' ' ? 'selected' : '' ?>>Espacio ( )</option>
                        </select>
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <strong>Ejemplo de formato:</strong> <span id="ejemploFormato">$1.300.000</span>
                </div>

                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
        <div class="tab-pane fade" id="legales">
            <div class="row mb-3 align-items-end">
                <div class="col-md-4">
                    <form method="post" action="/ZIGMA/public/index.php?url=Admin/guardarSalarioRol" class="d-flex align-items-end gap-2" id="formSalarioRol" onsubmit="return confirm('¿Guardar salario base para el rol seleccionado?');">
                        <label class="form-label mb-0 me-2">Salario Base por Rol</label>
                        <select name="rol" id="rolSalarioSelect" class="form-select" style="max-width: 160px;" onchange="actualizarSalarioRolInput()">
                            <?php foreach ((new \App\Models\Rol())->getAll() as $rol): ?>
                                <option value="<?= htmlspecialchars($rol['nombre']) ?>"><?= htmlspecialchars(ucfirst($rol['nombre'])) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" step="1" name="salario_rol" id="salarioRolInput" class="form-control" style="max-width: 140px;" min="0" required placeholder="Salario base">
                        <button type="submit" class="btn btn-outline-primary">Guardar</button>
                    </form>
                </div>
            </div>
            <form method="post" action="/ZIGMA/public/index.php?url=Admin/guardarAuxilioTransporte" onsubmit="return confirm('¿Guardar cambios en auxilio de transporte?');">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Auxilio Transporte</label>
                        <input type="number" step="0.01" name="auxilio_transporte" class="form-control" required min="0" value="<?= htmlspecialchars($parametros[0]['auxilio_transporte'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Año Vigencia</label>
                        <input type="number" name="año_vigencia" class="form-control" required min="2000" value="<?= htmlspecialchars($parametros[0]['año_vigencia'] ?? '') ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
            <div class="alert alert-info mt-3" id="notaAuxilio">
                <b>Nota:</b> El auxilio de transporte solo se otorga a empleados cuyo salario mensual sea menor o igual a <b>2 salarios mínimos legales vigentes (SMLV)</b>.<br>
                Con el SMLV actual de $<span id="smlvActual">
                <?php 
                // Determinar el salario mínimo vigente según el menor salario base por rol
                $salarios = array_map(function($r) { return $r['salario']; }, (new \App\Models\SalarioPorRol())->getAll());
                $smlv = $salarios ? min($salarios) : 0;
                echo number_format($smlv, 0, ',', '.');
                ?>
                </span>, el límite es <b>$<span id="limiteAuxilio">
                <?php echo number_format(($smlv * 2), 0, ',', '.'); ?></span></b>. Si el salario supera este valor, no se asigna auxilio de transporte, sin importar el cargo o rol.
            </div>
        </div>
        <div class="tab-pane fade" id="solidaridad">
            <form method="post" action="/ZIGMA/public/index.php?url=Admin/guardarRangosSolidaridad" onsubmit="return confirm('¿Guardar cambios en rangos de solidaridad?');">
                <h5>Rangos Fondo de Solidaridad</h5>
                <table class="table table-bordered" id="tablaSolidaridad">
                    <thead><tr><th>Desde SMLV</th><th>Hasta SMLV</th><th>Porcentaje</th><th>Acciones</th></tr></thead>
                    <tbody>
                        <?php foreach ($rangosSolidaridad as $i => $r): ?>
                        <tr>
                            <td><input type="number" step="0.01" name="rangos[<?= $i ?>][desde_smlv]" class="form-control" required min="0" value="<?= htmlspecialchars($r['desde_smlv']) ?>"></td>
                            <td><input type="number" step="0.01" name="rangos[<?= $i ?>][hasta_smlv]" class="form-control" required min="0" value="<?= htmlspecialchars($r['hasta_smlv']) ?>"></td>
                            <td><input type="number" step="0.01" name="rangos[<?= $i ?>][porcentaje]" class="form-control" required min="0" max="100" value="<?= htmlspecialchars($r['porcentaje']) ?>"></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">Eliminar</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-success" onclick="agregarFilaSolidaridad()">Agregar Rango</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
        <div class="tab-pane fade" id="retencion">
            <form method="post" action="/ZIGMA/public/index.php?url=Admin/guardarTablaRetencion" onsubmit="return confirm('¿Guardar cambios en tabla de retención?');">
                <h5>Tabla Retención en la Fuente</h5>
                <table class="table table-bordered" id="tablaRetencion">
                    <thead><tr><th>Desde UVT</th><th>Hasta UVT</th><th>Porcentaje</th><th>Acciones</th></tr></thead>
                    <tbody>
                        <?php foreach ($tablaRetencion as $i => $t): ?>
                        <tr>
                            <td><input type="number" step="0.01" name="tabla[<?= $i ?>][desde_uvt]" class="form-control" required min="0" value="<?= htmlspecialchars($t['desde_uvt']) ?>"></td>
                            <td><input type="number" step="0.01" name="tabla[<?= $i ?>][hasta_uvt]" class="form-control" required min="0" value="<?= htmlspecialchars($t['hasta_uvt']) ?>"></td>
                            <td><input type="number" step="0.01" name="tabla[<?= $i ?>][porcentaje]" class="form-control" required min="0" max="100" value="<?= htmlspecialchars($t['porcentaje']) ?>"></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">Eliminar</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-success" onclick="agregarFilaRetencion()">Agregar Rango</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
        <div class="tab-pane fade" id="historial">
            <h5>Historial de Modificaciones</h5>
            <table class="table table-bordered">
                <thead><tr><th>Fecha</th><th>Usuario</th><th>Acción</th><th>Detalle</th></tr></thead>
                <tbody>
                    <?php foreach ($historial as $h): ?>
                    <tr>
                        <td><?= htmlspecialchars($h['fecha']) ?></td>
                        <td><?= htmlspecialchars($h['actualizado_por']) ?></td>
                        <td><?= htmlspecialchars($h['accion']) ?></td>
                        <td>
                            <?php
                            $detalle = json_decode($h['detalle'], true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($detalle)) {
                                echo '<ul style="margin:0; padding-left:18px;">';
                                foreach ($detalle as $k => $v) {
                                    $k = ($k === 'anio') ? 'año' : $k;
                                    if (is_array($v)) {
                                        echo "<li><b>$k:</b><ul>";
                                        foreach ($v as $kk => $vv) {
                                            $kk = ($kk === 'anio') ? 'año' : $kk;
                                            echo "<li><b>$kk:</b> $vv</li>";
                                        }
                                        echo "</ul></li>";
                                    } else {
                                        echo "<li><b>$k:</b> $v</li>";
                                    }
                                }
                                echo '</ul>';
                            } else {
                                echo '<pre>' . htmlspecialchars(str_replace('"anio"', '"año"', $h['detalle'])) . '</pre>';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="/ZIGMA/public/index.php?url=Admin/exportarConfiguracion" class="btn btn-secondary">Exportar Configuración</a>
        </div>
        <div class="tab-pane fade" id="horasextras">
            <form method="post" action="/ZIGMA/public/index.php?url=Admin/guardarPorcentajesHorasExtras" onsubmit="return confirm('¿Guardar cambios en porcentajes de horas extras?');">
                <h5>Porcentajes de Horas Extras</h5>
                <table class="table table-bordered" id="tablaHorasExtras">
                    <thead><tr><th>Tipo de Hora Extra</th><th>Porcentaje</th><th>Acciones</th></tr></thead>
                    <tbody>
                        <?php foreach ((new \App\Models\TipoHoraExtra())->getAll() as $i => $h): ?>
                        <tr>
                            <td><input type="text" name="horas[<?= $i ?>][nombre]" class="form-control" required value="<?= htmlspecialchars($h['nombre']) ?>" readonly></td>
                            <td><input type="number" step="0.01" name="horas[<?= $i ?>][porcentaje]" class="form-control" required min="0" max="100" value="<?= htmlspecialchars($h['porcentaje']) ?>"></td>
                            <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">Eliminar</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-success" onclick="agregarFilaHorasExtras()">Agregar Tipo</button>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
        <div class="tab-pane fade" id="aportes">
            <form method="post" action="/ZIGMA/public/index.php?url=Admin/guardarAportes" onsubmit="return confirm('¿Guardar cambios en aportes y parafiscales?');">
                <h5>Valores de Seguridad Social, Prestaciones y Parafiscales</h5>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Salud (Empleador %)</label>
                        <input type="number" step="0.01" name="salud_empleador" class="form-control" required min="0" max="100" value="<?= htmlspecialchars($aportes['salud_empleador'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Salud (Empleado %)</label>
                        <input type="number" step="0.01" name="salud_empleado" class="form-control" required min="0" max="100" value="<?= htmlspecialchars($aportes['salud_empleado'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Pensión (Empleador %)</label>
                        <input type="number" step="0.01" name="pension_empleador" class="form-control" required min="0" max="100" value="<?= htmlspecialchars($aportes['pension_empleador'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Pensión (Empleado %)</label>
                        <input type="number" step="0.01" name="pension_empleado" class="form-control" required min="0" max="100" value="<?= htmlspecialchars($aportes['pension_empleador'] ?? '') ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Parafiscales (%)</label>
                        <input type="number" step="0.01" name="parafiscales" class="form-control" required min="0" max="100" value="<?= htmlspecialchars($aportes['parafiscales'] ?? '0') ?>">
                    </div>
                    <div class="col-md-3">
                        <label>SENA (%)</label>
                        <input type="number" step="0.01" name="sena" class="form-control" required min="0" max="100" value="<?= htmlspecialchars($aportes['sena'] ?? '0') ?>">
                    </div>
                    <div class="col-md-3">
                        <label>ICBF (%)</label>
                        <input type="number" step="0.01" name="icbf" class="form-control" required min="0" max="100" value="<?= htmlspecialchars($aportes['icbf'] ?? '0') ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Prestaciones Sociales (%)</label>
                        <input type="number" step="0.01" name="prestaciones" class="form-control" required min="0" max="100" value="<?= htmlspecialchars($aportes['prestaciones'] ?? '') ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </form>
        </div>
    </div>
    <a href="/ZIGMA/public/index.php?url=Dashboard" class="btn btn-outline-secondary mt-3">Volver al Dashboard</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Cargar salarios por rol desde PHP a JS
var salariosPorRol = <?php echo json_encode((new \App\Models\SalarioPorRol())->getAll()); ?>;
function actualizarSalarioRolInput() {
    var select = document.getElementById('rolSalarioSelect');
    var input = document.getElementById('salarioRolInput');
    var rol = select.value;
    var salario = '';
    salariosPorRol.forEach(function(item) {
        if(item.rol === rol) salario = item.salario;
    });
    input.value = salario;
}

function actualizarAuxilioNota() {
    // El SMLV será el menor salario base por rol
    var salarios = salariosPorRol.map(function(item){ return parseFloat(item.salario) || 0; });
    var smlv = Math.min.apply(null, salarios);
    document.getElementById('smlvActual').textContent = smlv.toLocaleString('es-CO');
    document.getElementById('limiteAuxilio').textContent = (smlv*2).toLocaleString('es-CO');
}

document.addEventListener('DOMContentLoaded', function() {
    actualizarSalarioRolInput();
    document.getElementById('rolSalarioSelect').addEventListener('change', actualizarSalarioRolInput);
    actualizarAuxilioNota();
    
    // Actualizar formato de ejemplo
    const formatoDivisaSelect = document.querySelector('input[name="formato_divisa"]');
    const formatoDecimalesSelect = document.querySelector('select[name="formato_decimales"]');
    const formatoMilesSelect = document.querySelector('select[name="formato_miles"]');
    
    if (formatoDivisaSelect && formatoDecimalesSelect && formatoMilesSelect) {
        function actualizarEjemplo() {
            const divisa = formatoDivisaSelect.value || '$';
            const decimales = parseInt(formatoDecimalesSelect.value) || 0;
            const miles = formatoMilesSelect.value || '.';
            
            const numero = 1300000;
            let formatted;
            
            if (miles === ' ') {
                formatted = numero.toLocaleString('de-DE', { minimumFractionDigits: decimales, maximumFractionDigits: decimales });
            } else if (miles === ',') {
                formatted = numero.toLocaleString('es-ES', { minimumFractionDigits: decimales, maximumFractionDigits: decimales });
            } else {
                formatted = numero.toLocaleString('en-US', { minimumFractionDigits: decimales, maximumFractionDigits: decimales })
                    .replace(/,/g, miles === ',' ? '@' : miles)
                    .replace(/@/g, ',');
            }
            
            document.getElementById('ejemploFormato').textContent = divisa + formatted;
        }
        
        formatoDivisaSelect.addEventListener('input', actualizarEjemplo);
        formatoDecimalesSelect.addEventListener('change', actualizarEjemplo);
        formatoMilesSelect.addEventListener('change', actualizarEjemplo);
    }
});
</script>
</body>
</html>
