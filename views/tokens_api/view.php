<?php
$pageTitle = 'Detalles del Token API - Sistema Recreos';
$action = 'tokens_api';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-key me-2"></i>
                Detalles del Token API
            </h2>
            <div>
                <a href="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=edit&id=<?php echo $token['id']; ?>" 
                   class="btn btn-outline-primary me-2">
                    <i class="bi bi-pencil me-2"></i>Editar
                </a>
                <a href="<?php echo BASE_URL; ?>index.php?action=tokens_api" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Información del Token</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">ID del Token</label>
                        <p><?php echo $token['id']; ?></p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Estado</label>
                        <p>
                            <span class="badge bg-<?php echo $token['estado'] ? 'success' : 'danger'; ?>">
                                <?php echo $token['estado'] ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </p>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-bold">Token</label>
                        <div class="card bg-dark text-light">
                            <div class="card-body">
                                <code class="fs-6"><?php echo htmlspecialchars($token['token']); ?></code>
                                <?php 
                                $tokenParts = explode('_', $token['token']);
                                if (count($tokenParts) === 3): 
                                ?>
                                <div class="mt-2">
                                    <small class="text-info">
                                        <strong>Parte aleatoria:</strong> <?php echo htmlspecialchars($tokenParts[0]); ?><br>
                                        <strong>Fecha:</strong> <?php echo htmlspecialchars($tokenParts[1]); ?> (<?php echo date('d/m/Y', strtotime($tokenParts[1])); ?>)<br>
                                        <strong>ID Cliente:</strong> <?php echo htmlspecialchars($tokenParts[2]); ?>
                                    </small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha de Registro</label>
                        <p><?php echo date('d/m/Y H:i', strtotime($token['fecha_reg'])); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información del Cliente</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre Completo</label>
                    <p><?php echo htmlspecialchars($token['nombre'] . ' ' . $token['apellido']); ?></p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">DNI</label>
                    <p><?php echo htmlspecialchars($token['dni']); ?></p>
                </div>
                
                <?php if (!empty($token['telefono'])): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Teléfono</label>
                    <p><?php echo htmlspecialchars($token['telefono']); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($token['correo'])): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Correo Electrónico</label>
                    <p><?php echo htmlspecialchars($token['correo']); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <!-- Botón de regenerar token -->
                    <form method="POST" 
                          action="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=regenerate" 
                          onsubmit="return confirm('¿Estás seguro de regenerar este token? El token anterior dejará de funcionar.')"
                          class="d-grid">
                        <input type="hidden" name="id" value="<?php echo $token['id']; ?>">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-arrow-repeat me-2"></i>Regenerar Token
                        </button>
                    </form>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=change_status" class="d-grid">
                        <input type="hidden" name="id" value="<?php echo $token['id']; ?>">
                        <input type="hidden" name="estado" value="<?php echo $token['estado'] ? '0' : '1'; ?>">
                        <button type="submit" class="btn btn-<?php echo $token['estado'] ? 'warning' : 'success'; ?>">
                            <i class="bi bi-power me-2"></i>
                            <?php echo $token['estado'] ? 'Desactivar Token' : 'Activar Token'; ?>
                        </button>
                    </form>
                    
                    <form method="POST" 
                          action="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=delete" 
                          onsubmit="return confirm('¿Estás seguro de eliminar este token?')"
                          class="d-grid">
                        <input type="hidden" name="id" value="<?php echo $token['id']; ?>">
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash me-2"></i>Eliminar Token
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Estadísticas de Uso -->
<?php if (isset($requestStats) && !empty($requestStats)): ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Últimas Solicitudes con este Token
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requestStats as $request): ?>
                                <tr>
                                    <td>
                                        <small><?php echo date('d/m/Y H:i', strtotime($request['fecha'])); ?></small>
                                    </td>
                                    <td>
                                        <?php if (!empty($request['tipo'])): ?>
                                            <span class="badge bg-info"><?php echo htmlspecialchars($request['tipo']); ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>index.php?action=count_request&method=view&id=<?php echo $request['id']; ?>" 
                                           class="btn btn-outline-info btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
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

<?php include __DIR__ . '/../layouts/footer.php'; ?>