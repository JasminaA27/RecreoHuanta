<?php
$pageTitle = 'Gestión de Clientes API - Sistema Recreos';
$action = 'cliente_api';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-people me-2"></i>
                Gestión de Clientes API
                <span class="badge bg-primary"><?php echo count($clientes); ?></span>
            </h2>
            <a href="<?php echo BASE_URL; ?>index.php?action=cliente_api&method=create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Cliente API
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0"><?php echo $stats['total_clientes'] ?? 0; ?></h4>
                        <p class="mb-0">Total Clientes</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people fs-1"></i>
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
                        <h4 class="mb-0"><?php echo $stats['clientes_activos'] ?? 0; ?></h4>
                        <p class="mb-0">Clientes Activos</p>
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
                        <h4 class="mb-0"><?php echo $stats['clientes_inactivos'] ?? 0; ?></h4>
                        <p class="mb-0">Clientes Inactivos</p>
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
                        <h4 class="mb-0"><?php echo $stats['correos_unicos'] ?? 0; ?></h4>
                        <p class="mb-0">Correos Únicos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-envelope fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros y búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <input type="hidden" name="action" value="cliente_api">
            <div class="col-md-6">
                <label for="search" class="form-label">Buscar Cliente</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search"
                           placeholder="Buscar por nombre, apellido o DNI..."
                           value="<?php echo htmlspecialchars($search ?? ''); ?>">
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="<?php echo BASE_URL; ?>index.php?action=cliente_api" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Limpiar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de clientes API -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-table me-2"></i>
            Lista de Clientes API
            <?php if (!empty($search)): ?>
                <span class="text-muted">- Filtros activos</span>
            <?php endif; ?>
        </h5>
    </div>
    <div class="card-body">
        <?php if (!empty($clientes)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>DNI</th>
                            <th>Nombre Completo</th>
                            <th>Contacto</th>
                            <th>Fecha Registro</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                            <tr>
                                <td><?php echo $cliente['id']; ?></td>
                                <td>
                                    <code><?php echo htmlspecialchars($cliente['dni']); ?></code>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']); ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($cliente['telefono'])): ?>
                                        <div>
                                            <i class="bi bi-telephone me-1"></i>
                                            <?php echo htmlspecialchars($cliente['telefono']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($cliente['correo'])): ?>
                                        <div>
                                            <i class="bi bi-envelope me-1"></i>
                                            <?php echo htmlspecialchars($cliente['correo']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small>
                                        <?php echo date('d/m/Y', strtotime($cliente['fecha_registro'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=cliente_api&method=change_status" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="estado" 
                                                   value="1"
                                                   <?php echo $cliente['estado'] ? 'checked' : ''; ?>
                                                   onchange="this.form.submit()">
                                            <label class="form-check-label">
                                                <?php echo $cliente['estado'] ? 'Activo' : 'Inactivo'; ?>
                                            </label>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo BASE_URL; ?>index.php?action=cliente_api&method=view&id=<?php echo $cliente['id']; ?>" 
                                           class="btn btn-outline-info btn-sm"
                                           title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <a href="<?php echo BASE_URL; ?>index.php?action=cliente_api&method=edit&id=<?php echo $cliente['id']; ?>" 
                                           class="btn btn-outline-primary btn-sm"
                                           title="Editar cliente">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                       <!--  <form method="POST" 
                                              action="<?php echo BASE_URL; ?>index.php?action=cliente_api&method=delete" 
                                              style="display: inline;"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este cliente API?')">
                                            <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
                                            <button type="submit" 
                                                    class="btn btn-outline-danger btn-sm"
                                                    title="Eliminar cliente">
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
                <i class="bi bi-people fs-1 text-muted"></i>
                <h5 class="mt-3 text-muted">No hay clientes API registrados</h5>
                <p class="text-muted">
                    <?php if (!empty($search)): ?>
                        No se encontraron clientes que coincidan con los filtros aplicados.
                    <?php else: ?>
                        Comienza agregando un nuevo cliente API.
                    <?php endif; ?>
                </p>
                <a href="<?php echo BASE_URL; ?>index.php?action=cliente_api&method=create" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Crear Primer Cliente API
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>