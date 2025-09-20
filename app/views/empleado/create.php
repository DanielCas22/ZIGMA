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
                    <form method="post" action="index.php?url=Empleado/store">
                        <div class="mb-3">
                            <label class="form-label">Nombres</label>
                            <input type="text" name="nombres" class="form-control" placeholder="Ej: Juan Carlos" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" placeholder="Ej: Ramírez López" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol o Cargo</label>
                            <select name="rol" class="form-select" required>
                                <option value="">Seleccione un rol</option>
                                <?php if (isset($roles) && is_array($roles)): ?>
                                    <?php foreach ($roles as $rol): ?>
                                        <option value="<?= htmlspecialchars($rol['nombre']) ?>"><?= htmlspecialchars($rol['nombre']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-success px-4">Registrar</button>
                            <a href="index.php?url=Empleado/index" class="btn btn-outline-secondary px-4">Cancelar</a>
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
