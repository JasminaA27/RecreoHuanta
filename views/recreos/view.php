<?php
$pageTitle = 'Detalle Recreo - Sistema Recreos';
$action = 'recreos';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?php echo BASE_URL; ?>index.php?action=dashboard">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?php echo BASE_URL; ?>index.php?action=recreos">Recreos</a>
                </li>
                <li class="breadcrumb-item active">Detalle Recreo</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-geo-alt-fill me-2"></i>
                Detalle del Recreo
                <span class="text-muted fs-6">#<?php echo $recreo['id']; ?></span>
            </h2>
            <div>
                <a href="<?php echo BASE_URL; ?>index.php?action=recreos" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-building me-2"></i>
                    Información del Recreo
                </h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nombre</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($recreo['nombre']); ?></dd>

                    <dt class="col-sm-3">Dirección</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($recreo['direccion']); ?></dd>

                    <dt class="col-sm-3">Referencia</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($recreo['referencia']); ?></dd>

                    <dt class="col-sm-3">Teléfono</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($recreo['telefono']); ?></dd>

                    <dt class="col-sm-3">Ubicación</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($recreo['ubicacion']); ?></dd>

                    <dt class="col-sm-3">Servicios</dt>
                    <dd class="col-sm-9"><?php echo nl2br(htmlspecialchars($recreo['servicio'])); ?></dd>

                    <dt class="col-sm-3">Precio</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($recreo['precio']); ?></dd>

                    <dt class="col-sm-3">Horario</dt>
                    <dd class="col-sm-9"><?php echo htmlspecialchars($recreo['horario']); ?></dd>

                    <dt class="col-sm-3">URL Google Maps</dt>
                    <dd class="col-sm-9">
                        <?php if (!empty($recreo['url_maps'])): ?>
                            <a href="<?php echo htmlspecialchars($recreo['url_maps']); ?>" target="_blank">
                                Ver en Google Maps
                            </a>
                        <?php else: ?>
                            <span class="text-muted">No disponible</span>
                        <?php endif; ?>
                    </dd>

                    <dt class="col-sm-3">Estado</dt>
                    <dd class="col-sm-9">
                        <?php if ($recreo['estado'] === 'activo'): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php elseif ($recreo['estado'] === 'pendiente'): ?>
                            <span class="badge bg-warning text-dark">Pendiente</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactivo</span>
                        <?php endif; ?>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
