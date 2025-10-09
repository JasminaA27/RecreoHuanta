<?php
$pageTitle = 'Estadísticas de Solicitudes API - Sistema Recreos';
$action = 'count_request';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-graph-up me-2"></i>
                Estadísticas de Solicitudes API
            </h2>
            <div>
                <a href="<?php echo BASE_URL; ?>index.php?action=count_request&method=stats" class="btn btn-outline-info me-2">
                    <i class="bi bi-bar-chart me-2"></i>Estadísticas Detalladas
                </a>
                <a href="<?php echo BASE_URL; ?>index.php?action=count_request&method=tokens" class="btn btn-outline-primary">
                    <i class="bi bi-trophy me-2"></i>Top Tokens
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas principales -->
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
                        <p class="mb-0">Tokens Utilizados</p>
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
                        <h4 class="mb-0"><?php echo $stats['solicitudes_hoy'] ?? 0; ?></h4>
                        <p class="mb-0">Solicitudes Hoy</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Tokens -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-trophy me-2"></i>
                    Top 5 Tokens Más Utilizados
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($topTokens)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Token</th>
                                    <th>Cliente</th>
                                    <th>Solicitudes</th>
                                    <th>Última Solicitud</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topTokens as $token): ?>
                                    <tr>
                                        <td>
    <code class="token-display">
        <?php 
        $tokenParts = explode('_', $request['token']);
        if (count($tokenParts) === 3) {
            echo htmlspecialchars($tokenParts[0] . '..._' . $tokenParts[1] . '_' . $tokenParts[2]);
        } else {
            echo htmlspecialchars(substr($request['token'], 0, 15) . '...');
        }
        ?>
    </code>
</td>
                                        <td><?php echo htmlspecialchars($token['nombre'] . ' ' . $token['apellido']); ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?php echo $token['total_solicitudes']; ?></span>
                                        </td>
                                        <td>
                                            <small><?php echo date('d/m/Y H:i', strtotime($token['ultima_solicitud'])); ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-3">No hay datos de solicitudes disponibles.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <input type="hidden" name="action" value="count_request">
            <div class="col-md-3">
                <label for="search" class="form-label">Buscar</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search"
                           placeholder="Token, cliente, tipo..."
                           value="<?php echo htmlspecialchars($search ?? ''); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <label for="token_id" class="form-label">Filtrar por Token</label>
                <select class="form-select" id="token_id" name="token_id">
                    <option value="">Todos los tokens</option>
                    <?php foreach ($tokens as $token): ?>
                        <option value="<?php echo $token['id']; ?>" 
                                <?php echo ($token_id ?? '') == $token['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars(substr($token['token'], 0, 20) . '...'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="limit" class="form-label">Límite de resultados</label>
                <select class="form-select" id="limit" name="limit">
                    <option value="50" <?php echo ($limit ?? 100) == 50 ? 'selected' : ''; ?>>50</option>
                    <option value="100" <?php echo ($limit ?? 100) == 100 ? 'selected' : ''; ?>>100</option>
                    <option value="200" <?php echo ($limit ?? 100) == 200 ? 'selected' : ''; ?>>200</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    <?php if (!empty($search) || !empty($token_id)): ?>
                        <a href="<?php echo BASE_URL; ?>index.php?action=count_request" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Limpiar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de solicitudes -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-list me-2"></i>
            Historial de Solicitudes
            <span class="badge bg-primary"><?php echo count($requests); ?></span>
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($requests)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Token</th>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                            <tr>
                                <td><?php echo $request['id']; ?></td>
                                <td>
                                    <code class="token-display">
                                        <?php echo htmlspecialchars(substr($request['token'], 0, 15) . '...'); ?>
                                    </code>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($request['nombre'] . ' ' . $request['apellido']); ?>
                                </td>
                                <td>
                                    <?php if (!empty($request['tipo'])): ?>
                                        <span class="badge bg-info"><?php echo htmlspecialchars($request['tipo']); ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?php echo date('d/m/Y H:i', strtotime($request['fecha'])); ?></small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo BASE_URL; ?>index.php?action=count_request&method=view&id=<?php echo $request['id']; ?>" 
                                           class="btn btn-outline-info btn-sm"
                                           title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <form method="POST" 
                                              action="<?php echo BASE_URL; ?>index.php?action=count_request&method=delete" 
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Estás seguro de eliminar esta solicitud?')">
                                            <input type="hidden" name="id" value="<?php echo $request['id']; ?>">
                                            <button type="submit" 
                                                    class="btn btn-outline-danger btn-sm"
                                                    title="Eliminar solicitud">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-graph-up fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">No hay solicitudes registradas</h5>
                <p class="text-muted">
                    <?php if (!empty($search) || !empty($token_id)): ?>
                        No se encontraron solicitudes que coincidan con los filtros aplicados.
                    <?php else: ?>
                        Las solicitudes de la API aparecerán aquí cuando se realicen.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Limpieza de datos -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-trash me-2"></i>
            Limpieza de Datos
        </h5>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=count_request&method=cleanup" 
              onsubmit="return confirm('¿Estás seguro de eliminar las solicitudes antiguas? Esta acción no se puede deshacer.')">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="days" class="form-label">Eliminar solicitudes más antiguas que:</label>
                    <select class="form-select" id="days" name="days">
                        <option value="7">7 días</option>
                        <option value="30" selected>30 días</option>
                        <option value="90">90 días</option>
                        <option value="180">180 días</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-2"></i>Limpiar Solicitudes Antiguas
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>