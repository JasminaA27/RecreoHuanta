<?php
$pageTitle = 'Gestión de Recreos - Sistema Recreos';
$action = 'recreos';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-geo-alt me-2"></i>
                Gestión de Recreos
                <span class="badge bg-primary"><?php echo count($recreos); ?></span>
            </h2>
            <a href="<?php echo BASE_URL; ?>index.php?action=recreos&method=create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Recreo
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas rápidas -->


<!-- Filtros y búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <input type="hidden" name="action" value="recreos">
            <div class="col-md-4">
                <label for="search" class="form-label">Buscar Recreo</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search"
                           placeholder="Buscar por nombre o servicio..."
                           value="<?php echo htmlspecialchars($search ?? ''); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <label for="ubicacion" class="form-label">Ubicación</label>
                <select class="form-select" id="ubicacion" name="ubicacion">
                    <option value="">Todas las ubicaciones</option>
                    <?php foreach ($ubicaciones as $ub): ?>
                        <option value="<?php echo htmlspecialchars($ub); ?>" 
                                <?php echo ($ubicacion ?? '') === $ub ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($ub); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    <?php if (!empty($search) || !empty($ubicacion)): ?>
                        <a href="<?php echo BASE_URL; ?>index.php?action=recreos" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Limpiar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de recreos -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-table me-2"></i>
            Lista de Recreos
            <?php if (!empty($search) || !empty($ubicacion)): ?>
                <span class="text-muted">- Filtros activos</span>
            <?php endif; ?>
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($recreos)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Precio</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recreos as $recreo): ?>
                            <tr>
                                <td><?php echo $recreo['id']; ?></td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($recreo['nombre']); ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-geo me-1"></i>
                                            <?php echo htmlspecialchars(substr($recreo['direccion'], 0, 50)) . (strlen($recreo['direccion']) > 50 ? '...' : ''); ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="bi bi-pin-map me-1"></i>
                                        <?php echo htmlspecialchars($recreo['ubicacion']); ?>
                                    </span>
                                </td>
                            <td>
    <?php if (!empty($recreo['precio'])): ?>
        <span class="text-success fw-bold">
            <?php echo htmlspecialchars($recreo['precio']); ?>
        </span>
    <?php else: ?>
        <span class="text-muted">Sin definir</span>
    <?php endif; ?>
</td>

                                <td>
                                    <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=recreos&method=change_status" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $recreo['id']; ?>">
                                        <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                                            <option value="activo" <?php echo $recreo['estado'] === 'activo' ? 'selected' : ''; ?>>
                                                Activo
                                            </option>
                                            <option value="inactivo" <?php echo $recreo['estado'] === 'inactivo' ? 'selected' : ''; ?>>
                                                Inactivo
                                            </option>
                                            <option value="pendiente" <?php echo $recreo['estado'] === 'pendiente' ? 'selected' : ''; ?>>
                                                Pendiente
                                            </option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <small>
                                        <?php echo date('d/m/Y', strtotime($recreo['fecha_creacion'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo BASE_URL; ?>index.php?action=recreos&method=view&id=<?php echo $recreo['id']; ?>" 
                                           class="btn btn-outline-info btn-sm"
                                           title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <a href="<?php echo BASE_URL; ?>index.php?action=recreos&method=edit&id=<?php echo $recreo['id']; ?>" 
                                           class="btn btn-outline-primary btn-sm"
                                           title="Editar recreo">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form method="POST" 
                                              action="<?php echo BASE_URL; ?>index.php?action=recreos&method=delete" 
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este recreo?')">
                                            <input type="hidden" name="id" value="<?php echo $recreo['id']; ?>">
                                            <button type="submit" 
                                                    class="btn btn-outline-danger btn-sm"
                                                    title="Eliminar recreo">
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
                <i class="bi bi-geo-alt fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">No hay recreos registrados</h5>
                <p class="text-muted">
                    <?php if (!empty($search) || !empty($ubicacion)): ?>
                        No se encontraron recreos que coincidan con los filtros aplicados.
                    <?php else: ?>
                        Comienza agregando un nuevo recreo.
                    <?php endif; ?>
                </p>
                <a href="<?php echo BASE_URL; ?>index.php?action=recreos&method=create" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Crear Primer Recreo
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>