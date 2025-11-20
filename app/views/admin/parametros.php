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
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#legales">Legales</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#solidaridad">Fondo Solidaridad</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#retencion">Retención Fuente</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#historial">Historial</button></li>
    </ul>
    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="legales">
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
            <div class="alert alert-info mt-3">
                <b>Nota:</b> El auxilio de transporte solo se otorga a empleados cuyo salario mensual sea menor o igual a <b>2 salarios mínimos legales vigentes (SMLV)</b>.<br>
                Con el SMLV actual de $1,423,000, el límite es <b>$2,846,000</b>. Si el salario supera este valor, no se asigna auxilio de transporte, sin importar el cargo o rol.
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
                        <td><pre><?= htmlspecialchars($h['detalle']) ?></pre></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="/ZIGMA/public/index.php?url=Admin/exportarConfiguracion" class="btn btn-secondary">Exportar Configuración</a>
        </div>
    </div>
    <a href="/ZIGMA/public/index.php?url=Dashboard" class="btn btn-outline-secondary mt-3">Volver al Dashboard</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function agregarFilaSolidaridad() {
    var tabla = document.getElementById('tablaSolidaridad').getElementsByTagName('tbody')[0];
    var i = tabla.rows.length;
    var fila = tabla.insertRow();
    fila.innerHTML = `<td><input type="number" step="0.01" name="rangos[${i}][desde_smlv]" class="form-control" required min="0"></td>
        <td><input type="number" step="0.01" name="rangos[${i}][hasta_smlv]" class="form-control" required min="0"></td>
        <td><input type="number" step="0.01" name="rangos[${i}][porcentaje]" class="form-control" required min="0" max="100"></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">Eliminar</button></td>`;
}
function agregarFilaRetencion() {
    var tabla = document.getElementById('tablaRetencion').getElementsByTagName('tbody')[0];
    var i = tabla.rows.length;
    var fila = tabla.insertRow();
    fila.innerHTML = `<td><input type="number" step="0.01" name="tabla[${i}][desde_uvt]" class="form-control" required min="0"></td>
        <td><input type="number" step="0.01" name="tabla[${i}][hasta_uvt]" class="form-control" required min="0"></td>
        <td><input type="number" step="0.01" name="tabla[${i}][porcentaje]" class="form-control" required min="0" max="100"></td>
        <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">Eliminar</button></td>`;
}
function eliminarFila(btn) {
    btn.closest('tr').remove();
}
</script>
</body>
</html>
