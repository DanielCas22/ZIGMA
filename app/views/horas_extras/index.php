<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Horas Extras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- ...dashboard y tabla aquí... -->
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="mb-4 text-primary">Horas Extras</h2>
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center shadow border-primary border-2">
                        <div class="card-body">
                            <h5 class="card-title">Total de Registros</h5>
                            <p class="display-6 fw-bold">
                                <?= isset($horas) ? count(array_filter($horas, function($h){ return !empty($h['id_extras']); })) : 0 ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center shadow border-success border-2">
                        <div class="card-body">
                            <h5 class="card-title">Empleados con Horas Extras</h5>
                            <p class="display-6 fw-bold">
                                <?php 
                                $empleadosConHE = [];
                                if (!empty($horas)) {
                                    foreach ($horas as $h) {
                                        if (!empty($h['id_extras'])) {
                                            $empleadosConHE[$h['id_empleados']] = true;
                                        }
                                    }
                                }
                                echo count($empleadosConHE);
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center shadow border-info border-2">
                        <div class="card-body">
                            <h5 class="card-title">Año Actual</h5>
                            <p class="display-6 fw-bold">
                                <?= date('Y') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Gestión de Horas Extras</h4>
                        <div>
                            <a href="index.php?url=Empleado/create" class="btn btn-info me-2">
                                <i class="fa fa-user-plus"></i> Registrar Empleado
                            </a>
                            <a href="/ZIGMA/public/index.php?url=HorasExtras/create" class="btn btn-success">
                                <i class="fa fa-plus"></i> Agregar Horas Extras
                            </a>
                        </div>
                    </div>
                    <form class="row g-2 align-items-center mb-3" method="get" action="/ZIGMA/public/index.php">
                        <input type="hidden" name="url" value="HorasExtras">
                        <div class="col-md-6">
                            <select name="empleado_id" class="form-select">
                                <option value="">Filtrar por Empleado</option>
                                <?php if (!isset($empleados) || !is_array($empleados)) $empleados = [];
                                foreach ($empleados as $emp): ?>
                                    <option value="<?= $emp['id_empleados'] ?>" <?= (isset($empleado_id) && $empleado_id == $emp['id_empleados']) ? 'selected' : '' ?>><?= $emp['nombre'] ?> <?= isset($emp['apellidos']) ? $emp['apellidos'] : '' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                        <div class="col-auto">
                            <a href="/ZIGMA/public/index.php?url=HorasExtras" class="btn btn-secondary">Limpiar</a>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>Empleado</th>
                                    <th>ID Hora Extra</th>
                                    <th>Valor</th>
                                    <th>Cantidad</th>
                                    <th>Tipo</th>
                                    <th>Porcentaje</th>
                                    <th>Día</th>
                                    <th>Mes</th>
                                    <th>Año</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($horas)) :
                                    foreach ($horas as $h) : ?>
                                        <tr>
                                            <td><?= $h['nombre'] ?> <?= isset($h['apellidos']) ? $h['apellidos'] : '' ?></td>
                                            <?php if (!empty($h['id_extras'])): ?>
                                                <td><?= $h['id_extras'] ?></td>
                                                <td><?= $h['valor'] ?></td>
                                                <td><?= $h['cantidad'] ?></td>
                                                <td><?= $h['tipo'] ?></td>
                                                <td><?= $h['porcentaje'] ?></td>
                                                <td><?= $h['dia'] ?></td>
                                                <td><?= $h['mes'] ?></td>
                                                <td><?= $h['año'] ?></td>
                                                <td>
                                                    <a href="/ZIGMA/public/index.php?url=HorasExtras/edit/<?= $h['id_extras'] ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Editar</a>
                                                    <a href="/ZIGMA/public/index.php?url=HorasExtras/delete/<?= $h['id_extras'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este registro?');"><i class="fa fa-trash"></i> Eliminar</a>
                                                </td>
                                            <?php else: ?>
                                                <td colspan="8" class="text-center text-muted">Sin horas extras</td>
                                                <td>
                                                    <a href="/ZIGMA/public/index.php?url=HorasExtras/create&empleado_id=<?= $h['id_empleados'] ?>" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Agregar</a>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach;
                                else: ?>
                                    <tr><td colspan="10" class="text-center text-muted">No hay empleados registrados</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
