<!-- Header Component ZIGMA -->
<?php 
require_once __DIR__ . '/../../models/RolePermissions.php';
require_once __DIR__ . '/../../models/NotificacionModel.php';
$notificationCount = RolePermissions::getPendingHoursCount(); 
$notiCount = 0;
if (isset($_SESSION['user']['id_doc'])) {
    $notiModel = new NotificacionModel();
    $notiCount = $notiModel->obtenerNoLeidasCount($_SESSION['user']['id_doc']);
}
?>
<nav class="navbar navbar-expand-lg navbar-dark navbar-zigma">
    <div class="container-fluid">
        <!-- Logo eliminado -->
        <div class="d-flex align-items-center">
            <?php if (isset($pageTitle)): ?>
                <span class="navbar-text me-3 text-white">
                    <i class="fas fa-chevron-right me-2"></i><?= $pageTitle ?>
                </span>
            <?php endif; ?>
            <!-- Icono de bandeja de notificaciones -->
            <a href="/ZIGMA/public/index.php?url=Notificacion/index" class="btn btn-info btn-sm me-2 position-relative" title="Notificaciones">
                <i class="fas fa-bell"></i>
                <?php if ($notiCount > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?= $notiCount ?>
                    <span class="visually-hidden">notificaciones no leídas</span>
                </span>
                <?php endif; ?>
            </a>
            <!-- Notificaciones de horas extras pendientes -->
            <?php if ($notificationCount > 0 && RolePermissions::canAccess('horas_extras', 'approve')): ?>
                <a href="/ZIGMA/public/index.php?url=HorasExtras/pendientes" 
                   class="btn btn-warning btn-sm me-2 position-relative" 
                   title="<?php echo $notificationCount; ?> horas extras pendientes">
                    <i class="fas fa-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?php echo $notificationCount; ?>
                        <span class="visually-hidden">horas extras pendientes</span>
                    </span>
                </a>
            <?php endif; ?>
            <a href="/ZIGMA/public/index.php?url=Dashboard/index" class="btn btn-outline-light btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>
</nav>
