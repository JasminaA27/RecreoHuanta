<?php
require_once 'models/Usuario.php';
require_once 'models/Recreo.php';

$pageTitle = 'Dashboard - Sistema Recreos';
$action = 'dashboard';

// Obtener estadísticas
$usuarioModel = new Usuario();
$recreoModel = new Recreo();

$statsUsuarios = $usuarioModel->getStats();
$statsRecreos = $recreoModel->getStats();
$statsByUbicacion = $recreoModel->getStatsByUbicacion();
$recreosRecientes = $recreoModel->getRecent(8);

// Obtener estadísticas específicas por ubicación
$recreosHuanta = 0;
$recreosLuricocha = 0;

foreach ($statsByUbicacion as $stat) {
    if ($stat['ubicacion'] === 'Huanta') {
        $recreosHuanta = $stat['activos'];
    }
    if ($stat['ubicacion'] === 'Luricocha') {
        $recreosLuricocha = $stat['activos'];
    }
}

include 'layouts/header.php';
?>

<!-- Header del Dashboard -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="text-gradient mb-1">Inicio</h1>
        <p class="text-muted mb-0">Bienvenido de vuelta, <?php echo $_SESSION['admin_nombre'] ?? 'Admin'; ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?php echo BASE_URL; ?>index.php?action=recreos&method=create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Nuevo Recreo
        </a>
        <a href="<?php echo BASE_URL; ?>index.php?action=usuarios&method=create" class="btn btn-outline-primary">
            <i class="bi bi-person-plus"></i>
            Nuevo Usuario
        </a>
    </div>
</div>

