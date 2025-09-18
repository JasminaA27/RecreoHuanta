<?php
$pageTitle = 'Gestión de Usuarios - Sistema Recreos';
$action = 'usuarios';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-people me-2"></i>
                Gestión de Usuarios
                <span class="badge bg-primary"><?php echo count($usuarios); ?></span>
            </h2>
            <a href="<?php echo BASE_URL; ?>index.php?action=usuarios&method=create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Usuario
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas rápidas -->

<!-- Filtros y búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <input type="hidden" name="action" value="usuarios">
            <div class="col-md-6">
                <label for="search" class="form-label">Buscar Usuario</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search"
                           placeholder="Buscar por nombre o usuario..."
                           value="<?php echo htmlspecialchars($search ?? ''); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="<?php echo BASE_URL; ?>index.php?action=usuarios" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Limpiar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de usuarios -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-table me-2"></i>
            Lista de Usuarios
            <?php if (!empty($search)): ?>
                <span class="text-muted">- Resultados para: "<?php echo htmlspecialchars($search); ?>"</span>
            <?php endif; ?>
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($usuarios)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>Fecha Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo $usuario['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong>
                                </td>
                                <td>
                                    <code><?php echo htmlspecialchars($usuario['username']); ?></code>
                                </td>
                                <td>
                                    <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=usuarios&method=change_status" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                        <input type="hidden" name="estado" value="<?php echo $usuario['estado'] === 'activo' ? 'inactivo' : 'activo'; ?>">
                                        
                                        <button type="submit" 
                                                class="btn btn-sm <?php echo $usuario['estado'] === 'activo' ? 'btn-success' : 'btn-danger'; ?>"
                                                <?php echo $usuario['id'] == $_SESSION['admin_id'] ? 'disabled title="No puedes cambiar tu propio estado"' : ''; ?>
                                                onclick="return confirm('¿Estás seguro de cambiar el estado de este usuario?')">
                                            <i class="bi bi-<?php echo $usuario['estado'] === 'activo' ? 'check-circle' : 'x-circle'; ?> me-1"></i>
                                            <?php echo ucfirst($usuario['estado']); ?>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y H:i', strtotime($usuario['fecha_creacion'])); ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo BASE_URL; ?>index.php?action=usuarios&method=edit&id=<?php echo $usuario['id']; ?>" 
                                           class="btn btn-outline-primary btn-sm"
                                           title="Editar usuario">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <?php if ($usuario['id'] != $_SESSION['admin_id']): ?>
                                            <form method="POST" 
                                                  action="<?php echo BASE_URL; ?>index.php?action=usuarios&method=delete" 
                                                  style="display: inline;"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                                <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                                <button type="submit" 
                                                        class="btn btn-outline-danger btn-sm"
                                                        title="Eliminar usuario">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-people fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">No hay usuarios registrados</h5>
                <p class="text-muted">
                    <?php if (!empty($search)): ?>
                        No se encontraron usuarios que coincidan con tu búsqueda.
                    <?php else: ?>
                        Comienza agregando un nuevo usuario.
                    <?php endif; ?>
                </p>
                <a href="<?php echo BASE_URL; ?>index.php?action=usuarios&method=create" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Crear Primer Usuario
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>