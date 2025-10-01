<?php
$pageTitle = 'Editar Token API - Sistema Recreos';
$action = 'tokens_api';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-key me-2"></i>
                Editar Token API
            </h2>
            <a href="<?php echo BASE_URL; ?>index.php?action=tokens_api" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Información del Token</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=edit&id=<?php echo $token['id']; ?>">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="id_client_api" class="form-label">Cliente API <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_client_api" name="id_client_api" required>
                                <option value="">Seleccionar cliente...</option>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?php echo $cliente['id']; ?>"
                                            <?php echo $token['id_client_api'] == $cliente['id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido'] . ' - ' . $cliente['dni']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <label for="token" class="form-label">Token <span class="text-danger">*</span></label>
                            <textarea class="form-control" 
                                      id="token" 
                                      name="token" 
                                      required
                                      rows="3"
                                      placeholder="Ingrese el token API..."><?php echo htmlspecialchars($token['token']); ?></textarea>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="estado" 
                                       name="estado" 
                                       value="1" 
                                       <?php echo $token['estado'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="estado">
                                    Token activo
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-info-circle me-2"></i>Información del Token
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">Fecha de Registro:</small>
                                            <br>
                                            <strong><?php echo date('d/m/Y H:i', strtotime($token['fecha_reg'])); ?></strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">Cliente Actual:</small>
                                            <br>
                                            <strong><?php echo htmlspecialchars($token['nombre'] . ' ' . $token['apellido']); ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Actualizar Token
                                </button>
                                <a href="<?php echo BASE_URL; ?>index.php?action=tokens_api" class="btn btn-secondary">
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