<!-- Estadísticas Principales -->
<div class="row g-4 mb-4">
    <!-- Total Recreos -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
        <div class="stats-card">
            <div class="stats-icon primary">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
            <div class="stats-number"><?php echo $statsRecreos['total_recreos']; ?></div>
            <div class="stats-label">Total Recreos</div>
        </div>
    </div>

    <!-- Recreos en Huanta -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
        <div class="stats-card success">
            <div class="stats-icon success">
                <i class="bi bi-building"></i>
            </div>
            <div class="stats-number"><?php echo $recreosHuanta; ?></div>
            <div class="stats-label">Recreos en Huanta</div>
        </div>
    </div>

    <!-- Recreos en Luricocha -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
        <div class="stats-card info">
            <div class="stats-icon info">
                <i class="bi bi-trees"></i>
            </div>
            <div class="stats-number"><?php echo $recreosLuricocha; ?></div>
            <div class="stats-label">Recreos en Luricocha</div>
        </div>
    </div>

    <!-- Recreos Activos -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
        <div class="stats-card success">
            <div class="stats-icon success">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stats-number"><?php echo $statsRecreos['recreos_activos']; ?></div>
            <div class="stats-label">Recreos Activos</div>
        </div>
    </div>

    <!-- Recreos Inactivos -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
        <div class="stats-card danger">
            <div class="stats-icon danger">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <div class="stats-number"><?php echo $statsRecreos['recreos_inactivos']; ?></div>
            <div class="stats-label">Recreos Inactivos</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Estadísticas por Ubicación -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center">
                <div class="stats-icon primary me-3" style="width: 2rem; height: 2rem; font-size: 1rem;">
                    <i class="bi bi-bar-chart-fill"></i>
                </div>
                <div>
                    <h5 class="mb-0">Recreos por Ubicación</h5>
                    <small class="text-muted">Distribución por distrito</small>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($statsByUbicacion)): ?>
                    <div class="space-y-4">
                        <?php foreach ($statsByUbicacion as $index => $stat): ?>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="stats-icon <?php echo ['primary', 'success', 'warning', 'info', 'danger'][$index % 5]; ?> me-2" 
                                             style="width: 1.5rem; height: 1.5rem; font-size: 0.75rem;">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </div>
                                        <span class="fw-semibold"><?php echo htmlspecialchars($stat['ubicacion']); ?></span>
                                    </div>
                                    <span class="badge bg-<?php echo ['primary', 'success', 'warning', 'info', 'danger'][$index % 5]; ?>">
                                        <?php echo $stat['total_recreos']; ?>
                                    </span>
                                </div>
                                <div class="progress mb-1" style="height: 8px;">
                                    <?php 
                                    $percentage = ($stat['total_recreos'] / $statsRecreos['total_recreos']) * 100;
                                    $colorClass = ['primary', 'success', 'warning', 'info', 'danger'][$index % 5];
                                    ?>
                                    <div class="progress-bar bg-<?php echo $colorClass; ?>" 
                                         role="progressbar" 
                                         style="width: <?php echo $percentage; ?>%"
                                         aria-valuenow="<?php echo $percentage; ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <?php echo $stat['activos']; ?> activos de <?php echo $stat['total_recreos']; ?> total
                                </small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-bar-chart text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No hay datos disponibles</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recreos Recientes -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="stats-icon success me-3" style="width: 2rem; height: 2rem; font-size: 1rem;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Recreos Recientes</h5>
                        <small class="text-muted">Últimos registros añadidos</small>
                    </div>
                </div>
                <a href="<?php echo BASE_URL; ?>index.php?action=recreos" class="btn btn-sm btn-outline-primary">
                    Ver todos
                </a>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                <?php if (!empty($recreosRecientes)): ?>
                    <ul class="recent-list">
                        <?php foreach ($recreosRecientes as $recreo): ?>
                            <li class="recent-list-item">
                                <div class="recent-item-content">
                                    <h6 class="recent-item-title">
                                        <?php echo htmlspecialchars($recreo['nombre']); ?>
                                    </h6>
                                    <p class="recent-item-subtitle">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        <?php echo htmlspecialchars($recreo['ubicacion']); ?>
                                    </p>
                                    <div class="recent-item-meta">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?php echo date('d/m/Y', strtotime($recreo['fecha_creacion'])); ?>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="badge bg-<?php echo $recreo['estado'] === 'activo' ? 'success' : ($recreo['estado'] === 'pendiente' ? 'warning' : 'danger'); ?> mb-1">
                                        <?php echo ucfirst($recreo['estado']); ?>
                                    </span>
                                    <?php if ($recreo['precio'] > 0): ?>
                                        <small class="text-success fw-semibold">
                                            S/ <?php echo number_format($recreo['precio'], 2); ?>
                                        </small>
                                    <?php else: ?>
                                        <small class="text-muted">Gratis</small>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-clock-history text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2 mb-3">No hay recreos registrados</p>
                        <a href="<?php echo BASE_URL; ?>index.php?action=recreos&method=create" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>
                            Crear primer recreo
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Información del Sistema -->
    <div class="col-lg-3">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center">
                <div class="stats-icon info me-3" style="width: 2rem; height: 2rem; font-size: 1rem;">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <div>
                    <h5 class="mb-0">Información</h5>
                    <small class="text-muted">Resumen del sistema</small>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column gap-3">
                  

                    <div class="d-flex align-items-center p-3 bg-light rounded">
                        <div class="stats-icon warning me-3" style="width: 2rem; height: 2rem; font-size: 1rem;">
                            <i class="bi bi-pin-map-fill"></i>
                        </div>
                        <div>
                            <div class="fw-semibold"><?php echo $statsRecreos['ubicaciones_unicas']; ?></div>
                            <small class="text-muted">Ubicaciones Únicas</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center p-3 bg-light rounded">
                        <div class="stats-icon primary me-3" style="width: 2rem; height: 2rem; font-size: 1rem;">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <div class="fw-semibold"><?php echo $statsUsuarios['total']; ?></div>
                            <small class="text-muted">Total Usuarios</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center p-3 bg-light rounded">
                        <div class="stats-icon info me-3" style="width: 2rem; height: 2rem; font-size: 1rem;">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div>
                            <div class="fw-semibold"><?php echo date('H:i', $_SESSION['login_time'] ?? time()); ?></div>
                            <small class="text-muted">Último Login</small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="text-center">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="<?php echo BASE_URL; ?>index.php?action=recreos" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-geo-alt"></i>
                                Ver Recreos
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?php echo BASE_URL; ?>index.php?action=usuarios" class="btn btn-outline-secondary btn-sm w-100">
                                <i class="bi bi-people"></i>
                                Ver Usuarios
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'layouts/footer.php'; ?>