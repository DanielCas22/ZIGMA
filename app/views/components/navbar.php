<!-- Header Component ZIGMA -->
<?php 
require_once __DIR__ . '/../../models/RolePermissions.php';
$notificationCount = RolePermissions::getPendingHoursCount(); 
?>
<nav class="navbar navbar-expand-lg navbar-dark navbar-zigma">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="/ZIGMA/public/index.php?url=Dashboard/index">
            <img src="/ZIGMA/public/img/logo_zigma.jpg" alt="Logo ZIGMA" style="height:40px; width:auto; margin-right:10px; border-radius:5px;">
            <span>ZIGMA</span>
        </a>
        <div class="d-flex align-items-center">
            <?php if (isset($pageTitle)): ?>
                <span class="navbar-text me-3 text-white">
                    <i class="fas fa-chevron-right me-2"></i><?= $pageTitle ?>
                </span>
            <?php endif; ?>
            
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
