
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2 class="mb-4 text-center">Editar Horas Extras</h2>
                    <form method="post" action="">
                        <div class="mb-3">
                            <label class="form-label">Empleado</label>
                            <select name="empleado_id" class="form-select" required>
                                <option value="">Seleccione un empleado</option>
                                <?php foreach ($empleados as $emp): ?>
                                    <option value="<?= $emp['id_empleados'] ?>" <?= $emp['id_empleados'] == $hora['empleado_id'] ? 'selected' : '' ?>><?= $emp['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Valor</label>
                            <input type="number" name="valor" class="form-control" value="<?= $hora['valor'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="text" name="cantidad" class="form-control" value="<?= $hora['cantidad'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <input type="text" name="tipo" class="form-control" value="<?= $hora['tipo'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Porcentaje</label>
                            <input type="text" name="porcentaje" class="form-control" value="<?= $hora['porcentaje'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Día</label>
                            <input type="text" name="dia" class="form-control" value="<?= $hora['dia'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mes</label>
                            <input type="text" name="mes" class="form-control" value="<?= $hora['mes'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Año</label>
                            <input type="text" name="año" class="form-control" value="<?= $hora['año'] ?>" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <a href="/ZIGMA/public/index.php?url=HorasExtras" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
