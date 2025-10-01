<?php
$pageTitle = 'Crear Token API - Sistema Recreos';
$action = 'tokens_api';
include __DIR__ . '/../layouts/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="bi bi-key me-2"></i>
                Crear Nuevo Token API
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
                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=create">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="id_client_api" class="form-label">Cliente API <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_client_api" name="id_client_api" required>
                                <option value="">Seleccionar cliente...</option>
                                <?php foreach ($clientes as $cliente): ?>
                                    <option value="<?php echo $cliente['id']; ?>">
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
                                      placeholder="Ingrese el token API..."></textarea>
                            <div class="form-text">
                                El token debe ser único y seguro. Puede generar uno automáticamente.
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-grid">
                                <button type="button" class="btn btn-outline-info mb-3" onclick="generateToken()">
                                    <i class="bi bi-shuffle me-2"></i>Generar Token Automáticamente
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="estado" 
                                       name="estado" 
                                       value="1" 
                                       checked>
                                <label class="form-check-label" for="estado">
                                    Token activo
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Crear Token
                                </button>
                                <a href="<?php echo BASE_URL; ?>index.php?action=tokens_api" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Form para generar token automático -->
                <form id="generateForm" method="POST" action="<?php echo BASE_URL; ?>index.php?action=tokens_api&method=generate" style="display: none;">
                    <input type="hidden" name="id_client_api" id="generate_client_id">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function generateToken() {
    const clientSelect = document.getElementById('id_client_api');
    const clientId = clientSelect.value;
    
    if (!clientId) {
        alert('Por favor, seleccione un cliente primero.');
        return;
    }
    
    document.getElementById('generate_client_id').value = clientId;
    document.getElementById('generateForm').submit();
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>