<?php
$pageTitle = 'Top Tokens - Sistema Recreos';
$action = 'count_request';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-trophy me-2"></i>
                Top Tokens Más Utilizados
            </h2>
            <a href="<?php echo BASE_URL; ?>index.php?action=count_request" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <input type="hidden" name="action" value="count_request">
            <input type="hidden" name="method" value="tokens">
            <div class="col-md-4">
                <label for="limit" class="form-label">Número de tokens a mostrar</label>
                <select class="form-select" id="limit" name="limit" onchange="this.form.submit()">
                    <option value="10" <?php echo ($limit ?? 20) == 10 ? 'selected' : ''; ?>>Top 10</option>
                    <option value="20" <?php echo ($limit ?? 20) == 20 ? 'selected' : ''; ?>>Top 20</option>
                    <option value="50" <?php echo ($limit ?? 20) == 50 ? 'selected' : ''; ?>>Top 50</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de top tokens -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-list-ol me-2"></i>
            Ranking de Tokens por Uso
            <span class="badge bg-primary"><?php echo count($topTokens); ?></span>
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($topTokens)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Posición</th>
                            <th>Token</th>
                            <th>Cliente</th>
                            <th>Total Solicitudes</th>
                            <th>Última Solicitud</th>
                            <th>Promedio Diario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $position = 1;
                        foreach ($topTokens as $token): 
                            $daysActive = max(1, round((time() - strtotime($token['ultima_solicitud'])) / (60 * 60 * 24)));
                            $dailyAvg = $token['total_solicitudes'] / $daysActive;
                        ?>
                            <tr>
                                <td>
                                    <?php if ($position == 1): ?>
                                        <span class="badge bg-warning fs-6">🥇 <?php echo $position; ?></span>
                                    <?php elseif ($position == 2): ?>
                                        <span class="badge bg-secondary fs-6">🥈 <?php echo $position; ?></span>
                                    <?php elseif ($position == 3): ?>
                                        <span class="badge bg-danger fs-6">🥉 <?php echo $position; ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark"><?php echo $position; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <code class="token-display">
                                        <?php echo htmlspecialchars(substr($token['token'], 0, 20) . '...'); ?>
                                    </code>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($token['nombre'] . ' ' . $token['apellido']); ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary fs-6"><?php echo $token['total_solicitudes']; ?></span>
                                </td>
                                <td>
                                    <small><?php echo date('d/m/Y H:i', strtotime($token['ultima_solicitud'])); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo number_format($dailyAvg, 1); ?>/día</span>
                                </td>
                            </tr>
                        <?php 
                            $position++;
                        endforeach; 
                        ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-trophy fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">No hay datos de tokens disponibles</h5>
                <p class="text-muted">Los tokens aparecerán aquí cuando se realicen solicitudes a la API.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>