<?php
$pageTitle = 'Estadísticas Detalladas - Sistema Recreos';
$action = 'count_request';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-bar-chart me-2"></i>
                Estadísticas Detalladas
            </h2>
            <a href="<?php echo BASE_URL; ?>index.php?action=count_request" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas generales -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?php echo $stats['total_solicitudes'] ?? 0; ?></h4>
                        <p class="mb-0">Total Solicitudes</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-cloud-arrow-down fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?php echo $stats['tokens_utilizados'] ?? 0; ?></h4>
                        <p class="mb-0">Tokens Únicos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-key fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?php echo $stats['dias_activos'] ?? 0; ?></h4>
                        <p class="mb-0">Días Activos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?php echo $stats['tipos_solicitud'] ?? 0; ?></h4>
                        <p class="mb-0">Tipos de Solicitud</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-list fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tipos de solicitud -->
<?php if (!empty($requestTypes)): ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-pie-chart me-2"></i>
                    Distribución por Tipo de Solicitud
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Total Solicitudes</th>
                                <th>Tokens Únicos</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalRequests = $stats['total_solicitudes'] ?? 1;
                            foreach ($requestTypes as $type): 
                                $percentage = ($type['total'] / $totalRequests) * 100;
                            ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-info"><?php echo htmlspecialchars($type['tipo']); ?></span>
                                    </td>
                                    <td><?php echo $type['total']; ?></td>
                                    <td><?php echo $type['tokens_unicos']; ?></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" 
                                                 role="progressbar" 
                                                 style="width: <?php echo $percentage; ?>%"
                                                 aria-valuenow="<?php echo $percentage; ?>" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                                <?php echo number_format($percentage, 1); ?>%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Estadísticas diarias -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-week me-2"></i>
                    Actividad de los Últimos 30 Días
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($dailyStats)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Total Solicitudes</th>
                                    <th>Tokens Activos</th>
                                    <th>Promedio por Token</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dailyStats as $day): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($day['fecha'])); ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?php echo $day['total_solicitudes']; ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success"><?php echo $day['tokens_activos']; ?></span>
                                        </td>
                                        <td>
                                            <?php 
                                            $avg = $day['tokens_activos'] > 0 ? $day['total_solicitudes'] / $day['tokens_activos'] : 0;
                                            echo number_format($avg, 1);
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-3">No hay datos de actividad disponibles.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>