<?php
$pageTitle = 'Gestión de Tokens API - Sistema Recreos';
$action = 'tokens_api';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-key me-2"></i>
                Gestión de Tokens API
                <span class="badge bg-primary"><?php echo count($tokens); ?></span>
            </h2>
            <a href="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Token
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?php echo $stats['total_tokens'] ?? 0; ?></h4>
                        <p class="mb-0">Total Tokens</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-key fs-1"></i>
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
                        <h4 class="mb-0"><?php echo $stats['tokens_activos'] ?? 0; ?></h4>
                        <p class="mb-0">Tokens Activos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle fs-1"></i>
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
                        <h4 class="mb-0"><?php echo $stats['tokens_inactivos'] ?? 0; ?></h4>
                        <p class="mb-0">Tokens Inactivos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-x-circle fs-1"></i>
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
                        <h4 class="mb-0"><?php echo $stats['clientes_activos'] ?? 0; ?></h4>
                        <p class="mb-0">Clientes Activos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <input type="hidden" name="action" value="tokens_api">
            <div class="col-md-4">
                <label for="search" class="form-label">Buscar Token</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search"
                           placeholder="Buscar por token, nombre..."
                           value="<?php echo htmlspecialchars($search ?? ''); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <label for="cliente_id" class="form-label">Filtrar por Cliente</label>
                <select class="form-select" id="cliente_id" name="cliente_id">
                    <option value="">Todos los clientes</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?php echo $cliente['id']; ?>" 
                                <?php echo ($cliente_id ?? '') == $cliente['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    <?php if (!empty($search) || !empty($cliente_id)): ?>
                        <a href="<?php echo BASE_URL; ?>index.php?action=tokens_api" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Limpiar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de tokens -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-table me-2"></i>
            Lista de Tokens API
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($tokens)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Token</th>
                            <th>Cliente</th>
                            <th>Fecha Registro</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tokens as $token): ?>
                            <tr>
                                <td><?php echo $token['id']; ?></td>
                                <td>
                                    <code class="token-display">
                                        <?php echo htmlspecialchars(substr($token['token'], 0, 20) . '...'); ?>
                                    </code>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($token['nombre'] . ' ' . $token['apellido']); ?></strong>
                                    <br>
                                    <small class="text-muted">DNI: <?php echo htmlspecialchars($token['dni']); ?></small>
                                </td>
                                <td>
                                    <small>
                                        <?php echo date('d/m/Y H:i', strtotime($token['fecha_reg'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=change_status" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $token['id']; ?>">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="estado" 
                                                   value="1"
                                                   <?php echo $token['estado'] ? 'checked' : ''; ?>
                                                   onchange="this.form.submit()">
                                            <label class="form-check-label">
                                                <?php echo $token['estado'] ? 'Activo' : 'Inactivo'; ?>
                                            </label>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                       
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=view&id=<?php echo $token['id']; ?>" 
                                           class="btn btn-outline-info btn-sm"
                                           title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <a href="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=edit&id=<?php echo $token['id']; ?>" 
                                           class="btn btn-outline-primary btn-sm"
                                           title="Editar token">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                      <!--   <form method="POST" 
                                              action="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=delete" 
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este token?')">
                                            <input type="hidden" name="id" value="<?php echo $token['id']; ?>">
                                            <button type="submit" 
                                                    class="btn btn-outline-danger btn-sm"
                                                    title="Eliminar token">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>-->
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-key fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">No hay tokens registrados</h5>
                <p class="text-muted">
                    <?php if (!empty($search) || !empty($cliente_id)): ?>
                        No se encontraron tokens que coincidan con los filtros aplicados.
                    <?php else: ?>
                        Comienza agregando un nuevo token API.
                    <?php endif; ?>
                </p>
                <a href="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=create" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Crear Primer Token
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>