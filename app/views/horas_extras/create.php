
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Horas Extras</title>
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
                            <i class="fa-solid fa-clock fa-2x"></i>
                        </span>
                        <h2 class="mb-0 text-primary">Agregar Horas Extras</h2>
                        <p class="text-muted">Registra las horas extras de un empleado</p>
                    </div>
                    <form method="post" action="">
                        <div class="mb-3">
                            <label class="form-label">Empleado</label>
                            <select name="empleado_id" class="form-select" required>
                                <option value="">Seleccione un empleado</option>
                                <?php 
                                $selectedEmpleado = isset($_GET['empleado_id']) ? $_GET['empleado_id'] : (isset($_POST['empleado_id']) ? $_POST['empleado_id'] : '');
                                foreach ($empleados as $emp): ?>
                                    <option value="<?= $emp['id_empleados'] ?>" <?= ($selectedEmpleado == $emp['id_empleados']) ? 'selected' : '' ?>><?= $emp['nombre'] ?> <?= isset($emp['apellidos']) ? $emp['apellidos'] : '' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Valor</label>
                            <input type="number" name="valor" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="text" name="cantidad" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <input type="text" name="tipo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Porcentaje</label>
                            <input type="text" name="porcentaje" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Día</label>
                            <input type="text" name="dia" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mes</label>
                            <input type="text" name="mes" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Año</label>
                            <input type="text" name="año" class="form-control" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success px-4">Guardar</button>
                            <a href="/ZIGMA/public/index.php?url=HorasExtras" class="btn btn-outline-secondary px-4">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
