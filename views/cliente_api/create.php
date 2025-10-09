<?php
$pageTitle = 'Crear Cliente API - Sistema Recreos';
$action = 'cliente_api';
include __DIR__ . '/../layouts/header.php';

// Recuperar datos del formulario si hay error
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="bi bi-person-plus me-2"></i>
                    Crear Nuevo Cliente API
                </h4>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['error']; ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo BASE_URL; ?>index.php?action=cliente_api&method=store">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="dni" class="form-label">DNI *</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="dni" 
                                       name="dni" 
                                       value="<?php echo htmlspecialchars($formData['dni'] ?? ''); ?>"
                                       required 
                                       maxlength="8"
                                       pattern="[0-9]{8}"
                                       title="El DNI debe tener 8 dígitos">
                                <div class="form-text">8 dígitos numéricos</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="estado" 
                                           name="estado" 
                                           value="1" 
                                           <?php echo isset($formData['estado']) && $formData['estado'] ? 'checked' : 'checked'; ?>>
                                    <label class="form-check-label" for="estado">Cliente Activo</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="<?php echo htmlspecialchars($formData['nombre'] ?? ''); ?>"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido *</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="apellido" 
                                       name="apellido" 
                                       value="<?php echo htmlspecialchars($formData['apellido'] ?? ''); ?>"
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="<?php echo htmlspecialchars($formData['telefono'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="correo" 
                                       name="correo" 
                                       value="<?php echo htmlspecialchars($formData['correo'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>index.php?action=cliente_api" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Crear Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>