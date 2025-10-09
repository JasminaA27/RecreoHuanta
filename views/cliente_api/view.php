<?php
$pageTitle = 'Detalles Cliente API - Sistema Recreos';
$action = 'cliente_api';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-person me-2"></i>
                    Detalles del Cliente API
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Información Personal</h6>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">ID:</th>
                                <td><?php echo $cliente['id']; ?></td>
                            </tr>
                            <tr>
                                <th>DNI:</th>
                                <td><code><?php echo htmlspecialchars($cliente['dni']); ?></code></td>
                            </tr>
                            <tr>
                                <th>Nombre:</th>
                                <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                            </tr>
                            <tr>
                                <th>Apellido:</th>
                                <td><?php echo htmlspecialchars($cliente['apellido']); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Información de Contacto</h6>
                        <table class="table table-sm">
                            <tr>
                                <th width="40%">Teléfono:</th>
                                <td><?php echo !empty($cliente['telefono']) ? htmlspecialchars($cliente['telefono']) : '-'; ?></td>
                            </tr>
                            <tr>
                                <th>Correo:</th>
                                <td><?php echo !empty($cliente['correo']) ? htmlspecialchars($cliente['correo']) : '-'; ?></td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    <?php if ($cliente['estado']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Registro:</th>
                                <td><?php echo date('d/m/Y H:i', strtotime($cliente['fecha_registro'])); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tokens del cliente -->
        <?php if (!empty($cliente['tokens'])): ?>
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-key me-2"></i>
                    Tokens API del Cliente
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Token</th>
                                <th>Estado</th>
                                <th>Fecha Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cliente['tokens'] as $token): ?>
                                <tr>
                                    <td><code><?php echo htmlspecialchars($token['token']); ?></code></td>
                                    <td>
                                        <?php if ($token['estado']): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($token['fecha_reg'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Acciones</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo BASE_URL; ?>index.php?action=cliente_api&method=edit&id=<?php echo $cliente['id']; ?>" 
                       class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>Editar Cliente
                    </a>
                    <a href="<?php echo BASE_URL; ?>index.php?action=cliente_api" 
                       class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Volver a la lista
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>