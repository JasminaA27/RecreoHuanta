<?php
$pageTitle = 'Editar Cliente API - Sistema Recreos';
$action = 'cliente_api';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-person-gear me-2"></i>
                Editar Cliente API
            </h2>
            <a href="<?php echo BASE_URL; ?>index.php?action=cliente_api" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información del Cliente API</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=cliente_api&method=edit&id=<?php echo $cliente['id']; ?>">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="dni" class="form-label">DNI <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="dni" 
                                   name="dni" 
                                   required
                                   maxlength="20"
                                   value="<?php echo htmlspecialchars($cliente['dni']); ?>"
                                   placeholder="Ingrese el DNI">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="telefono" 
                                   name="telefono"
                                   maxlength="20"
                                   value="<?php echo htmlspecialchars($cliente['telefono'] ?? ''); ?>"
                                   placeholder="Ingrese el teléfono">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nombre" 
                                   name="nombre" 
                                   required
                                   maxlength="100"
                                   value="<?php echo htmlspecialchars($cliente['nombre']); ?>"
                                   placeholder="Ingrese el nombre">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="apellido" 
                                   name="apellido" 
                                   required
                                   maxlength="100"
                                   value="<?php echo htmlspecialchars($cliente['apellido']); ?>"
                                   placeholder="Ingrese el apellido">
                        </div>
                        
                        <div class="col-12">
                            <label for="correo" class="form-label">Correo Electrónico</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="correo" 
                                   name="correo"
                                   maxlength="100"
                                   value="<?php echo htmlspecialchars($cliente['correo'] ?? ''); ?>"
                                   placeholder="Ingrese el correo electrónico">
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="estado" 
                                       name="estado" 
                                       value="1" 
                                       <?php echo $cliente['estado'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="estado">
                                    Cliente activo
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Actualizar Cliente API
                                </button>
                                <a href="<?php echo BASE_URL; ?>index.php?action=cliente_api" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>