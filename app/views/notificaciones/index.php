<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bandeja de Notificaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php $pageTitle = 'Bandeja de Notificaciones'; include __DIR__ . '/../components/navbar.php'; ?>
<div class="container py-4">
    <h2 class="mb-4">Bandeja de Notificaciones</h2>
    <?php if (empty($notificaciones)): ?>
        <div class="alert alert-info">No tienes notificaciones.</div>
    <?php else: ?>
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Mensaje</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notificaciones as $n): ?>
                <tr class="<?= $n['leida'] ? 'table-secondary' : '' ?>">
                    <td><?= date('d/m/Y H:i', strtotime($n['fecha_creacion'])) ?></td>
                    <td><?= htmlspecialchars($n['tipo']) ?></td>
                    <td><?= htmlspecialchars($n['mensaje']) ?></td>
                    <td>
                        <?php if ($n['url']): ?>
                        <a href="<?= $n['url'] ?>" class="btn btn-sm btn-primary">Ver</a>
                        <?php endif; ?>
                        <?php if (!$n['leida']): ?>
                        <a href="/ZIGMA/public/index.php?url=Notificacion/leer/<?= $n['id'] ?>" class="btn btn-sm btn-success">Marcar como leída</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
