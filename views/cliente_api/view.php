<?php
$pageTitle = 'Detalles del Cliente API - Sistema Recreos';
$action = 'cliente_api';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-person me-2"></i>
                Detalles del Cliente API
            </h2>
            <div>
                <a href="<?php echo BASE_URL; ?>index.php?action=cliente_api&method=edit&id=<?php echo $cliente['id']; ?>" 
                   class="btn btn-outline-primary me-2">
                    <i class="bi bi-pencil me-2"></i>Editar
                </a>
                <a href="<?php echo BASE_URL; ?>index.php?action=cliente_api" class="btn btn-outline-secondary">
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
                <h5 class="mb-0">Información Personal</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">ID del Cliente</label>
                        <p><?php echo $cliente['id']; ?></p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Estado</label>
                        <p>
                            <span class="badge bg-<?php echo $cliente['estado'] ? 'success' : 'danger'; ?>">
                                <?php echo $cliente['estado'] ? 'Activo' : 'Inactivo'; ?>
                            </span>
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">DNI</label>
                        <p>
                            <code><?php echo htmlspecialchars($cliente['dni']); ?></code>
                        </p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha de Registro</label>
                        <p><?php echo date('d/m/Y H:i', strtotime($cliente['fecha_registro'])); ?></p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nombre</label>
                        <p><?php echo htmlspecialchars($cliente['nombre']); ?></p>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Apellido</label>
                        <p><?php echo htmlspecialchars($cliente['apellido']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información de Contacto</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($cliente['telefono'])): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Teléfono</label>
                    <p>
                        <i class="bi bi-telephone me-2"></i>
                        <?php echo htmlspecialchars($cliente['telefono']); ?>
                    </p>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($cliente['correo'])): ?>
                <div class="mb-3">
                    <label class="form-label fw-bold">Correo Electrónico</label>
                    <p>
                        <i class="bi bi-envelope me-2"></i>
                        <?php echo htmlspecialchars($cliente['correo']); ?>
                    </p>
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
                    <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=cliente_api&method=change_status" class="d-grid">
                        <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
                        <input type="hidden" name="estado" value="<?php echo $cliente['estado'] ? '0' : '1'; ?>">
                        <button type="submit" class="btn btn-<?php echo $cliente['estado'] ? 'warning' : 'success'; ?>">
                            <i class="bi bi-power me-2"></i>
                            <?php echo $cliente['estado'] ? 'Desactivar Cliente' : 'Activar Cliente'; ?>
                        </button>
                    </form>
                    
                    <form method="POST" 
                          action="<?php echo BASE_URL; ?>index.php?action=cliente_api&method=delete" 
                          onsubmit="return confirm('¿Estás seguro de eliminar este cliente API?')"
                          class="d-grid">
                        <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash me-2"></i>Eliminar Cliente
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Tokens del Cliente -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-key me-2"></i>
                    Tokens Asociados
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($tokens)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Token</th>
                                    <th>Estado</th>
                                    <th>Fecha de Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tokens as $token): ?>
                                    <tr>
                                        <td>
                                            <code class="token-display">
                                                <?php echo htmlspecialchars(substr($token['token'], 0, 20) . '...'); ?>
                                            </code>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $token['estado'] ? 'success' : 'danger'; ?>">
                                                <?php echo $token['estado'] ? 'Activo' : 'Inactivo'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small><?php echo date('d/m/Y H:i', strtotime($token['fecha_reg'])); ?></small>
                                        </td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=view&id=<?php echo $token['id']; ?>" 
                                               class="btn btn-outline-info btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-3">Este cliente no tiene tokens asociados.</p>
                    <div class="text-center">
                        <a href="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=create" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Crear Primer Token
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>